<?php
global $post;
?>
<div <?php job_listing_class('job-list-style job-list-style-v3'); ?> <?php echo trim(listdo_display_map_data($post)); ?>>
	<div class="flex-middle full">
		<div class="listing-image">
			<?php listdo_display_listing_cover_image('listdo-list-v3-image'); ?>
		</div>
		<div class="bottom-list flex-column flex">
			<div class="listing-content">
				<div class="listing-content-inner clearfix">
					<?php do_action( 'listdo-listings-list-v3-title-above', $post ); ?>
					<h3 class="listing-title">
						<a href="<?php the_job_permalink(); ?>"><?php the_title(); ?></a>
						<?php do_action( 'listdo-listings-logo-after', $post ); ?>
					</h3>
					<?php do_action( 'listdo-listings-list-v3-title-below', $post ); ?>
				</div>
				<div class="listing-contact">
					<?php do_action( 'listdo-listings-list-v3-contact-info', $post ); ?>
				</div>
				<?php do_action( 'listdo-listings-list-v3-review', $post ); ?>
			</div>
		</div>
	</div>
</div>