<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'WP_Job_Manager_Writepanels' ) ) {
	include( JOB_MANAGER_PLUGIN_DIR . '/includes/admin/class-wp-job-manager-writepanels.php' );
}


function listdo_repeater_rows_html( $group_name, $fields, $data ) {
	?>
	<div class="wc-job-manager-repeater-rows">
		
		<div class="repeater-fields-rows">
			<?php
				if ( $data ) {
					foreach ( $data as $item ) {
						$i = rand(10,1000);
						echo '<div class="repeater-field row">';
						
						foreach ( $fields as $key => $field ) {
							echo '<div class="col-xs-6">';
							$type           = ! empty( $field['type'] ) ? $field['type'] : 'text';
							$field['value'] = isset( $item[ $key ] ) ? $item[ $key ] : '';

							if ( method_exists( 'WP_Job_Manager_Writepanels', 'input_' . $type ) ) {
								call_user_func( array( 'WP_Job_Manager_Writepanels', 'input_' . $type ), $key.$i, $field );
							} else {
								do_action( 'job_manager_input_' . $type, $key.$i, $field );
							}
							echo '</div>';
						}
						echo '<a href="" class="delete-repeat-row">'.esc_html__('Delete this row', 'listdo').'</a>';
						echo '</div>';
					}
				}
			?>
		</div>

		<div class="submit">
			<input type="submit" class="button repeate_field_add_row" value="<?php printf(esc_attr__( 'Add %s', 'listdo' ), $group_name ); ?>" data-row="<?php
				ob_start();
				echo '<div class="repeater-field row">';

				foreach ( $fields as $key => $field ) {
					echo '<div class="col-xs-6">';
					$type           = ! empty( $field['type'] ) ? $field['type'] : 'text';
					$field['value'] = '';

					if ( method_exists( 'WP_Job_Manager_Writepanels', 'input_' . $type ) ) {
						call_user_func( array( 'WP_Job_Manager_Writepanels', 'input_' . $type ), $key, $field );
					} else {
						do_action( 'job_manager_input_' . $type, $key, $field );
					}
					echo '</div>';
				}
				echo '<a href="" class="delete-repeat-row">'.esc_html__('Delete this row', 'listdo').'</a>';
				echo '</div>';
				echo esc_attr( ob_get_clean() );
			?>" />
		</div>
	</div>
	<?php
}

/**
 * Save repeater rows
 * @since 1.11.3
 */
function listdo_save_repeater_row( $post_id, $meta_key, $fields ) {
	$items            = array();
	$first_field      = current( $fields );
	$first_field_name = str_replace( '[]', '', $first_field['name'] );

	if ( ! empty( $_POST[ $first_field_name ] ) && is_array( $_POST[ $first_field_name ] ) ) {
		$keys = array_keys( $_POST[ $first_field_name ] );
		foreach ( $keys as $posted_key ) {
			$item = array();
			foreach ( $fields as $key => $field ) {
				$input_name = str_replace( '[]', '', $field['name'] );
				$type       = ! empty( $field['type'] ) ? $field['type'] : 'text';

				switch ( $type ) {
					case 'textarea' :
						$item[ $key ] = wp_kses_post( stripslashes( $_POST[ $input_name ][ $posted_key ] ) );
					break;
					default :
						if ( is_array( $_POST[ $input_name ][ $posted_key ] ) ) {
							$item[ $key ] = array_filter( array_map( 'sanitize_text_field', array_map( 'stripslashes', $_POST[ $input_name ][ $posted_key ] ) ) );
						} else {
							$item[ $key ] = sanitize_text_field( stripslashes( $_POST[ $input_name ][ $posted_key ] ) );
						}
					break;
				}
				if ( empty( $item[ $key ] ) && ! empty( $field['required'] ) ) {
					continue 2;
				}
			}
			$items[] = $item;
		}
	}

	update_post_meta( $post_id, $meta_key, $items );
}



// front end
function listdo_get_repeater_field( $field_prefix, $field ) {
	$fields = $field['fields'];
	
	$items       = array();
	$field_keys  = array_keys( $fields );
	if ( ! empty( $_POST[ 'repeater-row-' . $field_prefix ] ) && is_array( $_POST[ 'repeater-row-' . $field_prefix ] ) ) {
		$indexes = array_map( 'absint', $_POST[ 'repeater-row-' . $field_prefix ] );
		
		foreach ( $indexes as $index ) {
			$item = array();
			foreach ( $fields as $key => $field ) {
				$field_name = $field_prefix . '_' . $key . '_' . $index;

				switch ( $field['type'] ) {
					case 'textarea' :
						$item[ $key ] = wp_kses_post( stripslashes( $_POST[ $field_name ] ) );
					break;
					case 'file' :
						
						$file = listdo_upload_file( $field_name, $field );

						if ( ! $file ) {
							$file_data = isset( $_POST[ 'current_' . $field_name ] ) ? $_POST[ 'current_' . $field_name ] : '';

							$file = sanitize_text_field( stripslashes( $file_data ));
						} elseif ( is_array( $file ) ) {
							$file = array_filter( array_merge( $file, (array) sanitize_text_field( stripslashes( isset( $_POST[ 'current_' . $field_name ] ) ? $_POST[ 'current_' . $field_name ] : '' ) ) ) );
						}

						$item[ $key ] = $file;
					break;
					default :
						if ( is_array( $_POST[ $field_name ] ) ) {
							$item[ $key ] = array_filter( array_map( 'sanitize_text_field', array_map( 'stripslashes', $_POST[ $field_name ] ) ) );
						} else {
							$item[ $key ] = sanitize_text_field( stripslashes( $_POST[ $field_name ] ) );
						}
					break;
				}
				if ( empty( $item[ $key ] ) && ! empty( $field['required'] ) ) {
					continue 2;
				}
			}
			$items[] = $item;
		}
	}
	
	return $items;
}

function listdo_upload_file( $field_key, $field ) {
	if ( isset( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ]['name'] ) ) {
		if ( ! empty( $field['allowed_mime_types'] ) ) {
			$allowed_mime_types = $field['allowed_mime_types'];
		} else {
			$allowed_mime_types = job_manager_get_allowed_mime_types();
		}

		$file_urls       = array();
		$files_to_upload = job_manager_prepare_uploaded_files( $_FILES[ $field_key ] );

		foreach ( $files_to_upload as $file_to_upload ) {
			$uploaded_file = job_manager_upload_file(
				$file_to_upload,
				array(
					'file_key'           => $field_key,
					'allowed_mime_types' => $allowed_mime_types,
				)
			);

			if ( is_wp_error( $uploaded_file ) ) {
				throw new Exception( $uploaded_file->get_error_message() );
			} else {
				$file_urls[] = $uploaded_file->url;
			}
		}

		if ( ! empty( $field['multiple'] ) ) {
			return $file_urls;
		} else {
			return current( $file_urls );
		}
	}
}

function listdo_get_posted_repeater_field() {
	return 'listdo_get_repeater_field';
}
add_filter( 'job_manager_get_posted_repeater_field', 'listdo_get_posted_repeater_field' );