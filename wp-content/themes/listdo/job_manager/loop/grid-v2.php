<?php
global $post;
?>
<div <?php job_listing_class('job-grid-style-v2'); ?> <?php echo trim(listdo_display_map_data($post)); ?>>
	<div class="p-relative">
		<div class="listing-image">
			<?php listdo_display_listing_cover_image('listdo-card-image'); ?>
			<div class="flags-top-wrapper">
				<?php do_action( 'listdo-listings-grid-flags-top', $post ); ?>
				<?php do_action( 'listdo-listings-grid-flags-bottom', $post ); ?>
			</div>
		</div>
		<div class="bottom-grid">
			<div class="listing-content">
				
				<div class="listing-content-inner clearfix">
					<?php do_action( 'listdo-listings-grid-title-above', $post ); ?>
					<h3 class="listing-title">
						<a href="<?php the_job_permalink(); ?>"><?php the_title(); ?></a>
						<?php do_action( 'listdo-listings-logo-after', $post ); ?>
					</h3>
				</div>
				<div class="listing-contact">
					<?php do_action( 'listdo-listings-grid-contact-info', $post ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="listing-content-bottom">
		<?php do_action( 'listdo-listings-grid-logo', $post ); ?>
		<?php do_action( 'listdo-listings-grid-metas', $post ); ?>
	</div>
</div>