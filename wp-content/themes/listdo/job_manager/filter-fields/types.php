<div class="select-types">
	
	<?php

	$tplaceholder = !empty($types_label) ? $types_label : esc_html__( 'Filter by type', 'listdo' );
	$placeholder = !empty($placeholder) ? $placeholder : $tplaceholder;
	

	$selected_type = '';
	//try to see if there is a search_categories (notice the plural form) GET param
	$search_types = isset( $_REQUEST['job_type_select'] ) ? $_REQUEST['job_type_select'] : '';
	
	if ( ! empty( $search_types ) && is_array( $search_types ) ) {
		$search_types = $search_types[0];
	}
	$search_types = sanitize_text_field( stripslashes( $search_types ) );
	if ( ! empty( $search_types ) ) {

		if ( is_numeric( $search_types ) ) {
			$selected_type = intval( $search_types );
		} else {
			$term = get_term_by( 'slug', $search_types, 'job_listing_type' );
			$selected_type = $term->term_id;
		}
	} elseif (  ! empty( $atts['job_types'] ) ) {
		if ( is_array($atts['job_types']) ) {
			$job_type = $atts['job_types'][0];
			if ( is_numeric( $job_type ) ) {
				$selected_type = intval( $job_type );
			} else {
				$term = get_term_by( 'slug', $job_type, 'job_listing_type' );
				$selected_type = $term->term_id;
			}
		} else {
			if ( is_numeric( $atts['job_types'] ) ) {
				$selected_type = intval( $atts['job_types'] );
			} else {
				$term = get_term_by( 'slug', $atts['job_types'], 'job_listing_type' );
				$selected_type = $term->term_id;
			}
		}
	}

	?>
	
	<?php job_manager_dropdown_categories( array( 'taxonomy' => 'job_listing_type', 'hierarchical' => 1, 'show_option_all' => $placeholder, 'placeholder' => $placeholder, 'name' => 'job_type_select', 'orderby' => 'name', 'multiple' => false, 'hide_empty'  => false, 'selected' => $selected_type ) ); ?>

</div>