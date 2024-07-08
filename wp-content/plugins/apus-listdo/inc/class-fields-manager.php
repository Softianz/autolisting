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
 
class ApusListdo_Fields_Manager {

	public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_page' ), 1 );
        add_action( 'init', array(__CLASS__, 'init_hook'), 10 );
	}

    public static function register_page() {
        add_submenu_page( 'edit.php?post_type=job_listing', __( 'Fields Manager', 'apus-listdo' ), __( 'Fields Manager', 'apus-listdo' ), 'manage_options', 'job-manager-fields-manager', array( __CLASS__, 'output' ) );
    }

    public static function init_hook() {

        // custom fields
        add_action( 'wp_ajax_apuslistdo_custom_field_html', array( __CLASS__, 'custom_field_html' ) );
        add_action( 'wp_ajax_nopriv_apuslistdo_custom_field_html', array( __CLASS__, 'custom_field_html' ) );

        add_action( 'wp_ajax_apuslistdo_custom_field_available_html', array( __CLASS__, 'custom_field_available_html' ) );
        add_action( 'wp_ajax_nopriv_apuslistdo_custom_field_available_html', array( __CLASS__, 'custom_field_available_html' ) );


        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'scripts' ), 1 );
    }

    public static function scripts() {
        
        wp_enqueue_style('jquery-fonticonpicker', APUSLISTDO_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.min.css', array(), '1.0');
        wp_enqueue_style('jquery-fonticonpicker-bootstrap', APUSLISTDO_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.bootstrap.min.css', array(), '1.0');
        wp_enqueue_script('jquery-fonticonpicker', APUSLISTDO_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.min.js', array(), '1.0', true);

        wp_enqueue_style('apuslistdo-custom-field-css', APUSLISTDO_PLUGIN_URL . 'assets/admin/style.css');
        wp_register_script('apuslistdo-custom-field', APUSLISTDO_PLUGIN_URL.'assets/admin/functions.js', array('jquery', 'wp-color-picker', 'jquery-ui-sortable'), '', true);

        $args = array(
            'plugin_url' => APUSLISTDO_PLUGIN_URL,
            'ajax_url' => admin_url('admin-ajax.php'),
        );
        wp_localize_script('apuslistdo-custom-field', 'apuslistdo_customfield_common_vars', $args);
        wp_enqueue_script('apuslistdo-custom-field');
    }

    public static function output() {
        self::save();
        ?>
        <h1><?php echo esc_html__('Fields manager', 'apus-listdo'); ?></h1>

        <form class="job-manager-options" method="post" action="admin.php?page=job-manager-fields-manager">
            
            <?php echo ApusListdo_Template_Loader::get_template_part( 'admin/fields-settings' ); ?>
            
        </form>
        <?php
    }

    public static function save() {
        if ( isset( $_POST['updateListingType'] ) ) {

            $custom_field_final_array = $counts = array();
            if (isset($_POST['apuslistdo-custom-fields-type']) && sizeof($_POST['apuslistdo-custom-fields-type']) > 0) {
                $field_index = 0;

                foreach ($_POST['apuslistdo-custom-fields-type'] as $field_type) {
                    $custom_fields_id = isset($_POST['apuslistdo-custom-fields-id'][$field_index]) ? $_POST['apuslistdo-custom-fields-id'][$field_index] : '';
                    $counter = 0;
                    if ( isset($counts[$field_type]) ) {
                        $counter = $counts[$field_type];
                    }
                    $custom_field_final_array[] = self::custom_field_ready_array($counter, $field_type, $custom_fields_id);
                    $counter++;
                    $counts[$field_type] = $counter;
                    $field_index++;
                }
            }
            
            update_option('listdo_custom_fields_data', $custom_field_final_array);
            
        }
    }

    public static function custom_field_ready_array($array_counter = 0, $field_type = '', $custom_fields_id = '') {
        $custom_field_element_array = array();
        $custom_field_element_array['type'] = $field_type;
        if ( !empty($_POST["apuslistdo-custom-fields-{$field_type}"]) ) {
            foreach ($_POST["apuslistdo-custom-fields-{$field_type}"] as $field => $value) {
                if ( isset($value[$custom_fields_id]) ) {
                    $custom_field_element_array[$field] = $value[$custom_fields_id];
                } elseif ( isset($value[$array_counter]) ) {
                    $custom_field_element_array[$field] = $value[$array_counter];
                }
            }
        }

        return $custom_field_element_array;
    }

    public static function custom_field_html() {
        $fieldtype = $_POST['fieldtype'];
        $global_custom_field_counter = $_REQUEST['global_custom_field_counter'];
        $li_rand_id = rand(454, 999999);
        $html = '<li class="custom-field-class-' . $li_rand_id . '">';
        $types = apuslistdo_get_all_field_types();
        if ( in_array($fieldtype, $types) ) {
            if ( in_array( $fieldtype, array('text', 'textarea', 'wp-editor', 'number', 'url', 'email', 'checkbox') ) ) {
                $html .= apply_filters( 'apuslistdo_custom_field_text_html', $fieldtype, $global_custom_field_counter, '' );
            } elseif ( in_array( $fieldtype, array('select', 'multiselect', 'radio') ) ) {
                $html .= apply_filters( 'apuslistdo_custom_field_opts_html', $fieldtype, $global_custom_field_counter, '' );
            } else {
                $html .= apply_filters('apuslistdo_custom_field_'.$fieldtype.'_html', $fieldtype, $global_custom_field_counter, '');
            }
        }
        // action btns
        $html .= apply_filters('apuslistdo_custom_field_actions_html', $li_rand_id, $global_custom_field_counter, $fieldtype);
        $html .= '</li>';
        echo json_encode( array('html' => $html) );
        wp_die();
    }

    public static function custom_field_available_html() {
        $fieldtype = $_POST['fieldtype'];
        $global_custom_field_counter = $_REQUEST['global_custom_field_counter'];
        $li_rand_id = rand(454, 999999);
        $html = '<li class="custom-field-class-' . $li_rand_id . '">';
        $types = apuslistdo_all_types_available_fields();

        if ( isset($types[$fieldtype]) ) {

            $dtypes = apply_filters( 'apuslistdo_list_simple_type', array('job_title', 'job_hours', 'job_email', 'job_tagline', 'job_location', 'job_email', 'job_website', 'job_phone', 'job_video', 'job_date', 'job_start_date', 'job_finish_date', 'job_socials', 'job_price_from', 'job_price_to', 'job_price_range', 'job_hours', 'job_menu_prices', 'job_tags', 'job_regions', 'job_categories' ) );
            if ( in_array( $fieldtype, $dtypes ) ) {
                $html .= apply_filters( 'apuslistdo_custom_field_available_simple_html', $fieldtype, $global_custom_field_counter, $types[$fieldtype] );
            } elseif ( in_array( $fieldtype, array('job_category', 'job_amenities', 'job_type') ) ) {
                $html .= apply_filters( 'apuslistdo_custom_field_available_tax_html', $fieldtype, $global_custom_field_counter, $types[$fieldtype] );
            } elseif ( in_array($fieldtype, array( 'job_logo', 'job_cover_image' ) )) {
                $html .= apply_filters( 'apuslistdo_custom_field_available_file_html', $fieldtype, $global_custom_field_counter, $types[$fieldtype] );
            } elseif ( in_array($fieldtype, array( 'job_gallery', 'job_gallery_images') )) {
                $html .= apply_filters( 'apuslistdo_custom_field_available_files_html', $fieldtype, $global_custom_field_counter, $types[$fieldtype] );
            } else {
                $html .= apply_filters( 'apuslistdo_custom_field_available_'.$fieldtype.'_html', $fieldtype, $global_custom_field_counter, $types[$fieldtype] );
            }
        }

        // action btns
        $html .= apply_filters('apuslistdo_custom_field_actions_html', $li_rand_id, $global_custom_field_counter, $fieldtype);
        $html .= '</li>';
        echo json_encode(array('html' => $html));
        wp_die();
    }

}

ApusListdo_Fields_Manager::init();


