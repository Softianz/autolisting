<?php
/**
 * Shows `checkbox` form fields in a list from a list on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/term-checklist-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php
	$display_warning = false;
	if ( empty( $field['default'] ) ) {
		$field['default'] = '';
	}
	$selected = isset( $field['value'] ) ? $field['value'] : $field['default'];
	if ( empty($selected) ) {
		$selected = array();
	} else {
		if ( !is_array($selected) ) {
			$selected = array($selected);
		}
	}
	$args = array(
		'taxonomy'     => $field['taxonomy'],
		'hierarchical' => 1,
		'orderby'      => 'title',
		'hide_empty'   => false
	);
	if ( $field['taxonomy'] == 'job_listing_amenity' ) {
		$terms = array();
		if ( !empty($field['category_parent']) ) {
			if ( is_array($field['category_parent']) ) {
				foreach ($field['category_parent'] as $term_id) {
					$term = get_term_by( 'term_id', $term_id, 'job_listing_category');
					if ( $term ) {
						$terms[] = $term->slug;
					}
				}
			} else {
				$term = get_term_by( 'term_id', $field['category_parent'], 'job_listing_category');
				if ( $term ) {
					$terms[] = $term->slug;
				}
			}
		} else {
			global $thepostid;
			if ( $thepostid ) {
				$job_id = $thepostid;
			} else {
				$job_id = ! empty( $_REQUEST['job_id'] ) ? absint( $_REQUEST['job_id'] ) : 0;
			}
			$term_list = wp_get_post_terms($job_id, 'job_listing_category');
			if ( $term_list ) {
				foreach ($term_list as $term) {
					$terms[] = $term->slug;
				}
			}
		}
		if ( !empty($terms) ) {
			if ( count($terms) == 1 ) {
				$meta_query = array(
					'relation' => 'OR',
					array(
						'key' => 'apus_category_parent',
						'value' => '"' . $terms[0] . '"',
						'compare' => 'LIKE',
					),
					array(
						'key' => 'apus_category_parent',
						'value' => '',
					),
					array(
						'key' => 'apus_category_parent',
						'compare' => 'NOT EXISTS',
					)
				);
			} else {
				$meta_query = array('relation' => 'OR');
				foreach ($terms as $slug) {
					$meta_query[] = array(
						'relation' => 'OR',
						array(
							'key' => 'apus_category_parent',
							'value' => '"' . $slug . '"',
							'compare' => 'LIKE',
						),
						array(
							'key' => 'apus_category_parent',
							'value' => '',
						),
						array(
							'key' => 'apus_category_parent',
							'compare' => 'NOT EXISTS',
						)
					);
				}
			}
			$args['meta_query'] = $meta_query;
		} else {
			$display_warning = true;
		}
	}
	if ( !$display_warning ) {
		$categories_hash = 'jm_cats_' . md5( wp_json_encode( $args ) . WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_' . $args['taxonomy'] ) );
		$categories      = get_transient( $categories_hash );

		if ( empty( $categories ) ) {
			$categories = get_terms($args);
		}
		set_transient( $categories_hash, $categories, DAY_IN_SECONDS * 7 );
		?>
		<ul class="job-manager-term-checklist job-manager-term-checklist-<?php echo esc_attr( $key ); ?>">
			<?php
			if ( $categories ) {
				foreach ($categories as $term) {
					$checked = '';
					if ( is_array($selected) ) {
						if ( in_array($term->term_id, $selected) ) {
							$checked = 'checked="checked';
						}
					} else {
						if ( $term->term_id == $selected ) {
							$checked = 'checked="checked';
						}
					}
			?>
			<li>
				<div class="checkbox-wrapper">
					<input
						name="tax_input[<?php echo esc_attr($field['taxonomy']); ?>][]"
						type="checkbox"
						id="<?php echo esc_attr( 'term-checklist-' . $term->term_id ) ?>"
						value="<?php echo esc_attr( $term->term_id ) ?>"
						<?php echo trim($checked); ?>
					>
					<label for="<?php echo esc_attr( 'term-checklist-' . $term->term_id ) ?>"> <?php echo esc_attr( $term->name ) ?></label>
				</div>
			</li>
			<?php
				}
			}
			?>
		</ul>
		<?php
	} else {
		?>
		
			<div class="alert alert-warning"><?php esc_html_e('Please choose category to display available features', 'listdo'); ?></div>
		
		<?php
	}
	?>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
