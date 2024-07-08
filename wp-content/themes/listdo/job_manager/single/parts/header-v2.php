<?php
global $post;
?>
<header class="entry-header">
	<div class="container">
		<div class="entry-header-wrapper clearfix">

				<div class="entry-header-top-normal row flex-md">
					<div class="left-inner col-xs-12 col-md-5">
						<div class="flex-middle">
							<?php do_action( 'listdo-single-listing-header-logo', $post ); ?>
							<div class="right-info">
								<?php do_action('listdo-single-listing-header-title-above', $post); ?>
								<div class="entry-title-wrapper">
									<?php do_action('listdo-single-listing-header-title', $post); ?>
								</div>
								<?php do_action('listdo-single-listing-header-title-bellow', $post); ?>
								<?php do_action( 'listdo-single-listing-header-ratings', $post ); ?>
							</div>
						</div>
					</div>
					<div class="right-inner col-xs-12 col-md-7 flex-bottom">
						<div class="flex-middle full justify-content-end-md">
							<div class="header-right-v2 flex-middle">
								<?php do_action('listdo-single-listing-header-right-v2', $post); ?>
							</div>
							<div class="wrapper-showmore">
								<span class="showmore">
									<i class="flaticon-more-button-interface-symbol-of-three-horizontal-aligned-dots"></i>
								</span>
								<div class="content-inner">
									<?php do_action('listdo-single-listing-header-right-more', $post); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</header><!-- .entry-header -->