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

$nb_fields = listdo_get_config('submit_listing_region_nb_fields', '1');
?>

<div class="regions-field <?php echo esc_attr( is_admin() ? 'is-admin' : ''); ?>">
	<?php
		$first_regions = get_terms('job_listing_region', array(
	        'orderby' => 'count',
	        'hide_empty' => 0,
	        'parent' => 0,
	        'orderby' => 'title',
    		'order'   => 'ASC',
	    ));
	    $second_region_selected = 0;
	    $third_region_selected = 0;
	    $fourth_region_selected = 0;

	    $region1_text = listdo_get_config('submit_listing_region_1_field_label');
    ?>
	    <div class="field-region field-region1">
	    	<label><?php echo trim($region1_text); ?></label>
		    <select class="select-field-region select-field-region1" data-next="2" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_attr__('Please select %s', 'listdo'), $region1_text); ?>">
		    	<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $region1_text); ?></option>
			    <?php
			    if ( ! empty( $first_regions ) && ! is_wp_error( $first_regions ) ) {
				    foreach ($first_regions as $region) {
				    	$selected_attr = '';
				    	if ( isset( $selected ) && in_array( $region->term_id, $selected ) ) {
				    		$selected_attr = 'selected="selected"';
				    		$second_region_selected = $region->term_id;
				    	}
				      	?>
				      	<option value="<?php echo esc_attr($region->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
				      	<?php  
				    }
			    }
			    ?>
		    </select>
	    </div>
    <?php if ( $nb_fields == '2' || $nb_fields == '3' || $nb_fields == '4' ) {
    	$region2_text = listdo_get_config('submit_listing_region_2_field_label');
	?>
	    <div class="field-region field-region2">
	    	<label><?php echo trim($region2_text); ?></label>
	    	<select class="select-field-region select-field-region2" data-next="3" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_attr__('Please select %s', 'listdo'), $region2_text); ?>">
	    		<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $region2_text); ?></option>
	    		<?php
	    		if ( !empty($second_region_selected) ) {
		    		$second_regions = get_terms('job_listing_region', array(
		                'orderby' => 'count',
		                'hide_empty' => 0,
		                'parent' => $second_region_selected,
		                'orderby' => 'title',
    					'order'   => 'ASC',
		            ));
		            if ( ! empty( $second_regions ) && ! is_wp_error( $second_regions ) ) {
		            	foreach ($second_regions as $region) {
					    	$selected_attr = '';
					    	if ( isset( $selected ) && in_array( $region->term_id, $selected ) ) {
					    		$selected_attr = 'selected="selected"';
					    		$third_region_selected = $region->term_id;
					    	}
					      	?>
					      	<option value="<?php echo esc_attr($region->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
					      	<?php  
					    }
		            }
		    	}
	    		?>
	    	</select>
	    </div>
    <?php } ?>
    <?php if ( $nb_fields == '3' || $nb_fields == '4' ) {
    	$region3_text = listdo_get_config('submit_listing_region_3_field_label');
	?>
    	<div class="field-region field-region3">
    		<label><?php echo trim($region3_text); ?></label>
	    	<select class="select-field-region select-field-region3" data-next="4" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_attr__('Please select %s', 'listdo'), $region3_text); ?>">
	    		<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $region3_text); ?></option>
	    		<?php
	    		if ( !empty($third_region_selected) ) {
		    		$third_regions = get_terms('job_listing_region', array(
		                'orderby' => 'count',
		                'hide_empty' => 0,
		                'parent' => $third_region_selected,
		                'orderby' => 'title',
    					'order'   => 'ASC',
		            ));
		            if ( ! empty( $third_regions ) && ! is_wp_error( $third_regions ) ) {
		            	foreach ($third_regions as $region) {
					    	$selected_attr = '';
					    	if ( isset( $selected ) && in_array( $region->term_id, $selected ) ) {
					    		$selected_attr = 'selected="selected"';
					    		$fourth_region_selected = $region->term_id;
					    	}
					      	?>
					      	<option value="<?php echo esc_attr($region->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
					      	<?php  
					    }
		            }
		    	}
	    		?>
	    	</select>
	    </div>
    <?php } ?>
    <?php if ( $nb_fields == '4' ) {
    	$region4_text = listdo_get_config('submit_listing_region_4_field_label');
	?>
	    <div class="field-region field-region4">
	    	<label><?php echo trim($region4_text); ?></label>
	    	<select class="select-field-region select-field-region4" data-next="5" autocomplete="off" name="<?php echo isset( $field['name'] ) ? $field['name'] : $key; ?>[]" data-placeholder="<?php echo sprintf(esc_attr__('Please select %s', 'listdo'), $region4_text); ?>">
	    		<option value=""><?php echo sprintf(esc_html__('Please select %s', 'listdo'), $region4_text); ?></option>
	    		<?php
	    		if ( !empty($fourth_region_selected) ) {
		    		$fourth_regions = get_terms('job_listing_region', array(
		                'orderby' => 'count',
		                'hide_empty' => 0,
		                'parent' => $fourth_region_selected,
		                'orderby' => 'title',
    					'order'   => 'ASC',
		            ));
		            if ( ! empty( $fourth_regions ) && ! is_wp_error( $fourth_regions ) ) {
		            	foreach ($fourth_regions as $region) {
					    	$selected_attr = '';
					    	if ( isset( $selected ) && in_array( $region->term_id, $selected ) ) {
					    		$selected_attr = 'selected="selected"';
					    	}
					      	?>
					      	<option value="<?php echo esc_attr($region->slug); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
					      	<?php  
					    }
		            }
		    	}
	    		?>
	    	</select>
	    </div>
	<?php } ?>
</div>