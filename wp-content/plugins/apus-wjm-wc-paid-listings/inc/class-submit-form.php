<?php
/**
 * Submit Form
 *
 * @package    apus-wjm-wc-paid-listings
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  25/10/2018 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ApusWJMWCPaidListings_Submit_Form {
	
	public static $listing_package;
	public static $listing_user_package;

	public static function init() {
		add_filter( 'submit_job_steps',  array( __CLASS__, 'submit_job_steps' ), 5, 1 );

		// get listing package
		if ( ! empty( $_POST['awjm_listing_package'] ) ) {
			if ( is_numeric( $_POST['awjm_listing_package'] ) ) {
				self::$listing_package = absint( $_POST['awjm_listing_package'] );
			}
		} elseif ( ! empty( $_COOKIE['chosen_listing_package'] ) ) {
			self::$listing_package  = absint( $_COOKIE['chosen_listing_package'] );
		} elseif ( ! empty( $_POST['awjm_listing_user_package'] ) ) {
			if ( is_numeric( $_POST['awjm_listing_user_package'] ) ) {
				self::$listing_user_package = absint( $_POST['awjm_listing_user_package'] );
			}
		} elseif ( ! empty( $_COOKIE['chosen_listing_user_package'] ) ) {
			self::$listing_user_package = absint( $_COOKIE['chosen_listing_user_package'] );
			if ( ! empty( $_COOKIE['chosen_listing_package'] ) ) {
				unset($_COOKIE['chosen_listing_package']);
				setcookie('chosen_listing_package', null, -1, '/');
			}
		} elseif ( ! empty( $_COOKIE['chosen_listing_package'] ) ) {
			self::$listing_package  = absint( $_COOKIE['chosen_listing_package'] );
		}
	}

	public static function get_products() {
		$query_args = array(
		   	'post_type' => 'product',
		   	'post_status' => 'publish',
			'posts_per_page'   => -1,
			'order'            => 'asc',
			'orderby'          => 'menu_order',
		   	'tax_query' => array(
		        array(
		            'taxonomy' => 'product_type',
		            'field'    => 'slug',
		            'terms'    => array('listing_package'),
		        ),
		    ),
		);
		$posts = get_posts( $query_args );

		return $posts;
	}

	public static function submit_job_steps($steps) {
		
		$packages = self::get_products();

		if ( !empty($packages) ) {
			$steps['wjm-choose-packages'] = array(
				'name'     => esc_html__( 'Choose a package', 'apus-wjm-wc-paid-listings' ),
				'view'     => array( __CLASS__, 'choose_package' ),
				'handler'  => array( __CLASS__, 'choose_package_handler' ),
				'priority' => 1
			);

			$steps['wjm-process-packages'] = array(
				'name'     => '',
				'view'     => false,
				'handler'  => array( __CLASS__, 'process_package_handler' ),
				'priority' => 25
			);

			add_filter( 'submit_job_post_status', array( __CLASS__, 'submit_job_post_status' ), 10, 2 );
		}

		return $steps;
	}

	public static function submit_job_post_status( $status, $job ) {
		if ( $job->post_status === 'preview' ) {
			return 'pending_payment';
		}
		return $status;
	}

	public static function choose_package($atts = array()) {
		echo ApusWJMWCPaidListings_Template_Loader::get_template_part('choose-package-form', array('atts' => $atts) );
	}

	public static function choose_package_handler() {
		$form = WP_Job_Manager_Form_Submit_Job::instance();

		$validation = self::validate_package();

		if ( is_wp_error( $validation ) ) {
			$form->add_error( $validation->get_error_message() );
			$form->set_step( array_search( 'wjm-choose-packages', array_keys( $form->get_steps() ) ) );
			return false;
		}
		if ( self::$listing_user_package ) {
			wc_setcookie( 'chosen_listing_user_package', self::$listing_user_package );
		} elseif ( self::$listing_package ) {
			wc_setcookie( 'chosen_listing_package', self::$listing_package );
		}
		

		$form->next_step();
	}

	private static function validate_package() {
		if ( empty( self::$listing_user_package ) && empty( self::$listing_package )  ) {
			return new WP_Error( 'error', esc_html__( 'Invalid Package', 'apus-wjm-wc-paid-listings' ) );
		} elseif ( self::$listing_user_package ) {
			if ( ! apus_wjm_wc_paid_listings_package_is_valid( get_current_user_id(), self::$listing_user_package ) ) {
				return new WP_Error( 'error', __( 'Invalid Package', 'apus-wjm-wc-paid-listings' ) );
			}
		} elseif ( self::$listing_package ) {
			$package = wc_get_product( self::$listing_package );
			if ( empty($package) || $package->get_type() != 'listing_package' ) {
				return new WP_Error( 'error', esc_html__( 'Invalid Package', 'apus-wjm-wc-paid-listings' ) );
			}
		}

		return true;
	}

	public static function process_package_handler() {
		$form = WP_Job_Manager_Form_Submit_Job::instance();
		$job_id = $form->get_job_id();
		$post_status = get_post_status( $job_id );
		if ( $post_status == 'preview' ) {
			$update_job = array(
				'ID' => $job_id,
				'post_status' => 'pending_payment',
				'post_date' => current_time( 'mysql' ),
				'post_date_gmt' => current_time( 'mysql', 1 ),
				'post_author' => get_current_user_id(),
			);

			wp_update_post( $update_job );
		}

		if ( self::$listing_user_package ) {
			$product_id = get_post_meta(self::$listing_user_package, '_product_id', true);
			
			$feature_listings = get_post_meta(self::$listing_user_package, '_feature_listings', true );
			$featured = 0;
			if ( !empty($feature_listings) && $feature_listings === 'yes' ) {
				$featured = 1;
			}
			update_post_meta( $job_id, '_featured', $featured );
			$listings_duration = get_post_meta(self::$listing_user_package, '_listings_duration', true );
			update_post_meta( $job_id, '_job_duration', $listings_duration );
			update_post_meta( $job_id, '_package_id', $product_id );
			update_post_meta( $job_id, '_user_package_id', self::$listing_user_package );


			// Approve the job
			if ( in_array( get_post_status( $job_id ), array( 'pending_payment', 'expired' ) ) ) {
				apus_wjm_wc_paid_listings_approve_listing_with_package( $job_id, get_current_user_id(), self::$listing_user_package );
			}

			// remove cookie
			wc_setcookie( 'chosen_listing_user_package', '', time() - HOUR_IN_SECONDS );

			do_action( 'awjm_process_user_package_handler',self::$listing_user_package, $job_id );

			$form->next_step();
		} elseif ( self::$listing_package ) {

			$feature_listings = get_post_meta(self::$listing_package, '_feature_listings', true );
			
			$featured = 0;
			if ( !empty($feature_listings) && $feature_listings === 'yes' ) {
				$featured = 1;
			}
			update_post_meta( $job_id, '_featured', $featured );

			$listings_duration = get_post_meta(self::$listing_package, '_listings_duration', true );
			update_post_meta( $job_id, '_job_duration', $listings_duration );

			update_post_meta( $job_id, '_package_id', self::$listing_package );
			
			WC()->cart->add_to_cart( self::$listing_package, 1, '', '', array(
				'job_id' => $job_id
			) );

			wc_add_to_cart_message( self::$listing_package );

			// remove cookie
			wc_setcookie( 'chosen_listing_package', '', time() - HOUR_IN_SECONDS );

			do_action( 'awjm_process_package_handler', self::$listing_package, $job_id );

			wp_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
			exit;
		}
	}

}

ApusWJMWCPaidListings_Submit_Form::init();