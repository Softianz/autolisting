<?php
global $post;
if ( !listdo_listing_review_rating_enable() ) {
	return;
}
$ratings_avg = get_post_meta($post->ID, '_average_ratings', true);

$rating_avg = get_post_meta($post->ID, '_average_rating', true);
$rating_mode = listdo_get_config('listing_review_mode', 10);
$rating_categories = listdo_listing_review_categories();

if ( !empty( $rating_avg ) || !empty($ratings_avg) ) :
	?>
	<div class="review-avg-wrapper widget">
		<h2 class="widget-title">
			<i class="flaticon-star-1"></i><span><?php esc_html_e('Rating Average', 'listdo'); ?></span>
		</h2>
		<div class="review-content clearfix">
			<div class="rating-avg-wrapper clearfix">
				<div class="rating-avg"><?php echo trim($rating_avg); ?></div>
				<div class="rating-mode"><?php esc_html_e('out of', 'listdo'); ?> <?php echo number_format($rating_mode,1); ?></div>

				<div class="star-average-rating">
                	<div class="star-average-inner" style="width: <?php echo round(($rating_avg/$rating_mode * 100), 2).'%'; ?>"></div>
                </div>

			</div>
			<?php if ( $ratings_avg && $rating_categories ) { ?>
				<div class="ratings-avg-wrapper">
					<div class="ratings-avg-wrapper-inner">
					<?php foreach ($rating_categories as $key => $val) { ?>
						<div class="ratings-avg-item">
							<div class="rating-label"><?php echo esc_html($val['title']); ?></div>
							<div class="wrapper-average-rating flex-middle">
								<div class="average-rating">
	                            	<div class="average-inner" style="width: <?php echo round(($ratings_avg[$key]/$rating_mode * 100), 2).'%'; ?>"></div>
	                            </div>
	                            <div class="number">
	                            	<?php echo number_format($ratings_avg[$key],1); ?>
	                            </div>
                            </div>
						</div>
						
					<?php } ?>
					</div>
				</div>
			<?php } ?>
			<?php do_action('listdo-single-listing-review-avg', $post); ?>
		</div>
	</div>
<?php endif; ?>