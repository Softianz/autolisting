<div class="job-list-style job-list-style-v2 user-listing job_listing">
	<div class="flex-sm full">
		<div class="listing-image">
			<?php listdo_display_listing_cover_image('listdo-list-image'); ?>
			<div class="flags-top-wrapper">
				<?php do_action( 'listdo-listings-list-flags-top', $job ); ?>
			</div>
			<div class="flags-bottom-wrapper">
				<?php do_action( 'listdo-listings-list-flags-bottom', $job ); ?>
			</div>
		</div>
		<div class="bottom-list flex-column flex">
			<div class="listing-content">
				<?php do_action( 'listdo-listings-list-logo', $job ); ?>
				<div class="listing-content-inner clearfix">
					<?php do_action( 'listdo-listings-list-title-above', $job ); ?>
					<h3 class="listing-title">
						<a href="<?php the_job_permalink(); ?>"><?php echo get_the_title($job); ?></a>
						<?php do_action( 'listdo-listings-logo-after', $job ); ?>
					</h3>
					<?php do_action( 'listdo-listings-list-title-below', $job ); ?>
				</div>
			
				<div class="listing-contact">
					<?php do_action( 'listdo-listings-list-contact-info', $job ); ?>
				</div>
			</div>
			<div class="listing-content-bottom">
				<?php do_action( 'listdo-listings-list-metas', $job ); ?>
			</div>
		</div>
	</div>
</div>