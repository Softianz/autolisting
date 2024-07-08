<?php
/**
 * Custom Fields
 *
 * @package    apus-listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ApusListdo_Custom_Fields {
	
	public static $type_id;

	public static function init() {
		// admin
		add_filter( 'job_manager_job_listing_data_fields', array( __CLASS__, 'add_custom_fields' ), 100 );
		add_action( 'init', array( __CLASS__, 'admin_custom_field_actions') );

		// frontend
		add_filter( 'submit_job_form_fields', array( __CLASS__, 'submit_form_fields' ), 100 );
	}

	public static function admin_custom_field_actions() {
		$types = apply_filters('admin_custom_field_actions', array(
			'hidden', 'wp-editor', 'texteditor', 'text'
		));

		foreach ($types as $fieldtype) {
			try {
				add_action( "job_manager_input_{$fieldtype}", function( $key, $field ) use ( $fieldtype ) {
					include ApusListdo_Template_Loader::locate('admin/form-fields/'.$fieldtype);
				}, 1, 2);
			} catch (Exception $e) {

			}
		}
	}

	public static function add_custom_fields($old_fields) {
		global $post;
		$current_user = wp_get_current_user();

		$fields = array();
		
		$custom_all_fields = apuslistdo_get_custom_fields_data();
		if (is_array($custom_all_fields) && sizeof($custom_all_fields) > 0) {

			$dtypes = apuslistdo_get_all_field_types();
	        $available_types = apuslistdo_all_types_available_fields();
	        $required_types = apuslistdo_all_types_required_fields();
			$i = 1;
			foreach ($custom_all_fields as $key => $custom_field) {
				$show_in_admin_edit = isset($custom_field['show_in_admin_edit']) ? $custom_field['show_in_admin_edit'] : 'yes';
				if ( $show_in_admin_edit === 'yes' ) {
					$fieldkey = !empty($custom_field['type']) ? $custom_field['type'] : '';
					if ( !empty($fieldkey) ) {
						$type = '';
						if ( isset($required_types[$fieldkey]) ) {
							$field_data = wp_parse_args( $custom_field, $required_types[$fieldkey]);
							$fieldtype = isset($required_types[$fieldkey]['type']) ? $required_types[$fieldkey]['type'] : '';
						} elseif ( isset($available_types[$fieldkey]) ) {
							$field_data = wp_parse_args( $custom_field, $available_types[$fieldkey]);
    						$fieldtype = isset($available_types[$fieldkey]['type']) ? $available_types[$fieldkey]['type'] : '';
						} elseif ( in_array($fieldkey, $dtypes) ) {
							$fieldkey = isset($custom_field['key']) ? $custom_field['key'] : '';
							$fieldtype = isset($custom_field['type']) ? $custom_field['type'] : '';
							$field_data = $custom_field;
						}
						if ( $fieldtype && $fieldtype != 'heading' ) {
							$fields['_'.$fieldkey] = self::render_field($field_data, $fieldkey, $fieldtype, $i);
						}
					}
					
				}
				$i++;
			}
		}


		if ( isset($fields['_job_title']) ) {
			unset($fields['_job_title']);
		}
		if ( isset($fields['_job_description']) ) {
			unset($fields['_job_description']);
		}

		// if ( isset($fields['_job_category']) ) {
		// 	unset($fields['_job_category']);
		// }
		// if ( isset($fields['_job_type']) ) {
		// 	unset($fields['_job_type']);
		// }
		// if ( isset($fields['_job_amenities']) ) {
		// 	unset($fields['_job_amenities']);
		// }
		
		// if ( isset($fields['_job_regions']) ) {
		// 	unset($fields['_job_regions']);
		// }
		if ( isset($fields['_job_tags']) ) {
			unset($fields['_job_tags']);
		}
		
		//echo "<pre>".print_r($fields,1); die;
		
		if ( !empty($old_fields['_featured']) ) {
			$fields['_featured'] = $old_fields['_featured'];
			$fields['_featured']['priority'] = 1.1;
		}
		if ( !empty($old_fields['_job_expires']) ) {
			$fields['_job_expires'] = $old_fields['_job_expires'];
			$fields['_job_expires']['priority'] = 1.2;
		}
		if ( !empty($old_fields['_job_author']) ) {
			$fields['_job_author'] = $old_fields['_job_author'];
			$fields['_job_author']['priority'] = 100;
		}

		return apply_filters( 'apuslistdo-types-add_custom_fields', $fields, $old_fields);
	}

	public static function submit_form_fields($old_fields) {
		$fields = array();
		
		$custom_all_fields = apuslistdo_get_custom_fields_data();
		if (is_array($custom_all_fields) && sizeof($custom_all_fields) > 0) {
			$package_id = self::get_package_id();
			
			$dtypes = apuslistdo_get_all_field_types();
	        $available_types = apuslistdo_all_types_available_fields();
	        $required_types = apuslistdo_all_types_required_fields();
			$i = 1;

			foreach ($custom_all_fields as $key => $custom_field) {
				$show_in_submit_form = isset($custom_field['show_in_submit_form']) ? $custom_field['show_in_submit_form'] : 'yes';
				$check_package_field = self::check_package_field($custom_field, $package_id);
				if ( $show_in_submit_form === 'yes' && $check_package_field ) {
					$fieldkey = !empty($custom_field['type']) ? $custom_field['type'] : '';
					if ( !empty($fieldkey) ) {
						$type = '';
						if ( isset($required_types[$fieldkey]) ) {
							$field_data = wp_parse_args( $custom_field, $required_types[$fieldkey]);
							$fieldtype = isset($required_types[$fieldkey]['type']) ? $required_types[$fieldkey]['type'] : '';
						} elseif ( isset($available_types[$fieldkey]) ) {
							$field_data = wp_parse_args( $custom_field, $available_types[$fieldkey]);
    						$fieldtype = isset($available_types[$fieldkey]['type']) ? $available_types[$fieldkey]['type'] : '';
						} elseif ( in_array($fieldkey, $dtypes) ) {
							$fieldkey = isset($custom_field['key']) ? $custom_field['key'] : '';
							$fieldtype = isset($custom_field['type']) ? $custom_field['type'] : '';
							$field_data = $custom_field;
						}
						if ( $fieldtype ) {
							$fields['job'][$fieldkey] = self::render_field($field_data, $fieldkey, $fieldtype, $i);
						}
					}
					
				}
				$i++;
			}

		}
		
		return apply_filters( 'apuslistdo-types-submit_form_fields', $fields, $old_fields);
	}

	public static function get_package_id() {
		$listing_user_package = ! empty( $_COOKIE['chosen_listing_user_package'] ) ? intval($_COOKIE['chosen_listing_user_package']) : '';
		$listing_package = ! empty( $_COOKIE['chosen_listing_package'] ) ? intval($_COOKIE['chosen_listing_package']) : '';
		$package_id = 0;
		if ( !empty($listing_user_package) ) {
			$package_id = get_post_meta($listing_user_package, '_product_id', true);
		} elseif ( !empty($listing_package) ) {
			$package_id = $listing_package;
		}
		return apply_filters( 'apuslistdo-types-get_package_id', $package_id);
	}

	public static function check_package_field($field, $package_id) {
		$return = false;
		if ( empty($package_id) ) {
			$return = true;
		}
		if ( empty($field['show_in_package']) ) {
			$return = true;
		}
		if ( !empty($field['show_in_package']) ) {
			$package_display = !empty($field['package_display']) ? $field['package_display'] : array();
			if ( is_array($package_display) && in_array($package_id, $package_display) ) {
				$return = true;
			}
		}
		
		return apply_filters( 'apuslistdo-types-check_package_field', $return, $field, $package_id);
	}

	public static function render_field($field_data, $fieldkey, $fieldtype, $priority) {
		$label = stripslashes(isset($field_data['label']) ? $field_data['label'] : '');
        $placeholder = stripslashes(isset($field_data['placeholder']) ? $field_data['placeholder'] : '');
        $description = stripslashes(isset($field_data['description']) ? $field_data['description'] : '');
        $format = isset($field_data['format']) ? $field_data['format'] : '';
        $required = isset($field_data['required']) ? $field_data['required'] : '';
        $default = isset($field_data['default']) ? $field_data['default'] : '';

		$field = array(
			'label' => $label,
			'type' => $fieldtype,
			'required' => $required,
			'priority' => $priority,
			'description' => $description,
			'placeholder' => $placeholder,
			'format' => $format,
			'default' => $default,
			'type_id' => self::$type_id
		);

		switch ($fieldtype) {
			case 'text':
			case 'number':
			case 'url':
			case 'email':
				$field['type'] = 'text';
				$field['input_type'] = $fieldtype;
				break;
			case 'date':
				$format = !empty($field_data['format']) ? $field_data['format'] : 'date';
				if ( $format == 'date' ) {
					$field['type'] = 'date';
				} else {
					$field['type'] = 'datetime';
				}
				break;
			case 'radio':
			case 'select':
			case 'multiselect':
				$doptions = !empty($field_data['options']) ? $field_data['options'] : array();
				$options = array();
				if ( !empty($placeholder) ) {
					$options = array('' => $placeholder);
				}
				if ( is_array($doptions) ) {
					$options = $doptions;
				} elseif ( !empty($doptions) ) {
					$doptions = explode("\n", str_replace("\r", "", stripslashes($doptions)));
					foreach ($doptions as $val) {
						$options[$val] = $val;
					}
				}
				$field['options'] = $options;
				break;
			case 'file':
				$allow_types = !empty($field_data['allow_types']) ? $field_data['allow_types'] : array();
				$multiples = !empty($field_data['multiple_files']) ? $field_data['multiple_files'] : false;
				$ajax = !empty($field_data['ajax']) ? $field_data['ajax'] : false;

				if ( !empty($allow_types) ) {
					$allowed_mime_types = array();
					$mime_types = get_allowed_mime_types();
					foreach ($allow_types as $mime_type) {
						if ( isset($mime_types[$mime_type]) ) {
							$allowed_mime_types[$mime_type] = $mime_types[$mime_type];
						}
					}
					$field['allowed_mime_types'] = $allowed_mime_types;
				}
				$field['multiple'] = $multiples ? true : false;
				$field['ajax'] = $ajax ? true : false;
				if ( $ajax ) {
					$field['file_limit'] = !empty($field_data['file_limit']) ? $field_data['file_limit'] : 10;
				}
				break;
			case 'heading':
				$field['icon'] = !empty($field_data['icon']) ? $field_data['icon'] : '';
				$field['number_columns'] = !empty($field_data['number_columns']) ? $field_data['number_columns'] : '';
			case 'repeater':
				$field['fields'] = !empty($field_data['fields']) ? $field_data['fields'] : array();
				break;
		}
    	
    	switch ($fieldkey) {
			case 'job_description':
				$field['type'] = !empty($field_data['select_type']) ? $field_data['select_type'] : 'wp-editor';
			break;
			case 'job_category':
			case 'job_amenities':
			case 'job_type':
				$field['type'] = !empty($field_data['select_type']) ? $field_data['select_type'] : 'term-select';
				$field['taxonomy'] = !empty($field_data['taxonomy']) ? $field_data['taxonomy'] : '';
				break;
			case 'job_start_date':
			case 'job_finish_date':
				$field['type'] = 'datetime';
			case 'job_gallery_images':
				$field['multiple'] = !empty($field_data['multiple']) ? $field_data['multiple'] : true;
				break;
			case 'job_regions':
			case 'job_categories':
				$field['taxonomy'] = !empty($field_data['taxonomy']) ? $field_data['taxonomy'] : '';
				break;
		}

		return apply_filters( 'apuslistdo-types-render_field', $field, $field_data, $fieldkey, $fieldtype, $priority);
	}

}
ApusListdo_Custom_Fields::init();