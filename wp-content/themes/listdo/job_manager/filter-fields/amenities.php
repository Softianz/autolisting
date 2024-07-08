<div class="search_amenity_wrapper">
	<h4 class="title-amenity"><i class="fas fa-plus"></i> <?php esc_html_e('Filter by Features', 'listdo'); ?></h4>
	<div class="amenities-wrap">
		<?php
			if ( empty( $selected_category ) ) {
				//try to see if there is a search_categories (notice the plural form) GET param
				$search_categories = isset( $_REQUEST['search_categories'] ) ? $_REQUEST['search_categories'] : '';
				
				if ( ! empty( $search_categories ) && is_array( $search_categories ) ) {
					$search_categories = $search_categories[0];
				}
				
				$search_categories = sanitize_text_field( stripslashes( $search_categories ) );
				if ( ! empty( $search_categories ) ) {
					if ( is_numeric( $search_categories ) ) {
						$selected_category = intval( $search_categories );
					} else {
						$term = get_term_by( 'slug', $search_categories, 'job_listing_category' );
						$selected_category = $term->term_id;
					}
				} elseif (  ! empty( $categories ) ) {
					if ( is_array($categories) ) {
						$selected_category = intval( $categories[0] );
					} else {
						$selected_category = intval( $categories );
					}
				}
			}
			if ( !empty($selected_category) && ($cat_term = get_term_by('term_id', $selected_category, 'job_listing_category')) ) {
				$args = array(
					'hierarchical' => 1,
					'hide_empty' => false,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'apus_category_parent',
							'value' => '"' . $cat_term->slug . '"',
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
					)
				);
				
				$job_amenities = get_terms( array( 'job_listing_amenity' ), $args );

				if ( !empty( $job_amenities ) ) {
					$selected = '';
					if ( is_tax( 'job_listing_amenity' )  ) {
						global $wp_query;
						$term =	$wp_query->queried_object;
						$selected = $term->slug;
					}
					$rand = rand(100, 9999);
				?>
					<ul class="job_amenities">
						<?php foreach ( $job_amenities as $amenity ) : ?>
							<li>
								<label for="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" class="<?php echo sanitize_title( $amenity->name ); ?>">
									<input type="checkbox" name="filter_job_amenity[]" value="<?php echo trim($amenity->slug); ?>" id="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" <?php echo trim($selected == $amenity->slug ? 'checked="checked"' : ''); ?> /> <?php echo trim($amenity->name); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php }
			} else {
				$args = array(
					'hierarchical' => 1,
					'hide_empty' => false
				);
				
				$job_amenities = get_terms( array( 'job_listing_amenity' ), $args );

				if ( !empty( $job_amenities ) ) {
					$selected = '';
					if ( is_tax( 'job_listing_amenity' )  ) {
						global $wp_query;
						$term =	$wp_query->queried_object;
						$selected = $term->slug;
					}
					$rand = rand(100, 9999);
				?>
					<ul class="job_amenities">
						<?php foreach ( $job_amenities as $amenity ) : ?>
							<li>
								<label for="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" class="<?php echo sanitize_title( $amenity->name ); ?>">
									<input type="checkbox" name="filter_job_amenity[]" value="<?php echo trim($amenity->slug); ?>" id="job_amenity_<?php echo esc_attr($amenity->slug.'-'.$rand); ?>" <?php echo trim($selected == $amenity->slug ? 'checked="checked"' : ''); ?> /> <?php echo trim($amenity->name); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php } else { ?>
			<div class="alert alert-warning"><?php esc_html_e('Please choose category to display filters', 'listdo'); ?></div>
			<?php } ?>
		<?php } ?>
		
	</div>
</div>