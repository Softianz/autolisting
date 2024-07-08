<article id="post-<?php the_ID(); ?>" <?php post_class('apus-single-listing-v2 apus-single-listing-wrapper'); ?> <?php echo trim(listdo_display_map_data($post)); ?>>
	<?php
	global $post;
	$attach_id = get_post_thumbnail_id( $post->ID );
	$style = '';
	if ( !empty($attach_id) ) {
		$img = wp_get_attachment_image_src($attach_id, 'listdo-image-full');
		if ( !empty($img[0]) ) {
			$style = 'style="background-image:url('.$img[0].')"';
		}
	}
	?>
	<div class="header-gallery-wrapper header-top-job style-white v2" <?php echo trim($style); ?>>
		<?php get_template_part( 'job_manager/single/parts/header-v2' ); ?>
	</div>
	<?php
		$block_contents = array();
		
		$contents = listdo_get_content_sort();
		if ( !empty($contents) ) {
			foreach ($contents as $key => $title) {
				$content = trim(listdo_listing_display_part($key));
				if ( !empty($content) ) {
					$block_contents[$key] = $content;
				}
			}
		}
	?>

	<div class="panel-affix-wrapper">
		<div class="header-tabs-wrapper panel-affix">
			<div class="container">
				<div class="header-tabs-nav">
					<ul class="nav">
						<?php
						$contents = listdo_get_content_sort();
						$default_contents = listdo_get_default_blocks_content();
						// array icon
						$icon = array(
					        'description' => '<i class="flaticon-menu"></i>',
					        'maps' => '<i class="flaticon-placeholder-1"></i>',
					        'amenities' => '<i class="flaticon-list"></i>',
					        'photos' => '<i class="flaticon-gallery"></i>',
					        'menu-prices' => '<i class="flaticon-price-tag-1"></i>',
					        'video' => '<i class="flaticon-multimedia"></i>',
					        'hours' => '<i class="flaticon-stopwatch"></i>',
					        'products' => '<i class="flaticon-shopping-cart"></i>',
					        'review-avg' => '<i class="flaticon-star-1"></i>',
					        'comments' => '<i class="flaticon-chat-comment-oval-speech-bubble-with-text-lines"></i>',
					    );

						if ( !empty($contents) ) {
							foreach ($contents as $key => $title) {
								$content = trim(listdo_listing_display_part($key));
								if ( !empty($content) ) {
									$block_contents[$key] = $content;
									?>
									<li>
										<a href="#listing-<?php echo esc_attr($key); ?>">
											<?php foreach ($icon as $keyicon => $value) { 
												if($keyicon == $key ){
													echo trim($value);
												}
											}
											?>
											<?php if ( !empty($default_contents[$key]) ) {
												echo esc_html($default_contents[$key]);
											} else {
												echo esc_html($title);
											} ?>
										</a>
									</li>
									<?php
								}
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container detail-content">
		<div class="entry-listing-content">
			<?php
			$job_manager = $GLOBALS['job_manager'];
			
			ob_start();

			do_action( 'job_content_start' );
			
			get_job_manager_template( 'single/content-default.php', array('block_contents' => $block_contents) );

			do_action( 'job_content_end' );

			$content = ob_get_clean();


			echo apply_filters( 'job_manager_single_job_content', $content, $post );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listdo' ),
				'after'  => '</div>',
			) ); ?>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-## -->