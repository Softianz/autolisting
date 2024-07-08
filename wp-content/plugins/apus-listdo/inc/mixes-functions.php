<?php
/**
 * functions
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function apuslistdo_get_config($name, $default = '') {
    global $apus_options;
    if ( isset($apus_options[$name]) ) {
        return $apus_options[$name];
    }
    return $default;
}

function apuslistdo_removefilter($tag, $args) {
    remove_filter( $tag, $args );
}

function apuslistdo_addmetaboxes($fnc) {
    add_action( 'add_meta_boxes', $fnc );
}

function apuslistdo_addmetabox($key, $title, $fnc, $textdomain, $position, $priority, $args = null){
    add_meta_box( $key, $title, $fnc, $textdomain, $position, $priority, $args );
}

function apus_wjm_send_mail($to, $subject, $message, $headers){
    return wp_mail( $to, $subject, $message, $headers );
}


function apuslistdo_get_default_field_types() {
    
    $fields = apply_filters( 'apuslistdo_get_default_field_types', array(
        array(
            'title' => esc_html__('Direct Input', 'apus-listdo'),
            'fields' => array(
                'text' => esc_html__('Text', 'apus-listdo'),
                'textarea' => esc_html__('Textarea', 'apus-listdo'),
                'wp-editor' => esc_html__('WP Editor', 'apus-listdo'),
                'date' => esc_html__('Date', 'apus-listdo'),
                'number' => esc_html__('Number', 'apus-listdo'),
                'url' => esc_html__('Url', 'apus-listdo'),
                'email' => esc_html__('Email', 'apus-listdo'),
            )
        ),
        array(
            'title' => esc_html__('Choices', 'apus-listdo'),
            'fields' => array(
                'select' => esc_html__('Select', 'apus-listdo'),
                'multiselect' => esc_html__('Multiselect', 'apus-listdo'),
                'checkbox' => esc_html__('Checkbox', 'apus-listdo'),
                'radio' => esc_html__('Radio Buttons', 'apus-listdo'),
            )
        ),
        array(
            'title' => esc_html__('Form UI', 'apus-listdo'),
            'fields' => array(
                'heading' => esc_html__('Heading', 'apus-listdo')
            )
        ),
        array(
            'title' => esc_html__('Others', 'apus-listdo'),
            'fields' => array(
                'file' => esc_html__('File', 'apus-listdo')
            )
        ),
    ));
    
    return $fields;
}

function apuslistdo_get_all_field_types() {
    $fields = apuslistdo_get_default_field_types();
    $return = array();
    foreach ($fields as $group) {
        foreach ($group['fields'] as $key => $value) {
            $return[] = $key;
        }
    }

    return apply_filters( 'apuslistdo_get_all_field_types', $return );
}

function apuslistdo_all_types_required_fields() {
    return apply_filters( 'apuslistdo-custom-required-fields', array() );
}

function apuslistdo_all_types_available_fields() {
    return apply_filters( 'apuslistdo-custom-available-fields', array() );
}

function apuslistdo_get_custom_fields_data() {
    return apply_filters( 'apuslistdo-get-custom-fields-data', get_option('listdo_custom_fields_data', array()) );
}


function apuslistdo_display_hooks() {
    return apply_filters( 'apuslistdo_display_hooks', array() );
}

function apuslistdo_icon_picker($value = '', $id = '', $name = '', $class = 'apuslistdo-icon-pickerr') {

    $html = "
    <script>
    jQuery(document).ready(function ($) {
        setTimeout(function(){
            var e9_element = $('#icon_picker_".$id."').fontIconPicker({
                theme: 'fip-bootstrap',
                source: all_loaded_icons
            });
        }, 100);
    });
    </script>";

    $html .= '<input type="text" id="icon_picker_' . $id . '" class="' . $class . '" name="' . $name . '" value="' . $value . '">';

    return $html;
}




