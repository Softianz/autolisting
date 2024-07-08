<?php
global $wp_locale, $thepostid;


$selected = array();
if ( isset( $field['value'] ) ) {
	if ( is_array($field['value']) ) {
		foreach ($field['value'] as $slug) {
			if ( is_int( $slug ) ) {
				$selected[] = $slug;
			} elseif ( ($term = get_term_by( 'slug', $slug, $field['taxonomy'] )) ) {
				$selected[] = $term->term_id;
			}
		}
	} elseif ( !is_int( $field['value'] ) && ( $term = get_term_by( 'slug', $field['value'], $field['taxonomy'] ) ) ) {
		$selected[] = $term->term_id;
	} else {
		$selected[] = $field['value'];
	}
} elseif (  ! empty( $field['default'] ) && is_int( $field['default'] ) ) {
	$selected[] = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected[] = $term->term_id;
} else {
	$selected[] = '';
}

$nb_fields = listdo_get_config('submit_listing_category_nb_fields', '1');
?>

<div class="categories-field <?php echo esc_attr( is_admin() ? 'is-admin' : ''); ?>">
	<?php
		$first_categories = get_terms('job_listing_category', array(
	        'orderby' => 'count',
	        'hide_empty' => 0,
	        'parent' => 0,
	    ));
	    $second_category_selected = 0;

	    $category1_text = listdo_get_config('submit_listing_category_1_field_label');
    ?>
	    <div class="field-category field-category1">
	    	<label><?php echo trim($category1_text); ?></label>
		    <select class="select-field-category select-field-category1" data-next="2" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_html__('Please select %s', 'listdo'), $category1_text); ?>">
		    	<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $category1_text); ?></option>
			    <?php
			    if ( ! empty( $first_categories ) && ! is_wp_error( $first_categories ) ) {
				    foreach ($first_categories as $category) {
				    	$selected_attr = '';
				    	if ( isset( $selected ) && in_array( $category->term_id, $selected ) ) {
				    		$selected_attr = 'selected="selected"';
				    		$second_category_selected = $category->term_id;
				    	}
				      	?>
				      	<option value="<?php echo esc_attr($category->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($category->name); ?></option>
				      	<?php  
				    }
			    }
			    ?>
		    </select>
	    </div>
    <?php if ( $nb_fields == '2' ) {
    	$category2_text = listdo_get_config('submit_listing_category_2_field_label');
	?>
	    <div class="field-category field-category2">
	    	<label><?php echo trim($category2_text); ?></label>
	    	<select class="select-field-category select-field-category2" data-next="3" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_attr__('Please select %s', 'listdo'), $category2_text); ?>">
	    		<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $category2_text); ?></option>
	    		<?php
	    		if ( !empty($second_category_selected) ) {
		    		$second_categories = get_terms('job_listing_category', array(
		                'orderby' => 'count',
		                'hide_empty' => 0,
		                'parent' => $second_category_selected,
		            ));
		            if ( ! empty( $second_categories ) && ! is_wp_error( $second_categories ) ) {
		            	foreach ($second_categories as $category) {
					    	$selected_attr = '';
					    	if ( isset( $selected ) && in_array( $category->term_id, $selected ) ) {
					    		$selected_attr = 'selected="selected"';
					    	}
					      	?>
					      	<option value="<?php echo esc_attr($category->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($category->name); ?></option>
					      	<?php  
					    }
		            }
		    	}
	    		?>
	    	</select>
	    </div>
    <?php } ?>
</div>