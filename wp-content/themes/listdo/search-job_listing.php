<?php

get_header();

$layout_version = listdo_get_listing_archive_version();


if ($layout_version == 'default') {
	listdo_render_breadcrumbs();
}
?>
	<section id="main-container" class="main-content inner">
		<div id="primary" class="content-area">
			<div class="entry-content">
				<main id="main" class="site-main" role="main">
				<?php
					$shortcode = '[jobs keywords="'. get_search_query() .'" show_tags="true" orderby="featured" order="DESC"]';
					echo do_shortcode(  $shortcode );
				?>
				</main><!-- #main -->
			</div>
		</div><!-- #primary -->
	</section>

<?php get_footer(); ?>