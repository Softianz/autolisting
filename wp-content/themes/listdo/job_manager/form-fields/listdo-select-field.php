<?php
/**
 * Shows term `select` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/term-select-field.php.
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
} elseif ( is_int( $field['default'] ) ) {
	$selected = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected = $term->term_id;
} else {
	$selected = '';
}

// Select only supports 1 value.
if ( is_array( $selected ) ) {
	$selected = current( $selected );
}

	listdo_job_manager_dropdown_types( array(
		'taxonomy'     => $field['taxonomy'],
		'hierarchical' => 1,
		'name'         => isset( $field['name'] ) ? $field['name'] : $key,
		'orderby'      => 'title',
		'selected'     => $selected,
		'hide_empty'   => false,
		'show_option_all' => isset( $field['placeholder'] ) ? $field['placeholder'] : '-',
		'value'           => 'slug',
		'placeholder'     => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
	) );


if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
