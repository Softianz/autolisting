<!--<link rel="stylesheet" href="http://localhost/listit/wp-content/themes/listdo/css/bootstrap-1.css">-->
<link rel="stylesheet" href="https://softianz.com/autolisting2/wp-content/themes/listdo/css/bootstrap-1.css">


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.1/js/bootstrap.min.js"></script>

					<style type="text/css">
    	
    	.carousel-indicators button.thumbnail {
			  width: 100px;
			}
			.carousel-indicators button.thumbnail:not(.active) {
			  opacity: 0.7;
			}
			.carousel-indicators {
			  position: static;
			}
			@media screen and (min-width: 992px) {
			  .carousel {
			    max-width: 70%;
			    margin: 0 auto;
			  }
			}


    </style> -->
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
				<?php /* $count = 1; foreach ($photos as $thumb_id): ?>
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
				<?php $count++; endforeach; */ ?>
			</div>
		</div>
	</div>


	<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

<div class="carousel-inner" style="height: 400px; text-align: center;">
<?php $count = 1; foreach ($photos as $thumb_id): ?>
					<?php 
						$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
						//print_r($image_full);
						//die("dasdsa");
						$image_full_url = isset($image_full[0]) ? $image_full[0] : '';
					?>
		        	
						<?php
						if ( !empty($thumb_id) ) {
						?>

<div class="carousel-item <?php if($count == 1){echo 'active';} ?>">
	<img src="<?php echo $image_full[0]; ?>" class="d-block w-100" alt="..." style=" height: 400px;
    margin: auto; " >
  </div>
						
					
						<?php } ?>
					
				<?php $count++; endforeach; ?>
  
</div>
<div class="carousel-indicators" style="width:100% !important; overflow: none;">
	
<?php $count = 1; foreach ($photos as $thumb_id): ?>
					<?php 
						$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
						//print_r($image_full);
						//die("dasdsa");
						$image_full_url = isset($image_full[0]) ? $image_full[0] : '';
					?>
		        	
						<?php
						if ( !empty($thumb_id) ) {
						?>


			
  <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $count-1; ?>" class="<?php if($count == 1){echo 'active';} ?> thumbnail" aria-current="true" aria-label="Slide <?php echo $count; ?>">

	<img src="<?php echo $image_full[0]; ?>" class="d-block w-100" alt="...">
  </button>
					
						<?php } ?>
					
				<?php $count++; endforeach; ?>


 
</div>
</div>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.1/js/bootstrap.min.js"></script>