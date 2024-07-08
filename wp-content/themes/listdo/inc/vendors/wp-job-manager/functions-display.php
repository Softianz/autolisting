<?php

function listdo_display_listing_logo_image( $post = null ) {
	if ( empty($post) ) {
		global $post;
	}

	$size = 'thumbnail';
	$attach_id = get_post_meta($post->ID, '_job_logo', true);
	if ( !empty($attach_id) ) {
		?>
		<div class="listing-logo">
			<div class="inner">
				<?php echo trim(listdo_get_attachment_thumbnail($attach_id, $size)); ?>
			</div>
		</div>
		<?php
	}
}

function listdo_display_listing_cover_image( $size = 'thumbnail', $link = true, $post = null ) {
	if ( empty($post) ) {
		global $post;
	}
	$post_id = $post->ID;
	if ( has_post_thumbnail( $post_id ) ) {
		$attach_id = esc_sql( get_post_thumbnail_id( $post_id ) );
		if ($link) { ?>
			<a class="listing-image-inner" href="<?php the_job_permalink($post); ?>">
		<?php }
		echo trim(listdo_get_attachment_thumbnail($attach_id, $size));
		if ($link) { ?>
			</a>
		<?php }
	}
}

function listdo_display_listing_claim_status($post) {
	if ( listdo_get_config('claim_enable', true) ) {
		$listing_is_claimed = get_post_meta( $post->ID, '_claimed', true );
		if ( $listing_is_claimed ) {
			echo '<span class="listing-claimed-icon" data-toggle="tooltip" title="'.esc_attr__('Claimed', 'listdo').'"><i class="ti-check"></i></span>';
		}
	}
}

function listdo_display_listing_location($post) {
	$location = get_post_meta($post->ID, '_job_location', true);
	$location_friendly = get_post_meta($post->ID, '_job_location_friendly', true);
	if ( empty($location_friendly) ) {
		$location_friendly = $location;
	}
	if ( $location_friendly ) {
		if ( empty($location) ) {
			$location = $location_friendly;
		}
		?>
		<div class="listing-location listing-address">
			<a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank">
				<i class="flaticon-placeholder"></i><?php echo esc_html($location_friendly); ?>
			</a>
		</div>
		<?php
	}
}

function listdo_display_listing_phone($post) {
	$phone = get_post_meta($post->ID, '_job_phone', true);
	if ( $phone ) {
		$show_full = listdo_get_config('listing_show_full_phone', false);
		$hide_phone = $show_full ? false : true;
		$hide_phone = apply_filters('listdo_phone_hide_number', $hide_phone );
		$add_class = '';
        if ( $hide_phone ) {
            $add_class = 'phone-hide';
        }
		?>
		<div class="phone-wrapper listing-phone <?php echo esc_attr($add_class); ?>">
			<a class="phone" href="tel:<?php echo esc_attr($phone); ?>">
				<i class="flaticon-call"></i><?php echo esc_html($phone); ?>
			</a>
			<?php if ( $hide_phone ) {
                $dispnum = substr($phone, 0, (strlen($phone)-6) ) . str_repeat("*", 3);
            ?>
                <span class="phone-show" onclick="this.parentNode.classList.add('show');"><i class="flaticon-call"></i> <?php echo trim($dispnum); ?> <span class="bg-theme"><?php esc_html_e('show', 'listdo'); ?></span></span>
            <?php } ?>
		</div>
		<?php
	}
}

function listdo_display_listing_website($post) {
	$website = get_post_meta($post->ID, '_job_website', true);
	if ( $website ) {
		?>
		<div class="listing-website">
			<a href="<?php echo esc_aurl($website); ?>" target="_blank">
				<i class="ti-world"></i><?php echo esc_html($website); ?>
			</a>
		</div>
		<?php
	}
}

function listdo_display_time_status($post) {
	if ( listdo_get_config('listing_show_hour_status', true) ) {
		$status = listdo_get_current_time_status( $post->ID );

		if ( $status ) { ?>
			<div class="listing-time opening">
				<?php esc_html_e( 'Open', 'listdo' ); ?>
			</div>
		<?php } else { ?>
			<div class="listing-time closed">
				<?php esc_html_e( 'Closed', 'listdo' ); ?>
			</div>
		<?php }
	}
}

function listdo_display_listing_first_category($post) {
	$terms = get_the_terms( $post->ID, 'job_listing_category' );
	$firstTermHTML  = '';
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		$firstTerm = $terms[0];
		if ( ! $firstTerm == null ) {
			$icon = listdo_listing_category_icon($firstTerm);
			$color_value = get_term_meta( $firstTerm->term_id, 'apus_color', true );
			$firstTermHTML .= '<a href="'.get_term_link($firstTerm).'" style="color:'.$color_value.'">'.$icon.$firstTerm->name.'</a>';
			if ( count($terms) > 1 ) {
				$firstTermHTML .= '<div class="more-categories"><span class="count-cat">+'.(count($terms) - 1).'</span>';
				$firstTermHTML .= '<div class="more-categories-inner">';
				foreach ($terms as $key => $term) {
					if ( $key != 0 ) {
						$icon = listdo_listing_category_icon($term);
						$firstTermHTML .= '<a href="'.get_term_link($term).'">'.$icon.'<span class="name-category">'.$term->name.'</span>'.'</a>';
					}
				}
				$firstTermHTML .= '</div>';
				$firstTermHTML .= '</div>';
			}
		}
	}
	if ( $firstTermHTML ) {
		?>
		<div class="listing-category">
			<?php echo trim($firstTermHTML); ?>
		</div>
		<?php
	}
}

function listdo_display_listing_all_categories($post) {
	$terms = get_the_terms( $post->ID, 'job_listing_category' );
	$termHTML  = '';
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		$i=1; foreach ($terms as $term) {
			$icon = listdo_listing_category_icon($term);
			$termHTML .= '<a href="'.get_term_link($term).'">'.$icon.$term->name.'</a>'.($i < count($terms) ? ', ' : '');
		$i++;}
	}
	if ( $termHTML ) {
		?>
		<div class="listing-category">
			<?php echo trim($termHTML); ?>
		</div>
		<?php
	}
}
function listdo_display_listing_all_types($post) {
	$terms = get_the_terms( $post->ID, 'job_listing_type' );
	$termHTML  = '';
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		$i=1; foreach ($terms as $term) {
			$icon = listdo_listing_category_icon($term);
			$termHTML .= '<a href="'.get_term_link($term).'">'.$icon.$term->name.'</a>'.($i < count($terms) ? ', ' : '');
		$i++;}
	}
	if ( $termHTML ) {
		?>
		<div class="listing-category">
			<?php echo trim($termHTML); ?>
		</div>
		<?php
	}
}

function listdo_display_listing_review_v1($post) {
	if ( listdo_listing_review_rating_enable() ) {
		$rating = get_post_meta( $post->ID, '_average_rating', true );
		$rating = !empty($rating) ? $rating : 0;
		$nb_rating = listdo_get_total_reviews( $post->ID );
		if($nb_rating > 0){
			listdo_print_review($rating, 'list', $nb_rating);
		}
	}
}

function listdo_display_listing_review($post) {
	if ( listdo_listing_review_rating_enable() ) {
		$total_rating = get_post_meta( $post->ID, '_average_rating', true );
		if ( $total_rating > 0 ) {
			listdo_display_listing_review_html($total_rating);
		}
	}
}

function listdo_display_listing_review_html($total_rating) {
	$rating_mode = listdo_get_config('listing_review_mode', 10);
	?>
	<div class="wrapper-star-average-rating">
		<div class="star-average-rating">
			<div class="star-average-inner" style="width: <?php echo round(($total_rating/$rating_mode * 100), 2).'%'; ?>"></div>
		</div>
		<div class="number">(<?php comments_number( esc_html__('0 Reviews', 'listdo'), esc_html__('1 Review', 'listdo'), esc_html__('% Reviews', 'listdo') ); ?>)</div>
		
	</div>
	<?php
}

function listdo_display_price_range($post) {
	$price_from = get_post_meta($post->ID, '_job_price_from', true);
	$price_to = get_post_meta($post->ID, '_job_price_to', true);
	if ( $price_from || $price_to ) {
		?>
		<span class="price-range">
			<?php listdo_listing_display_price($price_from); ?>
			<?php if ( $price_to ) { ?>
				- <?php listdo_listing_display_price($price_to); ?>
			<?php } ?>
		</span>
		<?php
	}
}

function listdo_display_listing_card_bookmark_btn($post) {
	?>
	<div class="top-imformation">
		<?php Listdo_Bookmark::display_bookmark_btn($post); ?>
	</div>
	<?php
}

function listdo_listing_display_featured_label($post) {
	$featured = get_post_meta($post->ID, '_featured', true);
	if ( $featured ) {
		?>
		<div class="listing-featured-label">
			<?php esc_html_e('Featured', 'listdo'); ?>
		</div>
		<?php
	}
}

function listdo_listing_display_price($price, $echo = true) {
	$price = listdo_price_format_number( $price );
	
	$symbol = listdo_get_config('listing_currency_symbol', '$');
	$show_after = listdo_get_config('listing_currency_symbol_after_amount', 0);
	
	$currency_symbol = ! empty( $symbol ) ? $symbol : '$';
	$currency_show_symbol_after = $show_after ? true : false;
	$price_html = $price;
	if ( ! empty( $currency_symbol ) ) {
		if ( $currency_show_symbol_after ) {
			$price_html = sprintf('<span class="price">%s</span><span class="currency-symbol">%s</span>', $price, $currency_symbol);
		} else {
			$price_html = sprintf('<span class="currency-symbol">%s</span><span class="price">%s</span>', $currency_symbol, $price);
		}
	}
	if ( $echo ) {
		echo trim($price_html);
	} else {
		return $price_html;
	}
}

function listdo_listing_display_price_range($post) {
	$price_range = get_post_meta($post->ID, '_job_price_range', true);
	$price_range_labels = listdo_job_manager_price_range_icons();
	if ( $price_range && isset($price_range_labels[$price_range])) {
		$labels = $price_range_labels[$price_range];
		?>
		<div class="listing-price-range" data-placement="top" data-toggle="tooltip" title="<?php echo esc_attr($labels['label']); ?>">
			<?php echo esc_attr($labels['icon']); ?>
		</div>
		<?php
	}
}

function listdo_display_categories_icon( $terms ) {
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		foreach ($terms as $term) {
			$icon = listdo_listing_category_icon($term);
			
			if ( !empty($icon) ) {
			?>
				<a href="<?php echo esc_url( get_term_link($term->term_id, 'job_listing_category') ); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr( $term->name); ?>">
					<?php echo trim($icon); ?>
				</a>
			<?php
			}
		}
	}
}

function listdo_listing_category_icon( $term ) {
	$html = '';

	$icon_type_value = get_term_meta( $term->term_id, 'apus_icon_type', true );
	$icon_font_value = get_term_meta( $term->term_id, 'apus_icon_font', true );
	$icon_image_value = get_term_meta( $term->term_id, 'apus_icon_image', true );
	if ( $icon_type_value == 'font' && !empty($icon_font_value) ) {
		$html = '<i class="'.esc_attr($icon_font_value).'"></i>';
	} elseif ( $icon_type_value == 'image' && !empty($icon_image_value) ) {
		$image_url = wp_get_attachment_image_src($icon_image_value, 'full');
		if ( !empty($image_url[0]) ) {
			$html = '<img src="'.esc_url($image_url[0]).'" alt="'.esc_attr__( 'icon', 'listdo' ).'" />';
		}
	}
	$color_value = get_term_meta( $term->term_id, 'apus_color', true );
	$style = '';
	if ( !empty($color_value) ) {
		$style = ' style="background: '.$color_value.';"';
	}
	if($html){
		$html = '<span class="icon-cate"'.$style.'>'.$html.'</span>';
	}
	return $html;
}

function listdo_job_manager_price_range_label($labels) {
	$labels = listdo_job_manager_price_range_icons();
	$return = array( 'notsay' => esc_html__('Prefer not to say', 'listdo') );
	foreach ($labels as $key => $label) {
		$return[$key] = $label['icon'] .' - '.$label['label'];
	}
	return $return;
}

// price range label
add_filter( 'apus_listdo_price_ranges', 'listdo_job_manager_price_range_label' );


function listdo_listing_title($post) {
	?>
	<h1 class="entry-title" itemprop="name">
		<?php echo get_the_title($post); ?>
	</h1>
	<?php
}

function listdo_listing_claimed_status($post) {
	if ( listdo_get_config('claim_enable', true) ) {
		$listing_is_claimed = get_post_meta( $post->ID, '_claimed', true );
		if ( $listing_is_claimed ) {
			echo '<span class="listing-claimed-icon" data-toggle="tooltip" title="'.esc_attr__('Claimed', 'listdo').'"><i class="ti-check"></i></span>';
		}
	}
}

function listdo_listing_tagline($post) {
	$job_tagline = get_post_meta( $post->ID, '_job_tagline', true );
	if ( $job_tagline ) {
		echo '<div class="listing-tagline">'.$job_tagline.'</div>';
	}
}

function listdo_listing_review_btn($post) {
	if ( listdo_listing_review_enable($post->ID) ) {
        echo '<div class="listing-review-btn"><a class="listing-reviews" href="#listing-reviews"><i class="flaticon-comment-1"></i><span class="text"> '.esc_html__('Write a review ', 'listdo').'</span></a></div>';
	}
}

function listdo_listing_directions($post) {
	global $post;
	$location = get_the_job_location( $post );
	if ( $location ) {
	    echo '<div class="listing-review-btn"><a class="listing-direction" 
	    href="'.'http://maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false'.'" target="_blank" ><i class="flaticon-compass"></i><span class="text"> '.esc_html__(' Get Directions', 'listdo').'</span></a></div>';
	}
}

function listdo_listing_call($post) {
	global $post;
	$phone = get_post_meta( get_the_ID(), '_job_phone', true);
	if ( ! empty( $phone ) ) :	
    	echo '<div class="listing-review-btn"><a class="listing-call" '.' href="tel:'.trim($phone).'" itemprop="telephone" '.'  ><i class="flaticon-phone-call-1"></i><span class="text"> '.esc_html__(' Call Now', 'listdo').'</span></a></div>';
	endif;
}

function listdo_listing_website($post) {
	global $post;
	$website = get_post_meta( get_the_ID(), '_job_website', true);
	if(!empty($website)):
    	echo '<div class="listing-review-btn"><a class="listing-website" href="'.esc_url( $website ).'"><i class="flaticon-unlink"></i><span class="text"> '.esc_html__('Website', 'listdo').'</span></a></div>';
	endif;
}
// end hook bottom detail


function listdo_listing_single_socials_share($post) {
	if ( listdo_get_config('show_listing_social_share', true) ) { ?>
		<div class="sharing-popup">
			<a href="javascript:void(0);" class="share-popup" title="<?php esc_attr_e('Social Share', 'listdo'); ?>">
				<i class="flaticon-share"></i><span class="text"><?php esc_html_e('Share', 'listdo'); ?></span>
			</a>
			<?php get_template_part( 'template-parts/modal-sharebox' ); ?>
		</div>
	<?php }
}

function listdo_listing_single_address($post) {
	$location = get_post_meta( $post->ID, '_job_location', true );
	if ( $location ) {
		?>
		<div class="listing-address">
			<h4><?php esc_html_e('Address', 'listdo'); ?></h4>
			<div class="listing-item-inner">
				<?php echo esc_html($location); ?>
			</div>
		</div>
		<?php
	}
}

function listdo_listing_single_startdate($post) {
	$start_date = get_post_meta( $post->ID, '_job_start_date', true );
	if ( $start_date ) {
		$start_date = strtotime($start_date);
		?>
		<div class="listing-starttime">
			<h4><?php esc_html_e('Start Time', 'listdo'); ?></h4>
			<div class="listing-item-inner">
				<div class="time"><?php echo date(get_option('time_format'), $start_date); ?></div>
				<div class="date"><?php echo date(get_option('date_format'), $start_date); ?></div>
			</div>
		</div>
		<?php
	}
}

function listdo_listing_single_finishdate($post) {
	$finish_date = get_post_meta( $post->ID, '_job_finish_date', true );
	if ( $finish_date ) {
		$finish_date = strtotime($finish_date);
		?>
		<div class="listing-finishtime">
			<h4><?php esc_html_e('Finish Time', 'listdo'); ?></h4>
			<div class="listing-item-inner">
				<div class="time"><?php echo date(get_option('time_format'), $finish_date); ?></div>
				<div class="date"><?php echo date(get_option('date_format'), $finish_date); ?></div>
			</div>
		</div>
		<?php
	}
}

function listdo_listing_posted_by($post) {
	?>
	<div class="author"><?php echo esc_html__('Posted By: ','listdo'); the_author_posts_link(); ?></div>
	<?php
}

function listdo_listing_categories($post) {
	ob_start();
	$terms = get_the_terms( $post->ID, 'job_listing_category' );
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		$i = 0;
		foreach ($terms as $term) {
			?>
				<?php if($i != 0) echo ', ';?>
				<a href="<?php echo esc_url( get_term_link($term->term_id, 'job_listing_category') ); ?>" title="<?php echo esc_attr( $term->name); ?>">
					<?php echo esc_attr( $term->name); ?>
				</a>
			<?php
			$i++;
		}
	}
	$term_html = ob_get_clean();
	?>
	<div class="categories"><?php echo trim($term_html); ?></div>
	<?php
}

function listdo_listing_video($post) {
	$video       = get_post_meta($post->ID, '_job_video', true);
	$video_embed = false;
	$filetype    = wp_check_filetype( $video );

	if ( ! empty( $video ) ) {
		// FV WordPress Flowplayer Support for advanced video formats.
		if ( shortcode_exists( 'flowplayer' ) ) {
			$video_embed = '[flowplayer src="' . esc_url( $video ) . '"]';
		} elseif ( ! empty( $filetype['ext'] ) ) {
			$video_embed = wp_video_shortcode( array( 'src' => $video ) );
		} else {
			$video_embed = wp_oembed_get( $video );
		}
	}

	$video_embed = apply_filters( 'the_company_video_embed', $video_embed, $post );

	if ( $video_embed ) {
		echo '<div class="company_video"><div class="company_video_inner">' . $video_embed . '</div></div>'; // WPCS: XSS ok.
	}
}

function listdo_listing_regions($post) {
	$terms = get_the_terms( $post->ID, 'job_listing_region' );

	$termsHTML  = '';
	if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
		$regions = array();
		listdo_listing_regions_walk($terms, 0, $regions);

		$regions = array_reverse($regions, true);
		$i = 1; foreach ($regions as $term) {
			$termsHTML .= '<a href="'.get_term_link($term).'">'.$term->name.'</a>'.($i < count($regions) ? ', ' : '');
			$i++;
		}
	}
	if ( !empty($termsHTML) ) {
		?>
		<div class="listing-location listing-address">
			<i class="flaticon-pin"></i><?php echo trim($termsHTML); ?>
		</div>
		<?php
	}
}

function listdo_listing_regions_walk( $terms, $id_parent, &$dropdown ) {
    foreach ( $terms as $key => $term ) {
        if ( $term->parent == $id_parent ) {
            $dropdown = array_merge( $dropdown, array( $term ) );
            unset($terms[$key]);
            listdo_listing_regions_walk( $terms, $term->term_id,  $dropdown );
        }
    }
}

function listdo_listing_menu_prices($items_data) {
	$html = '';
	if ( !empty($items_data) && is_array($items_data) ) {
		foreach ($items_data as $section) {

			$section_title = !empty($section['section_title']) ? $section['section_title'] : '';
			$titles_value = !empty($section['title']) ? $section['title'] : array();
			$items_prices = !empty($section['price']) ?  $section['price'] : array();
			$items_descriptions = !empty($section['description']) ? $section['description'] : array();
			
			if ( listdo_check_menu_prices_empty($section) ) {
				$html .= '<div class="col-xs-12">
						<div class="menu-prices-wrapper">';
				if ( !empty($section_title) ) {
					$html .= '<h3 class="title-price">'.trim($section_title).'</h3>';
				}
				$html .= '<ul class="listing-menu-prices-list">';
				foreach ($titles_value as $key => $title_value) {
					$price_value = !empty($items_prices[$key]) ? $items_prices[$key] : '';
					$description_value = !empty($items_descriptions[$key]) ? $items_descriptions[$key] : '';
				
					$html .= '<li>
							<h5>'.$title_value.'<span class="price">'.$price_value.'</span></h5>
							<div class="description">
								'.$description_value.'
							</div>
						</li>';
				}
				$html .= '</ul>
						</div>
					</div>';
			}
		}
	}
	return $html;
}

function listdo_listing_for_search_distance($post) {
	global $listdo_distances;
	if ( !empty($listdo_distances[$post->ID]) && !empty($listdo_distances[$post->ID]->distance) ) {
		$distance_type = listdo_get_config('listing_filter_distance_unit', 'km');
		?>
		<div class="listing-distance"><?php echo round($listdo_distances[$post->ID]->distance, 1).' '.$distance_type; ?></div>
		<?php
	}
}

function listdo_display_map_data( $post ) {
	$ouput = 'data-latitude="'.esc_attr( $post->geolocation_lat ).'" data-longitude="'.esc_attr( $post->geolocation_long ).'" data-img="'.esc_attr( listdo_get_post_image_src( $post->ID, 'listdo-card-image' ) ).'" data-thumb="'.esc_attr( listdo_get_post_image_src( $post->ID, 'thumbnail' ) ).'" data-permalink="'.get_the_job_permalink( $post ).'"';
	return $ouput;
}
if(!function_exists('listdo_listings_grid_metas_before')){
    function listdo_listings_grid_metas_before(){
        echo '<div class="grid-metas-inner flex-middle">';
    }
}
if(!function_exists('listdo_listings_grid_metas_after')){
    function listdo_listings_grid_metas_after(){
        echo '</div>';
    }
}
if(!function_exists('listdo_listings_grid_contact_before')){
    function listdo_listings_grid_contact_before(){
        echo '<div class="grid-contact-inner flex-middle">';
    }
}
if(!function_exists('listdo_listings_grid_contact_after')){
    function listdo_listings_grid_contact_after(){
        echo '</div>';
    }
}
if(!function_exists('listdo_listings_list_metas_before')){
    function listdo_listings_list_metas_before(){
        echo '<div class="list-metas-inner flex-middle">';
    }
}
if(!function_exists('listdo_listings_list_metas_after')){
    function listdo_listings_list_metas_after(){
        echo '</div>';
    }
}

add_action( 'listdo-listings-logo-after', 'listdo_display_listing_claim_status', 10, 1);



// Place: hook loop grid
add_action('listdo-listings-grid-logo', 'listdo_display_listing_logo_image', 10, 1);

add_action('listdo-listings-grid-flags-top', 'listdo_display_price_range', 20, 1);
add_action('listdo-listings-grid-flags-top', 'listdo_listing_display_featured_label', 10, 1);

add_action('listdo-listings-grid-flags-bottom', 'listdo_display_listing_card_bookmark_btn', 20, 1);

add_action('listdo-listings-grid-title-below', 'listdo_listing_tagline', 10, 1);
add_action('listdo-listings-grid-title-above', 'listdo_display_listing_review', 10, 1);

add_action('listdo-listings-grid-contact-info', 'listdo_listings_grid_contact_before', 1, 1);
add_action('listdo-listings-grid-contact-info', 'listdo_listing_regions', 10, 1);
add_action('listdo-listings-grid-contact-info', 'listdo_display_listing_phone', 20, 1);
add_action('listdo-listings-grid-contact-info', 'listdo_listings_grid_contact_after', 30, 1);

add_action('listdo-listings-grid-metas', 'listdo_listings_grid_metas_before', 1, 1);
add_action('listdo-listings-grid-metas', 'listdo_display_listing_first_category', 10, 1);
add_action('listdo-listings-grid-metas', 'listdo_display_time_status', 20, 1);
add_action('listdo-listings-grid-metas', 'listdo_listings_grid_metas_after', 30, 1);

// Place: hook loop grid v2
add_action('listdo-listings-grid-v2-logo', 'listdo_display_listing_logo_image', 10, 1);

add_action('listdo-listings-grid-v2-flags-top', 'listdo_display_price_range', 10, 1);
add_action('listdo-listings-grid-v2-flags-top', 'listdo_listing_display_featured_label', 20, 1);
add_action('listdo-listings-grid-v2-flags-top', 'listdo_display_time_status', 9, 1);

add_action('listdo-listings-grid-v2-flags-bottom', 'listdo_display_listing_review_v1', 10, 1);
//add_action('listdo-listings-grid-v2-flags-bottom', 'listdo_display_listing_card_bookmark_btn', 20, 1);

add_action('listdo-listings-grid-v2-title-below', 'listdo_listing_tagline', 10, 1);

add_action('listdo-listings-grid-v2-contact-info', 'listdo_listings_grid_contact_before', 1, 1);
add_action('listdo-listings-grid-v2-contact-info', 'listdo_listing_regions', 10, 1);
add_action('listdo-listings-grid-v2-contact-info', 'listdo_display_listing_phone', 20, 1);
add_action('listdo-listings-grid-v2-contact-info', 'listdo_listings_grid_contact_after', 30, 1);

add_action('listdo-listings-grid-v2-metas', 'listdo_listings_grid_metas_before', 1, 1);
add_action('listdo-listings-grid-v2-metas', 'listdo_display_listing_first_category', 10, 1);
add_action('listdo-listings-grid-v2-metas', 'listdo_listings_grid_metas_after', 30, 1);


// Place: hook loop list

add_action('listdo-listings-list-logo', 'listdo_display_listing_logo_image', 10, 1);

add_action('listdo-listings-list-flags-top', 'listdo_display_price_range', 20, 1);
add_action('listdo-listings-list-flags-top', 'listdo_listing_display_featured_label', 10, 1);

add_action('listdo-listings-list-flags-bottom', 'listdo_display_listing_card_bookmark_btn', 20, 1);

add_action('listdo-listings-list-title-below', 'listdo_listing_tagline', 10, 1);
add_action('listdo-listings-list-title-above', 'listdo_display_listing_review', 10, 1);

add_action('listdo-listings-list-contact-info', 'listdo_listings_grid_contact_before', 1, 1);
add_action('listdo-listings-list-contact-info', 'listdo_listing_regions', 10, 1);
add_action('listdo-listings-list-contact-info', 'listdo_display_listing_phone', 20, 1);
add_action('listdo-listings-list-contact-info', 'listdo_listings_grid_contact_after', 30, 1);

add_action('listdo-listings-list-metas', 'listdo_listings_grid_metas_before', 1, 1);
add_action('listdo-listings-list-metas', 'listdo_display_listing_first_category', 10, 1);
add_action('listdo-listings-list-metas', 'listdo_display_time_status', 20, 1);
add_action('listdo-listings-list-metas', 'listdo_listings_grid_metas_after', 30, 1);


// Place: hook loop list version 3
add_action('listdo-listings-list-v3-contact-info', 'listdo_listing_regions', 10, 1);
add_action('listdo-listings-list-v3-contact-info', 'listdo_display_listing_phone', 20, 1);
add_action('listdo-listings-list-v3-review', 'listdo_display_listing_review', 10, 1);


// Place: Listing single
add_action('listdo-single-listing-header-ratings', 'listdo_display_listing_review', 10, 1);
add_action('listdo-single-listing-header-logo', 'listdo_display_listing_logo_image', 10, 1);

add_action('listdo-single-listing-header-title', 'listdo_listing_title', 10, 1);
add_action('listdo-single-listing-header-title', 'listdo_listing_claimed_status', 20, 1);

add_action('listdo-single-listing-header-title-bellow', 'listdo_listing_tagline', 10, 1);

add_action('listdo-single-listing-header-metas', 'listdo_display_listing_all_categories', 10, 1);
add_action('listdo-single-listing-header-metas', 'listdo_display_listing_phone', 20, 1);
add_action('listdo-single-listing-header-metas', 'listdo_display_listing_location', 30, 1);

// bottom
add_action('listdo-single-listing-header-right', 'listdo_listing_directions', 18, 1);
add_action('listdo-single-listing-header-right', 'listdo_listing_call', 19, 1);
add_action('listdo-single-listing-header-right', 'listdo_listing_website', 20, 1);
add_action('listdo-single-listing-header-right', array('Listdo_Bookmark', 'display_bookmark_btn'), 21, 1);
add_action('listdo-single-listing-header-right', 'listdo_listing_single_socials_share', 22, 1);
add_action('listdo-single-listing-header-right', 'listdo_listing_review_btn', 30, 1);

// version 2
add_action('listdo-single-listing-header-right-v2', 'listdo_listing_directions', 18, 1);
add_action('listdo-single-listing-header-right-v2', 'listdo_listing_call', 19, 1);
add_action('listdo-single-listing-header-right-v2', 'listdo_listing_website', 20, 1);

// hook more
add_action('listdo-single-listing-header-right-more', array('Listdo_Bookmark', 'display_bookmark_btn'), 21, 1);
add_action('listdo-single-listing-header-right-more', 'listdo_listing_single_socials_share', 22, 1);
add_action('listdo-single-listing-header-right-more', 'listdo_listing_review_btn', 30, 1);