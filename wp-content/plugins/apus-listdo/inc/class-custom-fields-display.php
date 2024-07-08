<?php
/**
 * template loader
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ApusListdo_Custom_Fields_Display {
	
	public static function init() {
		add_action('init', array(__CLASS__, 'hooks'));
	}

	public static function hooks() {
		$hooks = apuslistdo_display_hooks();

		foreach ($hooks as $hook => $title) {
			if ( !empty($hook) ) {
				add_action( $hook, function($post) use ( $hook ) {
					self::display_hook($post, $hook);
				}, 100 );
			}
		}
	}

	public static function display_hook($post, $current_hook) {
		$custom_fields = apuslistdo_get_custom_fields_data();

		if (is_array($custom_fields) && sizeof($custom_fields) > 0) {
			foreach ($custom_fields as $key => $custom_field) {
				$hook_display = !empty($custom_field['hook_display']) ? $custom_field['hook_display'] : '';
				if ( !empty($hook_display) && $hook_display == $current_hook ) {
					echo self::display_field_data($custom_field, $post, $current_hook);
				}
			}
		}
	}

	public static function display_field_data($custom_field, $post, $current_hook) {
		$field_type = !empty($custom_field['type']) ? $custom_field['type'] : '';
		$field_key = !empty($custom_field['key']) ? $custom_field['key'] : '';
		$field_label = !empty($custom_field['label']) ? $custom_field['label'] : '';
		$value = get_post_meta( $post->ID, '_'.$field_key, true );
        if ( empty($value) ) {
            return;
        }
		$output_value = '';
		switch ( $field_type ) {
            case 'text':
            case 'number':
            case 'email':
            case 'select':
            case 'radio':
                $output_value = $value;
                break;
            case 'textarea':
            case 'wp-editor':
                $output_value = wpautop($value);
                break;
            case 'url':
                $output_value = '<a href="'.esc_url($value).'">'.$value.'</a>';
                break;
            case 'date':
            	$output_value = strtotime($value);
            	$output_value = date(get_option('date_format'), $output_value);
                break;
            case 'checkbox':
            	$output_value = $value ? esc_html__('Yes', 'apus-listdo') : esc_html__('No', 'apus-listdo');
            	break;
            case 'multiselect':
                if ( is_array($value) ) {
                	$output_value = implode(', ', $value);
                }
                break;
            case 'file':
                $return = '';
                if ( is_array($value) ) {
                	foreach ($value as $file) {
                		if ( self::check_image_mime_type($file) ) {
                			$return .= '<img src="'.esc_url($file).'">';
                		} else {
                			$return .= '<a href="'.esc_url($file).'">'.esc_html__('Download file', 'apus-listdo').'</a>';
                		}
                	}
                } elseif ( !empty($value) ) {
                	if ( self::check_image_mime_type($value) ) {
            			$return .= '<img src="'.esc_url($value).'">';
            		} else {
            			$return .= '<a href="'.esc_url($value).'">'.esc_html__('Download file', 'apus-listdo').'</a>';
            		}
                }
                $output_value = $return;
        }
        ob_start();
        ?>
        <div class="custom-field-data">
        	<?php if ( $field_label ) { ?>
	        	<h5><?php echo trim($field_label); ?></h5>
	        <?php } ?>
	        <div class="content"><?php echo trim($output_value); ?></div>
        </div>
        <?php
        $html = ob_get_clean();
        return apply_filters( 'apus-listdo-display_field_data', $html, $custom_field, $post, $field_label, $output_value, $current_hook );
	}

	public static function check_image_mime_type($image_path) {
		$filetype = strtolower(substr(strstr($image_path, '.'), 1));
	    $mimes  = array( "gif", "jpg", "png", "ico");

	    if ( in_array($filetype, $mimes) ) {
	        return true;
	    } else {
	        return false;
	    }
	}
}

ApusListdo_Custom_Fields_Display::init();