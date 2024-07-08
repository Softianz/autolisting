<?php
global $post;
?>
<div <?php job_listing_class('job-list-style job-list-style-v1'); ?> <?php echo trim(listdo_display_map_data($post)); ?>>
	<div class="flex-sm full">
		<div class="listing-image">
			<?php listdo_display_listing_cover_image('listdo-list-image'); ?>
			<div class="flags-top-wrapper">
				<?php do_action( 'listdo-listings-list-flags-top', $post ); ?>
			</div>
			<div class="flags-bottom-wrapper">
				<?php do_action( 'listdo-listings-list-flags-bottom', $post ); ?>
			</div>
		</div>
		<div class="bottom-list flex-column flex">
			<div class="listing-content">
				<?php do_action( 'listdo-listings-list-logo', $post ); ?>
				<div class="listing-content-inner clearfix">
					<?php do_action( 'listdo-listings-list-title-above', $post ); ?>
					<h3 class="listing-title">
						<a href="<?php the_job_permalink(); ?>"><?php the_title(); ?></a>
						<?php do_action( 'listdo-listings-logo-after', $post ); ?>
					</h3>
					<?php do_action( 'listdo-listings-list-title-below', $post ); ?>
				</div>
			
				<div class="listing-contact">
					<?php do_action( 'listdo-listings-list-contact-info', $post ); ?>
				</div>
			</div>
			<div class="listing-content-bottom">
				<?php do_action( 'listdo-listings-list-metas', $post ); ?>
			</div>
		</div>
	</div>
</div>