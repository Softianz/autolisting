<?php
global $post;
$photos = listdo_get_listing_gallery( $post->ID );
$total = count($photos);
if ( ! empty( $photos ) ) :
	$slideshow_key = listdo_random_key();
	?>
	<div id="listing-photos" class="widget">
		<h3 class="widget-title"><i class="flaticon-gallery"></i><?php esc_html_e( 'Gallery', 'listdo' ); ?></h3>
		<div class="photos-wrapper">
			<div class="row">
				<?php $count = 1; foreach ($photos as $thumb_id): ?>
					<?php 
						$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
						$image_full_url = isset($image_full[0]) ? $image_full[0] : '';
					?>
		        	<div class="item col-xs-3">
						<?php
						if ( !empty($thumb_id) ) {
						?>
						<div class="attachment <?php echo esc_attr($count > 8 ? 'hidden' : ''); ?>"><div class="p-relative"><div class="image-wrapper">
							<a class="photo-gallery-item" href="<?php echo esc_url($image_full_url); ?>" data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow_key); ?>">
								<?php echo trim(listdo_get_attachment_thumbnail($thumb_id, 'listdo-image-gallery')); ?>
							</a></div>
							
							<?php if ( $count == 8 && $total > 8 ) { ?><span class="show-more-images">+<?php echo trim($total - 8); ?><span class="text"><?php echo esc_html__('Show Photos','listdo');  ?></span><span class="click-icon"><i class="flaticon-more-button-interface-symbol-of-three-horizontal-aligned-dots"></i></span> </span>
							<?php } ?>

						</div></div>
						<?php } ?>
					</div>
				<?php $count++; endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>