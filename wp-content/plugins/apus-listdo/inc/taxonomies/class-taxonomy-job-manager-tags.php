<?php
/**
 * Tags
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ApusListdo_Taxonomy_Tags{

	/**
	 *
	 */
	public static function init() {
		global $wp_taxonomies;

		if ( isset( $wp_taxonomies['job_listing_tag'] ) ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'definition' ), 1 );

		add_filter( 'submit_job_form_fields', array( __CLASS__, 'job_tag_field' ) );
		add_filter( 'submit_job_form_validate_fields', array( __CLASS__, 'validate_job_tag_field' ), 10, 3 );
		add_action( 'job_manager_update_job_data', array( __CLASS__, 'save_job_tag_field' ), 10, 2 );
		add_action( 'submit_job_form_fields_get_job_data', array( __CLASS__, 'get_job_tag_field_data' ), 10, 2 );
	}

	/**
	 *
	 */
	public static function definition() {
		$labels = array(
			'name'              => __( 'Tags', 'apus-listdo' ),
			'singular_name'     => __( 'Tag', 'apus-listdo' ),
			'search_items'      => __( 'Search Tags', 'apus-listdo' ),
			'all_items'         => __( 'All Tags', 'apus-listdo' ),
			'parent_item'       => __( 'Parent Tag', 'apus-listdo' ),
			'parent_item_colon' => __( 'Parent Tag:', 'apus-listdo' ),
			'edit_item'         => __( 'Edit', 'apus-listdo' ),
			'update_item'       => __( 'Update', 'apus-listdo' ),
			'add_new_item'      => __( 'Add New', 'apus-listdo' ),
			'new_item_name'     => __( 'New Tag', 'apus-listdo' ),
			'menu_name'         => __( 'Tags', 'apus-listdo' ),
		);

		register_taxonomy( 'job_listing_tag', 'job_listing', array(
			'labels'            => apply_filters( 'apuslistdo_taxomony_booking_tags_labels', $labels ),
			'hierarchical'      => false,
			'query_var'         => 'listing-tag',
			'rewrite'           => array( 'slug' => __( 'listing-tag', 'apus-listdo' ) ),
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> true
		) );
	}

	public static function job_tag_field($fields) {
		$max  = 15;
		$fields['job']['job_tags'] = array(
			'label'       => __( 'Job tags', 'apus-listdo' ),
			'description' => __( 'Comma separate tags, such as required skills or technologies, for this job.', 'apus-listdo' ) . $max,
			'type'        => 'text',
			'required'    => false,
			'placeholder' => __( 'e.g. PHP, Social Media, Management', 'apus-listdo' ),
			'priority'    => "4.5"
		);
		return $fields;
	}

	public static function validate_job_tag_field( $passed, $fields, $values ) {
		if ( empty($values['job']['job_tags']) ) {
			return $passed;
		}
		$max  = 15;
		$tags = is_array( $values['job']['job_tags'] ) ? $values['job']['job_tags'] : array_filter( explode( ',', $values['job']['job_tags'] ) );

		if ( $max && sizeof( $tags ) > $max )
			return new WP_Error( 'validation-error', sprintf( __( 'Please enter no more than %d tags.', 'jobpro' ), $max ) );

		return $passed;
	}

	public static function format_job_tag( $tag ) {
		// We'll assume that small tags less than or equal to 3 chars are abbreviated. Uppercase them.
		if ( strlen( $tag ) <= 3 ) {
			$tag = strtoupper( $tag );
		} else {
			$tag = strtolower( $tag );
		}
		return $tag;
	}

	/**
	 * Save posted tags to the job
	 */
	public static function save_job_tag_field( $job_id, $values ) {
		if ( empty($values['job']['job_tags']) ) {
			return;
		}
		if ( is_array( $values['job']['job_tags'] ) ) {
			$tags = array_map( 'absint', $values['job']['job_tags'] );
		} else {
			$raw_tags = array_filter( array_map( 'sanitize_text_field', explode( ',', $values['job']['job_tags'] ) ) );

			// Loop tags we want to set and put them into an array
			$tags = array();

			foreach ( $raw_tags as $tag ) {
				$tags[] = self::format_job_tag( $tag );
			}
		}

		if ( ! empty( $tags ) ) {
			wp_set_object_terms( $job_id, $tags, 'job_listing_tag', false );
		}
	}

	public static function get_job_tag_field_data( $data, $job ) {
		if ( !empty($data[ 'job' ][ 'job_tags' ]) ) {
			$data[ 'job' ][ 'job_tags' ]['value'] = implode( ', ', wp_get_object_terms( $job->ID, 'job_listing_tag', array( 'fields' => 'names' ) ) );
		}
		
		return $data;
	}

	public static function get_job_tag_list( $job_id ) {
		$terms = get_the_term_list( $job_id, 'job_listing_tag', '', apply_filters( 'job_manager_tag_list_sep', ', ' ), '' );

		return $terms;
	}
}

ApusListdo_Taxonomy_Tags::init();