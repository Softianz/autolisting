<?php
/**
 * apus listdo
 *
 * @package    listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// for custyom admin 'job_manager_input_' . $type

class Listdo_Submittion{

	public static function init() {
		// save [front, back]
		add_filter( 'register_post_type_job_listing', array( __CLASS__, 'change_post_type_label' ), 10 );

		// custom slug
		add_filter( 'submit_job_form_prefix_post_name_with_company', array( __CLASS__, 'slug_only_title' ), 100 );
		add_filter( 'submit_job_form_prefix_post_name_with_location', array( __CLASS__, 'slug_only_title' ), 100 );
		add_filter( 'submit_job_form_prefix_post_name_with_job_type', array( __CLASS__, 'slug_only_title' ), 100 );
		
		if ( function_exists('apuslistdo_addmetaboxes') ) {
			apuslistdo_addmetaboxes( array( __CLASS__, 'metaboxes' ) );
		}

		add_filter('apuslistdo-types-add_custom_fields', array( __CLASS__, 'admin_fields' ), 10 );
		// admin fields
		add_action( 'job_manager_input_listdo-location', array( __CLASS__, 'input_location_fields' ), 10, 2 );

		add_action( 'job_manager_input_datetime', array( __CLASS__, 'input_datetime_fields' ), 10, 2 );
		add_action( 'job_manager_input_date', array( __CLASS__, 'input_date_fields' ), 10, 2 );

		add_action( 'job_manager_input_listdo-regions', array( __CLASS__, 'input_regions_fields' ), 10, 2 );
		add_action( 'job_manager_input_term-select', array( __CLASS__, 'input_term_select_fields' ), 10, 2 );
		add_action( 'job_manager_input_term-multiselect', array( __CLASS__, 'input_term_multi_fields' ), 10, 2 );
		add_action( 'job_manager_input_term-checklist', array( __CLASS__, 'input_term_checklist_fields' ), 10, 2 );

		// region field
		add_action( 'wp_ajax_listdo_process_change_region', array( __CLASS__, 'process_change_region' ) );
		add_action( 'wp_ajax_nopriv_listdo_process_change_region', array( __CLASS__, 'process_change_region' ) );

		// submit_job_form_fields_get_job_data
		add_action( 'submit_job_form_fields_get_job_data', array( __CLASS__, 'get_job_data' ), 10, 2 );
		// save data
		add_action( 'job_manager_update_job_data', array( __CLASS__, 'listing_submit' ), 10, 2 );
		add_action( 'job_manager_save_job_listing', array( __CLASS__, 'listing_update_job_data' ), 100, 2 );
		add_action( 'job_manager_save_job_listing', array( __CLASS__, 'listing_update' ), 100, 2 );

		add_filter('submit_job_form_save_job_data', array( __CLASS__, 'submit_job_form_save_job_data' ) );

		add_filter( 'job_manager_job_listing_data_fields', array( __CLASS__, 'add_custom_fields' ), 200 );
	}

	public static function change_post_type_label($args) {
		$singular = esc_html__( 'Listing', 'listdo' );
		$plural   = esc_html__( 'Listings', 'listdo' );

		$args['labels']      = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'menu_name'          => $plural,
			'all_items'          => sprintf( esc_html__( 'All %s', 'listdo' ), $plural ),
			'add_new'            => esc_html__( 'Add New', 'listdo' ),
			'add_new_item'       => sprintf( esc_html__( 'Add %s', 'listdo' ), $singular ),
			'edit'               => esc_html__( 'Edit', 'listdo' ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'listdo' ), $singular ),
			'new_item'           => sprintf( esc_html__( 'New %s', 'listdo' ), $singular ),
			'view'               => sprintf( esc_html__( 'View %s', 'listdo' ), $singular ),
			'view_item'          => sprintf( esc_html__( 'View %s', 'listdo' ), $singular ),
			'search_items'       => sprintf( esc_html__( 'Search %s', 'listdo' ), $plural ),
			'not_found'          => sprintf( esc_html__( 'No %s found', 'listdo' ), $plural ),
			'not_found_in_trash' => sprintf( esc_html__( 'No %s found in trash', 'listdo' ), $plural ),
			'parent'             => sprintf( esc_html__( 'Parent %s', 'listdo' ), $singular )
		);
		$args['description'] = sprintf( esc_html__( 'This is where you can create and manage %s.', 'listdo' ), $plural );
		$args['supports']    = array( 'title', 'editor', 'custom-fields', 'publicize', 'comments', 'thumbnail' );
		
		
		return $args;
	}

	public static function slug_only_title($return) {
		return false;
	}

	public static function add_custom_fields($fields) {
		if ( listdo_get_config('claim_enable') ) {
			$fields['_claimed'] = array(
				'label'       => esc_html__( 'Claimed:', 'listdo' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'The owner has been verified.', 'listdo' ),
				'priority'    => 2.3,
				'required' => false,
			);
		}
		
		$opts = array(
			'' => esc_html__('Global Settings', 'listdo'),
			'v1' => esc_html__('Version 1', 'listdo'),
			'v2' => esc_html__('Version 2', 'listdo')
		);

		$fields['_layout_type'] = array(
			'label'       => esc_html__( 'Layout Type', 'listdo' ),
			'type'        => 'select',
			'description' => esc_html__( 'Choose layout for listing', 'listdo' ),
			'priority'    => 100,
			'required' => false,
			'options' => $opts
		);

		return $fields;
	}

	public static function metaboxes( $post_type ) {
		$post_types = array( 'job_listing' );
 		global $post;

        if ( in_array( $post_type, $post_types ) && function_exists('apuslistdo_addmetabox') ) {

            apuslistdo_addmetabox(
            	'job_listing_images',
                esc_html__( 'Listing Images', 'listdo' ),
                array( __CLASS__, 'render_meta_box_settings' ),
                $post_type,
                'side',
                'low'
            );

            if ( class_exists('ApusListdo_Custom_Fields') ) {
	            $fields = ApusListdo_Custom_Fields::add_custom_fields(array());
	            if ( !empty($fields) ) {
	            	foreach ($fields as $key => $field) {
	            		if ( $field['type'] == 'repeater' ) {
	            			$label = !empty($field['label']) ? $field['label'] : esc_html__('Repeater', 'listdo');
	            			apuslistdo_addmetabox(
	            				'job_listing_'.$key,
				                $label,
				                array( __CLASS__, 'render_meta_box_repeater' ),
				                $post_type,
				                'normal',
				                'low',
				                array('key' => $key, 'field' => $field)
				            );
	            		} elseif ( $field['type'] == 'listdo-menu-prices' ) {
	            			$label = !empty($field['label']) ? $field['label'] : esc_html__('Menu Prices', 'listdo');
	            			apuslistdo_addmetabox(
	            				'job_listing_'.$key,
				                $label,
				                array( __CLASS__, 'render_meta_box_menu_prices' ),
				                $post_type,
				                'normal',
				                'low',
				                array('key' => $key, 'field' => $field)
				            );
	            		} elseif ( $field['type'] == 'listdo-hours' ) {
	            			$label = !empty($field['label']) ? $field['label'] : esc_html__('Hours Operation', 'listdo');
	            			apuslistdo_addmetabox(
	            				'job_listing_'.$key,
				                $label,
				                array( __CLASS__, 'render_meta_box_hours' ),
				                $post_type,
				                'normal',
				                'low',
				                array('key' => $key, 'field' => $field)
				            );
	            		}
	            	}
	            }
	        }


        }
	}

	public static function render_meta_box_repeater( $post, $args ) {
		$key = !empty($args['args']['key']) ? $args['args']['key'] : '';
		$field = !empty($args['args']['field']) ? $args['args']['field'] : '';

		listdo_repeater_rows_html( $field['label'], $field['fields'], get_post_meta( $post->ID, $key, true ) );
	}

	public static function render_meta_box_schedule( $post, $args ) {
		$key = !empty($args['args']['key']) ? $args['args']['key'] : '';
		$field = !empty($args['args']['field']) ? $args['args']['field'] : '';

		self::input_schedule_fields( $key, $field );
	}

	public static function render_meta_box_menu_prices( $post, $args ) {
		$key = !empty($args['args']['key']) ? $args['args']['key'] : '';
		$field = !empty($args['args']['field']) ? $args['args']['field'] : '';

		self::input_menu_prices_fields( $key, $field );
	}

	public static function render_meta_box_hours( $post, $args ) {
		$key = !empty($args['args']['key']) ? $args['args']['key'] : '';
		$field = !empty($args['args']['field']) ? $args['args']['field'] : '';

		self::input_hours_fields( $key, $field );
	}

	public static function check_type_has_field($fields, $field_type) {
		if (is_array($fields) && sizeof($fields) > 0) {
			foreach ($fields as  $field) {
				if ( !empty($field['type']) && $field['type'] == $field_type ) {
					return true;
				}
			}
		}
		return false;
	}

	public static function render_meta_box_settings( $post ) {
	    global $post;
		if ( !empty($post) ) {

			$fields = get_option('listdo_custom_fields_data', true);
			$has_job_logo = self::check_type_has_field($fields, 'job_logo');
			if ( $has_job_logo ) {
				$job_logo = get_post_meta($post->ID, '_job_logo', true);

    	?>
		    	<div class="listdo-listing-side-meta">
		    		<h3><?php esc_html_e('Listing Logo', 'listdo'); ?></h3>
		    		<div id="upload-image-logo-images" class="images">
		    			<?php if ( $job_logo ) {
		    				$image_src = wp_get_attachment_image_src( absint( $job_logo ) );
							$image_src = $image_src ? $image_src[0] : '';
	    				?>
	    					<div class="image-wrapper">
		    					<img src="<?php echo esc_url($image_src); ?>">
				    			<input type="hidden" name="_job_logo" value="<?php echo esc_attr($job_logo); ?>">
				    			<span class="remove-image"><i class="fas fa-times"></i></span>
			    			</div>
		    			<?php } ?>
		    		</div>
		    		<button id="upload-image-logo" type="button" class="button"><?php esc_html_e('Add logo image', 'listdo'); ?></button>
		    		
		    	</div>

	    	<?php }

	    	$has_job_gallery_images = self::check_type_has_field($fields, 'job_gallery_images');
	    	if ( $has_job_gallery_images ) {
				$gallery_images = get_post_meta($post->ID, '_job_gallery_images', true);
				if ( $has_job_logo ) {
					?>
					<br>
	    			<hr>
					<?php
				}
    	?>		
		    	<div class="listdo-listing-side-meta">
		    		<h3><?php esc_html_e('Gallery Images', 'listdo'); ?></h3>
		    		<div id="upload-image-gallery-images" class="images">
		    			<?php if ( $gallery_images ) {
		    				foreach ($gallery_images as $value) {
			    				$image_src = wp_get_attachment_image_src( absint( $value ) );
								$image_src = $image_src ? $image_src[0] : '';
	    				?>
		    					<div class="image-wrapper">
			    					<img src="<?php echo esc_url($image_src); ?>">
					    			<input type="hidden" name="_job_gallery_images[]" value="<?php echo esc_attr($value); ?>">
					    			<span class="remove-image"><i class="fas fa-times"></i></span>
				    			</div>
		    				<?php } ?>
		    			<?php } ?>
		    		</div>
		    		<button id="upload-image-gallery" type="button" class="button"><?php esc_html_e('Add gallery images', 'listdo'); ?></button>
		    	</div>
	    	<?php }
	    	?>
	    <?php } ?>
	    
		<?php
	    
	    add_action('admin_print_scripts', array(__CLASS__, 'admin_scripts'));
  	}

  	public static function admin_scripts() {
        wp_enqueue_script('media-upload');
    }

    public static function admin_fields($fields) {
    	if ( isset($fields['_job_logo']) ) {
			unset($fields['_job_logo']);
		}
		if ( isset($fields['_job_cover_image']) ) {
			unset($fields['_job_cover_image']);
		}
		if ( isset($fields['_job_gallery_images']) ) {
			unset($fields['_job_gallery_images']);
		}
    	return $fields;
    }

	public static function input_hours_fields($key, $field) {
		global $wp_locale, $post, $thepostid;

		$thepostid = $post->ID;
		?>

		<div class="form-field full-form-field">

			<?php
			if ( empty($field['value']) ) {
				$field['value'] = get_post_meta($thepostid, '_job_hours', true);
			}
			get_job_manager_template( 'form-fields/listdo-hours-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}

	public static function input_location_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field location-form-field full-form-field">
			
			<?php
			if ( empty( $field[ 'value' ] ) ) {
				$field[ 'value' ] = get_post_meta( $thepostid, '_job_location', true );
			}
			get_job_manager_template( 'form-fields/listdo-location-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}

	public static function input_menu_prices_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field full-form-field">
			<?php

			if ( empty( $field[ 'value' ] ) ) {
				$field[ 'value' ] = get_post_meta( $thepostid, '_job_menu_prices', true );
			}

			get_job_manager_template( 'form-fields/listdo-menu-prices-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}


	public static function input_datetime_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;

		?>

		<div class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<?php

			if ( empty( $field[ 'value' ] ) ) {
				$field[ 'value' ] = get_post_meta( $thepostid, $key, true );
			}

			get_job_manager_template( 'form-fields/datetime-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}

	public static function input_date_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;

		?>

		<div class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<?php

			if ( empty( $field[ 'value' ] ) ) {
				$field[ 'value' ] = get_post_meta( $thepostid, $key, true );
			}

			get_job_manager_template( 'form-fields/date-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}

	public static function input_regions_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<?php
			$terms = wp_get_post_terms( $thepostid, $field['taxonomy'] );
			if ( !empty($terms) ) {
				$term_ids = array();
				foreach ($terms as $term) {
					$term_ids[] = $term->term_id;
				}
				$field[ 'value' ] = $term_ids;
			}
			get_job_manager_template( 'form-fields/listdo-regions-field.php', array('key' => $key, 'field' => $field) );
			?>
		</div>

		<?php
	}

	public static function input_term_select_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field fieldset-<?php echo esc_attr($key); ?>">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<div class="field">
				<?php
				$terms = wp_get_post_terms( $thepostid, $field['taxonomy'] );
				if ( !empty($terms) ) {
					$term_ids = array();
					foreach ($terms as $term) {
						$field[ 'value' ] = $term->term_id;
					}
				}
				get_job_manager_template( 'form-fields/term-select-field.php', array('key' => $key, 'field' => $field) );
				?>
			</div>
		</div>

		<?php
	}

	public static function input_term_multi_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field fieldset-<?php echo esc_attr($key); ?>">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<div class="field">
				<?php
				$terms = wp_get_post_terms( $thepostid, $field['taxonomy'] );
				if ( !empty($terms) ) {
					$term_ids = array();
					foreach ($terms as $term) {
						$term_ids[] = $term->term_id;
					}
					$field[ 'value' ] = $term_ids;
				}
				get_job_manager_template( 'form-fields/term-multiselect-field.php', array('key' => $key, 'field' => $field) );
				?>
			</div>
		</div>

		<?php
	}

	public static function input_term_checklist_fields($key, $field) {
		global $wp_locale, $post, $thepostid;
		
		$thepostid = $post->ID;
		?>

		<div class="form-field fieldset-<?php echo esc_attr($key); ?>">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<div class="field">
				<?php
				$terms = wp_get_post_terms( $thepostid, $field['taxonomy'] );

				if ( !empty($terms) ) {
					$term_ids = array();
					foreach ($terms as $term) {
						$term_ids[] = $term->term_id;
					}
					$field[ 'value' ] = $term_ids;
				}
				get_job_manager_template( 'form-fields/term-checklist-field.php', array('key' => $key, 'field' => $field) );
				?>
			</div>
		</div>

		<?php
	}

	public static function process_change_region() {
		check_ajax_referer( 'listdo-ajax-nonce', 'security' );
		
		$region_parent = !empty($_POST['region_parent']) ? $_POST['region_parent'] : '';
		$next = !empty($_POST['next']) ? $_POST['next'] : '2';
		$name = !empty($_POST['name']) ? $_POST['name'] : '_job_regions[]';
		$region_text = listdo_get_config('submit_listing_region_'.$next.'_field_label');
		$placeholder = !empty($_POST['placeholder']) ? $_POST['placeholder'] : sprintf(esc_html__('Please select %s', 'listdo'), $region_text);
		
		$html = '';
		if ( $region_parent ) {
			$term_parent = get_term_by('slug', $region_parent, 'job_listing_region');
			if ( !empty($term_parent) ) {
				$regions = get_terms('job_listing_region', array(
	                'orderby' => 'count',
	                'hide_empty' => 0,
	                'parent' => $term_parent->term_id,
	            ));
	            if ( ! empty( $regions ) && ! is_wp_error( $regions ) ) {
	            	ob_start();
	            	
	            	?>
	            	<?php if ( $name == '_job_regions[]' || $name == 'job_regions[]' ) { ?>
		            	<label><?php echo trim($region_text); ?></label>
		            <?php } ?>
	            	<select class="select-field-region select-field-region<?php echo esc_attr($next); ?>" data-next="<?php echo esc_attr($next + 1); ?>" autocomplete="off" name="<?php echo trim($name); ?>" data-placeholder="<?php echo esc_attr($placeholder); ?>">
	            		<option value=""><?php echo esc_attr($placeholder); ?></option>
	            		<?php
		            	foreach ($regions as $region) {
					      	?>
					      	<option value="<?php echo esc_attr($region->slug); ?>"><?php echo esc_html($region->name); ?></option>
					      	<?php  
					    }
					    ?>
					</select>
				    <?php
				    $html = ob_get_clean();
	            }
            }
		}
		if ( empty($html) ) {
			ob_start();
        	?>
        	<?php if ( $name == '_job_regions[]' || $name == 'job_regions[]' ) { ?>
            	<label><?php echo trim($region_text); ?></label>
            <?php } ?>
        	<select class="select-field-region select-field-region<?php echo esc_attr($next); ?>" data-next="<?php echo esc_attr($next + 1); ?>" autocomplete="off" name="<?php echo esc_attr($name); ?>" data-placeholder="<?php echo esc_attr($placeholder); ?>">
        		<option value=""><?php echo esc_attr($placeholder); ?></option>
			</select>
		    <?php
		    $html = ob_get_clean();
		}
		echo trim($html);
		die();
	}

	
	public static function listing_submit( $id, $values ) {
		if ( isset($values[ 'job' ][ 'job_cover_image' ]) && !empty($values[ 'job' ][ 'job_cover_image' ]) ) {
			$job_cover_image =  $values[ 'job' ][ 'job_cover_image' ];
			if ( is_numeric($job_cover_image) ) {
				$attachment_id = $job_cover_image;
			} else {
				$attachment_id = listdo_get_attachment_id_from_url( $job_cover_image );
			}
			if ( empty( $attachment_id ) ) {
				delete_post_thumbnail( $id );
			} else {
				set_post_thumbnail( $id, $attachment_id );
			}
		}

		if ( isset($values[ 'job' ][ 'job_logo' ]) && !empty($values[ 'job' ][ 'job_logo' ]) ) {
			$job_logo =  $values[ 'job' ][ 'job_logo' ];

			if ( is_numeric($job_logo) ) {
				$attachment_id = $job_logo;
			} else {
				$attachment_id = listdo_get_attachment_id_from_url( $job_logo );
			}

			update_post_meta( $id, '_job_logo', $attachment_id );
		}

		if ( isset($values[ 'job' ][ 'job_gallery_images' ]) && !empty($values[ 'job' ][ 'job_gallery_images' ]) ) {
			$job_gallery_images = $values[ 'job' ][ 'job_gallery_images' ];
			$gallery_images = array();

			// we may have a simple string(on image upload) or an array of images, so we need to treat them all
			if ( is_numeric( $job_gallery_images ) ) {
				$attach_id = listdo_get_attachment_id_from_url( $job_gallery_images );
				if ( ! empty( $attach_id ) && is_numeric( $attach_id ) ) {
					$gallery_images[] = $attach_id;
				}
			} elseif ( is_array( $job_gallery_images ) && ! empty( $job_gallery_images ) ) {
				foreach ( $job_gallery_images as $key => $url ) {
					if ( is_numeric( $url ) ) {
						$gallery_images[] = $url;
					} else {
						$attach_id = listdo_get_attachment_id_from_url( $url );
						if ( ! empty( $attach_id ) && is_numeric( $attach_id ) ) {
							$gallery_images[] = $attach_id;
						}
					}
				}
			}
			
			update_post_meta( $id, '_job_gallery_images', $gallery_images );
		}

		self::listing_update_job_data($id, $values);
	}

	public static function listing_update_job_data( $id, $values ) {
		
		if ( isset( $_POST[ 'job_hours' ] ) ) {
			update_post_meta( $id, '_job_hours', stripslashes_deep( $_POST[ 'job_hours' ] ) );
		}

		if ( isset( $_POST[ 'geo_latitude' ] ) ) {
			update_post_meta( $id, 'geolocation_lat', stripslashes_deep( $_POST[ 'geo_latitude' ] ) );
		}

		if ( isset( $_POST[ 'geo_longitude' ] ) ) {
			update_post_meta( $id, 'geolocation_long', stripslashes_deep( $_POST[ 'geo_longitude' ] ) );
		}

		if ( isset( $_POST[ 'job_location_friendly' ] ) ) {
			update_post_meta( $id, '_job_location_friendly', stripslashes_deep( $_POST[ 'job_location_friendly' ] ) );
		}

		if ( isset( $_POST[ '_job_menu_prices' ] ) ) {
			update_post_meta( $id, '_job_menu_prices_data', stripslashes_deep( $_POST[ '_job_menu_prices' ] ) );
		}

	}

	public static function listing_update( $id, $values ) {
		
		// region term
		if ( isset( $_POST[ '_job_regions' ] ) ) {
			if ( is_array( $_POST[ '_job_regions' ] ) ) {
				$terms = $_POST[ '_job_regions' ];
			} else {
				$terms = array($_POST[ '_job_regions' ]);
			}
			wp_set_object_terms( $id, $terms, 'job_listing_region', false );
		}

		if ( isset( $_POST[ '_job_c_category' ] ) ) {
			if ( is_array( $_POST[ '_job_c_category' ] ) ) {
				$terms = $_POST[ '_job_c_category' ];
			} else {
				$terms = array($_POST[ '_job_c_category' ]);
			}
			wp_set_object_terms( $id, $terms, 'job_listing_category', false );
		}

		if ( isset( $_POST[ '_job_c_type' ] ) ) {
			if ( is_array( $_POST[ '_job_c_type' ] ) ) {
				$terms = $_POST[ '_job_c_type' ];
			} else {
				$terms = array($_POST[ '_job_c_type' ]);
			}
			wp_set_object_terms( $id, $terms, 'job_listing_type', false );
		}

		if ( isset( $_POST[ '_job_category' ] ) ) {
			if ( is_array( $_POST[ '_job_category' ] ) ) {
				$terms = $_POST[ '_job_category' ];
			} else {
				$terms = array($_POST[ '_job_category' ]);
			}
			$terms = array_map('intval', $terms);
			wp_set_object_terms( $id, $terms, 'job_listing_category', false );
		}

		if ( isset( $_POST['tax_input'][ 'job_listing_amenity' ] ) ) {
			if ( is_array( $_POST['tax_input'][ 'job_listing_amenity' ] ) ) {
				$terms = $_POST['tax_input'][ 'job_listing_amenity' ];
			} else {
				$terms = array($_POST['tax_input'][ 'job_listing_amenity' ]);
			}
			$terms = array_map('intval', $terms);
			wp_set_object_terms( $id, $terms, 'job_listing_amenity', false );
		}

		if ( class_exists('ApusListdo_Custom_Fields') ) {
			$fields = ApusListdo_Custom_Fields::add_custom_fields(array());
	        if ( !empty($fields) ) {
	        	foreach ($fields as $key => $field) {
	        		if ( $field['type'] == 'repeater' ) {
	        			listdo_save_repeater_row( $id, $key, $field['fields'] );
	        		}
	        	}
	        }
        }
        
		if ( isset($_POST[ '_job_gallery_images' ]) && !empty($_POST[ '_job_gallery_images' ]) ) {
			update_post_meta( $id, '_job_gallery_images', $_POST[ '_job_gallery_images' ] );
		} else {
			delete_post_meta( $id, '_job_gallery_images' );
		}
		if ( isset($_POST[ '_job_logo' ]) && !empty($_POST[ '_job_logo' ]) ) {
			update_post_meta( $id, '_job_logo', $_POST[ '_job_logo' ] );
		} else {
			delete_post_meta( $id, '_job_logo' );
		}

	}

	public static function submit_job_form_save_job_data($job_data) {
		$job_data['comment_status'] = 'open';
		return $job_data;
	}
	
	public static function get_job_data($fields, $job) {
		if ( isset($fields[ 'job' ][ 'job_cover_image' ]['value']) && !empty($fields[ 'job' ][ 'job_cover_image' ]['value']) ) {
			$fields[ 'job' ][ 'job_cover_image' ]['value'] = has_post_thumbnail( $job->ID ) ? get_post_thumbnail_id( $job->ID ) : get_post_meta( $job->ID, '_job_cover_image', true );
		}
		return $fields;
	}
	
	public static function upload_file( $files_to_upload, $allowed_mime_types ) {
		$file_urls = array();

		foreach( $files_to_upload as $key => $file_to_upload ) {
			$uploaded_file = job_manager_upload_file(
				$file_to_upload,
				array(
					'file_key'           => '',
					'allowed_mime_types' => $allowed_mime_types,
				)
			);

			if ( is_wp_error( $uploaded_file ) ) {
				throw new Exception( $uploaded_file->get_error_message() );
			} else {
				$file_urls[$key] = $uploaded_file->url;
			}
		}

		return $file_urls;
	}


	public static function job_manager_dropdown_categories( $args = '' ) {
		$defaults = array(
			'orderby'         => 'id',
			'order'           => 'ASC',
			'show_count'      => 0,
			'hide_empty'      => 1,
			'parent'          => '',
			'child_of'        => 0,
			'exclude'         => '',
			'echo'            => 1,
			'selected'        => 0,
			'hierarchical'    => 0,
			'name'            => 'cat',
			'id'              => '',
			'class'           => 'job-manager-category-dropdown ' . ( is_rtl() ? 'chosen-rtl' : '' ),
			'depth'           => 0,
			'taxonomy'        => 'job_listing_category',
			'value'           => 'id',
			'multiple'        => true,
			'show_option_all' => false,
			'placeholder'     => esc_html__( 'Choose a category&hellip;', 'listdo' ),
			'no_results_text' => esc_html__( 'No results match', 'listdo' ),
			'multiple_text'   => esc_html__( 'Select Some Options', 'listdo' ),
			'meta_query'   => ''
		);

		$r = wp_parse_args( $args, $defaults );

		if ( ! isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
			$r['pad_counts'] = true;
		}

		/** This filter is documented in wp-job-manager.php */
		$r['lang'] = apply_filters( 'wpjm_lang', null );

		// Store in a transient to help sites with many cats.
		$categories_hash = 'jm_cats_' . md5( wp_json_encode( $r ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_' . $r['taxonomy'] ) );
		$categories      = get_transient( $categories_hash );

		if ( empty( $categories ) ) {
			$categories = get_terms(
				array(
					'taxonomy'     => $r['taxonomy'],
					'orderby'      => $r['orderby'],
					'order'        => $r['order'],
					'hide_empty'   => $r['hide_empty'],
					'parent'       => $r['parent'],
					'child_of'     => $r['child_of'],
					'exclude'      => $r['exclude'],
					'hierarchical' => $r['hierarchical'],
					'meta_query' => $r['meta_query'],
				)
			);
			set_transient( $categories_hash, $categories, DAY_IN_SECONDS * 7 );
		}

		$id = $r['id'] ? $r['id'] : $r['name'];

		$output = "<select name='" . esc_attr( $r['name'] ) . "[]' id='" . esc_attr( $id ) . "' class='" . esc_attr( $r['class'] ) . "' " . ( $r['multiple'] ? "multiple='multiple'" : '' ) . " data-placeholder='" . esc_attr( $r['placeholder'] ) . "' data-no_results_text='" . esc_attr( $r['no_results_text'] ) . "' data-multiple_text='" . esc_attr( $r['multiple_text'] ) . "'>\n";

		if ( $r['show_option_all'] ) {
			$output .= '<option value="">' . esc_html( $r['show_option_all'] ) . '</option>';
		}

		if ( ! empty( $categories ) ) {
			include_once JOB_MANAGER_PLUGIN_DIR . '/includes/class-wp-job-manager-category-walker.php';

			$walker = new WP_Job_Manager_Category_Walker();

			if ( $r['hierarchical'] ) {
				$depth = $r['depth'];  // Walk the full depth.
			} else {
				$depth = -1; // Flat.
			}

			$output .= $walker->walk( $categories, $depth, $r );
		}

		$output .= "</select>\n";

		if ( $r['echo'] ) {
			echo trim($output); // WPCS: XSS ok.
		}

		return $output;
	}
}

Listdo_Submittion::init();