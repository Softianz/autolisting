<?php
global $post;
$photos = listdo_get_listing_gallery( $post->ID );
if ( ! empty( $photos ) ) :
	$slideshow_key = listdo_random_key();
?>
	<div class="container-fluid no-padding">
		<div class="entry-featured-carousel gallery-listing">
			<div class="slick-carousel" data-carousel="slick" data-items="4" data-smallmedium="3" data-extrasmall="3" data-margin="0" data-smallest="2" data-pagination="false" data-nav="true">
				<?php foreach ($photos as $thumb_id): ?>
					
					<?php
					$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
					$image_full_url = isset($image_full[0]) ? $image_full[0] : '';
					if ($image_full_url) {
					?>
						<a class="photo-gallery-item" href="<?php echo esc_url($image_full_url); ?>" data-elementor-lightbox-slideshow="<?php echo esc_attr($slideshow_key); ?>">
							<?php echo listdo_get_attachment_thumbnail($thumb_id, 'listdo-image-gallery'); ?>
							<span class="click-view">
								<span class="flaticon-magnifying-glass"></span>
							</span>
						</a>
					<?php } ?>

				<?php endforeach; ?>

				<?php do_action('listdo-single-listing-gallery', $post); ?>
			</div>
		</div>
	</div>
<?php endif; ?>