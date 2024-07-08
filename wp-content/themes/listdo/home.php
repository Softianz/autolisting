<?php
get_header();
$sidebar_configs = listdo_get_blog_layout_configs();
$columns = listdo_get_config('blog_columns', 1);
listdo_render_breadcrumbs();

$show = listdo_get_config('show_blog_breadcrumbs', true);
$class = '';
if ( !$show || is_front_page() ) {
	$class = 'top-padding-30';
}
?>
<section id="main-container" class="content-space <?php echo esc_attr($class); ?> <?php echo apply_filters('listdo_blog_content_class', 'container');?> inner">
	<?php listdo_before_content( $sidebar_configs ); ?>
	<div class="row">
		<?php listdo_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<main id="main" class="site-main layout-blog <?php echo esc_attr( ($columns <= 1) ? 'home-blog':''); ?>" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header hidden">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				$layout = listdo_get_blogs_layout_type();
				get_template_part( 'template-posts/layouts/'.$layout );

				// Previous/next page navigation.
				listdo_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
		
		<?php listdo_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer(); ?>