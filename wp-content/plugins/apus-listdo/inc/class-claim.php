<?php
/**
 * favorite
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class ApusListdo_Claim {

	public static function init() {

        add_filter( 'listdo_redux_config_sections_after_listing', array( __CLASS__, 'settings' ), 5 );
        
        add_filter( 'listdo_email_template_fields', array( __CLASS__, 'email_template_settings' ), 10, 1 );
        add_filter( 'listdo_listing_single_sort_sidebar', array( __CLASS__, 'sidebar_fields' ) );
        add_filter( 'listdo_listing_display_part', array( __CLASS__, 'display' ), 10, 2 );

        add_action( 'wp_ajax_apuslistdo_claim_listing',  array( __CLASS__, 'claim_listing' ) );
        add_action( 'wp_ajax_nopriv_apuslistdo_claim_listing',  array( __CLASS__, 'claim_listing' ) );
        add_action( 'save_post', array( __CLASS__, 'update_listing' ) );
	}

    public static function sidebar_fields($fields) {
        $fields['claim'] = esc_html__( 'Claim Listing', 'apus-listdo' );
        return $fields;
    }

    public static function display($content, $template) {
        if ( $template == 'claim' ) {
            $content = ApusListdo_Template_Loader::get_template_part( 'claim/'.$template);
        }
        return $content;
    }

    public static function claim_listing() {
        $post_id = !empty($_POST['post_id']) ? $_POST['post_id'] : '';
        $listing = get_post($post_id);
        if ( is_user_logged_in() && is_object($listing) ) {

            $author_nicename = '';
            $emailexist = false;

            $current_user = wp_get_current_user();
            $userID = $current_user->ID;
            $userData = get_user_by( 'id', $userID );
            $claimer_email = $userData->user_email;

            $claimer_name = !empty($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';
            $post_title = $listing->post_title;
            $post_url = get_permalink($listing);
            $posttitle = esc_html__('Claim for', 'apus-listdo').' '. $post_title;
            $message = !empty($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
            $phone = !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

            $details = $claimer_name . ' : ' . $claimer_email . ' : ' . $phone.' : ' . $message;

            $claim_post = array(
                'post_title'    => wp_strip_all_tags( $posttitle ),
                'post_type'   => 'job_claim',
                'post_status'   => 'publish',
            );

            $post_id = wp_insert_post( $claim_post );
            self::uptdate_claim_meta($post_id, 'claim_for', $post_id);
            self::uptdate_claim_meta($post_id, 'status', 'pending');
            self::uptdate_claim_meta($post_id, 'detail', $details);

            // send email
            $args = array(
                'website_url' => site_url(),
                'website_name' => get_option('blogname'),
                'listing_title' => $post_title,
                'listing_url' => $post_url,
                'email' => $claimer_email
            );

            // send email for claimer
            $args['type'] = 'claimer';
            self::send_email($args);

            // send email for admin
            $args['email'] = get_option( 'admin_email' );
            $args['type'] = 'admin';
            self::send_email($args);

            // send email for author
            $post_author = $listing->post_author;
            $user = get_user_by( 'id', $post_author );
            $author_email = $user->user_email;
            $args['email'] = $author_email;
            $args['type'] = 'author';
            self::send_email($args);

            $result = $post_id;

            $return = array(
                'msg' => '<span class="alert alert-success">'.esc_html__('Claim has been submitted.','apus-listdo').'</span>',
                'result' => $result
            );
        } else {
            $return = array(
                'msg' => '<span class="alert alert-error">'.esc_html__('Can not claim this listing', 'apus-listdo').'</span>',
                'result' => false
            );
        }
        echo json_encode($return);
        exit();
    }

    public static function send_email($args) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        extract($args);
        $subject = apuslistdo_get_config('claim_'.$type.'_email_subject');
        $message = apuslistdo_get_config('claim_'.$type.'_email_message');

        $subject = str_replace('{website_url}',$website_url, $subject);
        $subject = str_replace('{listing_title}',$listing_title, $subject);
        $subject = str_replace('{listing_url}',$listing_url, $subject);
        $subject = str_replace('{website_name}',$website_name, $subject);

        $message = str_replace('{website_url}',$website_url, $message);
        $message = str_replace('{listing_title}',$listing_title, $message);
        $message = str_replace('{listing_url}',$listing_url, $message);
        $message = str_replace('{website_name}',$website_name, $message);

        wp_mail( $email, $subject, $message, $headers);
    }

    public static function uptdate_claim_meta($post_id, $key, $value) {
        $prefix = 'listdo_claim_';
        update_post_meta($post_id, $prefix.$key, $value);
    }

    public static function get_claim_meta($post_id, $key, $single = true) {
        $prefix = 'listdo_claim_';
        return get_post_meta($post_id, $prefix.$key, $single);
    }

    public static function email_template_settings($fields) {
        $fields[] = array(
            'id' => 'claimer_email',
            'icon' => true,
            'type' => 'info',
            'raw' => '<h3 style="margin: 0;"> '.esc_html__('Claim Email Templates', 'apus-listdo').'</h3>',
        );
        $fields[] = array(
            'id' => 'claim_claimer_email_subject',
            'type' => 'textarea',
            'title' => esc_html__('Email Subject For Claimer', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );
        $fields[] = array(
            'id' => 'claim_claimer_email_message',
            'type' => 'editor',
            'title' => esc_html__('Email Body For Claimer', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );

        $fields[] = array(
            'id' => 'claim_admin_email_subject',
            'type' => 'textarea',
            'title' => esc_html__('Email Subject For Admin', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );
        $fields[] = array(
            'id' => 'claim_admin_email_message',
            'type' => 'editor',
            'title' => esc_html__('Email Body For Admin', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );

        $fields[] = array(
            'id' => 'claim_author_email_subject',
            'type' => 'textarea',
            'title' => esc_html__('Email Subject For Author', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );
        $fields[] = array(
            'id' => 'claim_author_email_message',
            'type' => 'editor',
            'title' => esc_html__('Email Body For Author', 'apus-listdo'),
            'description' => esc_html__('Variables: ', 'apus-listdo').'{website_url}, {website_name}, {listing_title}, {listing_url}',
        );
        return $fields;
    }

    public static function settings($sections) {
        $sections[] = array(
            'title' => esc_html__('Claim Listing', 'apus-listdo'),
            'fields' => array(
                array(
                    'id' => 'claim_enable',
                    'type' => 'switch',
                    'title' => esc_html__('Enbale Claim', 'apus-listdo'),
                    'default' => 1
                ),
                array(
                    'id' => 'claim_title',
                    'type' => 'text',
                    'title' => esc_html__('Claim Title', 'apus-listdo'),
                ),
                array(
                    'id' => 'claim_banner',
                    'type' => 'media',
                    'title' => esc_html__('Banner', 'apus-listdo'),
                    'subtitle' => esc_html__('Upload a .jpg or .png image.', 'apus-listdo'),
                ),
            )
        );

        return $sections;
    }

    public static function update_listing($post_id) {
        $post_type = get_post_type($post_id);
        
        if ( $post_type != 'job_claim' ) {
            return;
        }

        $claim_for = self::get_claim_meta($post_id, 'claim_for');
        $status = self::get_claim_meta($post_id, 'status');
        $args = array(
            'name'        => $claim_for,
            'post_type'   => 'job_listing',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $listing = get_post($claim_for);
        if( empty($listing) ) {
            return;
        }
        $author_id = $listing->post_author;

        if ( !empty($status) && $status == 'approved' ) {
            
            $oldusermeta = get_user_by( 'id', $author_id );
            $author_email = $oldusermeta->user_email;
            
            update_post_meta( $listing->ID, '_claimed', 1 );
            
            $subjectclaimer = esc_html__('Your Claim has been approved', 'apus-listdo');
            $msgtoclaimer = esc_html__('Congratulation! Your claim has been approved. Details are following', 'apus-listdo');
            $msgtoclaimer .='<br>';
            $msgtoclaimer .= esc_html__('Listing Title : ', 'apus-listdo').get_the_title($claim_for);
            $msgtoclaimer .='<br>';
            $msgtoclaimer .= esc_html__('URL : ', 'apus-listdo').get_the_permalink($claim_for);
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            wp_mail( $author_email, $subjectclaimer, $msgtoclaimer, $headers);
            
        }
        
    }
}

ApusListdo_Claim::init();