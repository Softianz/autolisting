<?php
$post_id = $_GET['listing_id'];
global $post;
$post = get_post($post_id);

?>
<div class="quickview-wrapper job_listing quickview-place job-grid-style job-grid-place" <?php echo trim(listdo_display_map_data($post)); ?>>
	
	<div class="row no-margin">
		<div class="col-sm-6 no-padding">
			<div class="preview-content-wrapper">
				<div class="preview-content-inner">

					<div class="listing-image">
						<?php listdo_display_listing_cover_image('medium_large'); ?>
						<?php do_action( 'listdo-listings-preview-flags', $post ); ?>
					</div>

					<div class="bottom-grid">
						<div class="listing-content flex-middle clearfix">
							<?php do_action( 'listdo-listings-preview-logo', $post ); ?>
							<div class="listing-content-inner">
								<?php do_action( 'listdo-listings-preview-title-above', $post ); ?>
								<h3 class="listing-title"><a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo get_the_title($post_id); ?></a></h3>
								<?php do_action( 'listdo-listings-preview-title-below', $post ); ?>
							</div>
						</div>
						<div class="listing-content-bottom flex-middle clearfix">
							<?php do_action( 'listdo-listings-preview-metas', $post ); ?>
						</div>
					</div>
					
					<?php if(get_post_field('post_content', $post_id)){ ?>
						<div class="description ">
							<h3 class="title-des"><?php esc_html_e( 'Description', 'listdo' ); ?></h3>
							<?php echo apply_filters('the_content', get_post_field('post_content', $post_id) ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-sm-6 no-padding ">
			<div id="apus-preview-listing-map" class="apus-preview-listing-map"></div>
		</div>
	</div>
</div>