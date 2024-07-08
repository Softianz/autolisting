<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */
get_header();
?>
	<div id="primary" class="content-area space-content top-30">
		<main id="main" class="site-main" role="main">
			<div class="container">
			<div class="container-inner main-content">
				<div class="row"> 
	                <!-- MAIN CONTENT -->
	                <div class="col-lg-8 col-md-8 col-xs-12">
                        <?php
	                        if ( have_posts() ) {
	                        	$layout = listdo_get_blogs_layout_type();
								get_template_part( 'template-posts/layouts/'.$layout );
								listdo_paging_nav();
							} else {
	                            get_template_part( 'template-posts/content', 'none' );
							}
						?>
	                </div>
	                <div class="col-xs-12 col-md-4 sidebar">
	                	<?php if ( is_active_sidebar( 'sidebar-default' ) ): ?>
				   			<?php dynamic_sidebar('sidebar-default'); ?>
				   		<?php endif; ?>
	                   	
	                </div>
	            </div>
            </div>
            </div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>