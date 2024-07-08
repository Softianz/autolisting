<?php

get_header();

$layout_version = listdo_get_listing_archive_version();

if ($layout_version == 'default') {
	listdo_render_breadcrumbs();
}
?>
	
	<section id="main-container" class="inner">
		
		<div id="primary" class="content-area">
			<div class="entry-content">
				<main id="main" class="site-main" role="main">
				<?php
					global $wp_query;
					
					$pagination = '';
					if ( !listdo_get_config('listing_show_loadmore', true) ) {
						$pagination = 'show_pagination="true"';
					}
					
					$term =	$wp_query->queried_object;
					if ( isset( $term->slug) ) {
						$shortcode = '[jobs job_types="' . $term->slug . '" show_more="false" '.$pagination.' show_tags="true" orderby="featured" order="DESC"]';

						echo do_shortcode(  $shortcode );
					}
				?>
				</main><!-- #main -->
			</div>
		</div><!-- #primary -->
	</section>

<?php get_footer(); ?>