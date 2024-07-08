<?php
global $post;
?>
<header class="entry-header">
	<div class="container">
		<div class="entry-header-wrapper clearfix">

				<div class="entry-header-top-center">
					<?php do_action( 'listdo-single-listing-header-ratings', $post ); ?>
					<?php do_action( 'listdo-single-listing-header-logo', $post ); ?>
					<div class="entry-header-content-inner">
						<div class="top-header">
							<?php do_action('listdo-single-listing-header-title-above', $post); ?>
							<div class="entry-title-wrapper">
								<?php do_action('listdo-single-listing-header-title', $post); ?>
							</div>
							<?php do_action('listdo-single-listing-header-title-bellow', $post); ?>
						</div>
						<div class="header-metas">
							<?php
							/**
							 * single_job_listing_start hook
							 *
							 * @hooked job_listing_meta_display - 20
							 * @hooked job_listing_company_display - 30
							 */
							do_action( 'single_job_listing_start' );
							?>
						</div>
					</div>
				</div>

				<div class="entry-header-bottom">
					<?php do_action('listdo-single-listing-header-right', $post); ?>
				</div>
		</div>
	</div>
</header><!-- .entry-header -->