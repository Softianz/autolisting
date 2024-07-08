<?php
/**
 * Shows term `select` (multiple) form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/term-multiselect-field.php.
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

// Get selected value.
if ( isset( $field['value'] ) ) {
	$selected = $field['value'];
} elseif (  ! empty( $field['default'] ) && is_int( $field['default'] ) ) {
	$selected = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected = $term->term_id;
} else {
	$selected = '';
}

wp_enqueue_script( 'wp-job-manager-term-multiselect' );

$display_warning = false;
$args = array(
	'taxonomy'     => $field['taxonomy'],
	'hierarchical' => 1,
	'name'         => isset( $field['name'] ) ? $field['name'] : $key,
	'orderby'      => 'title',
	'selected'     => $selected,
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
	if ( isset( $field['placeholder'] ) && ! empty( $field['placeholder'] ) ) $args['placeholder'] = $field['placeholder'];
	Listdo_Submittion::job_manager_dropdown_categories( apply_filters( 'job_manager_term_multiselect_field_args', $args ) );
} else {
	?>
	<div class="alert alert-warning"><?php esc_html_e('Please choose category to display available features', 'listdo'); ?></div>
	<?php
}
if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
