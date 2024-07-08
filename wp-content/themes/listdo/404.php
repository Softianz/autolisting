<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */
/*
*Template Name: 404 Page
*/
get_header();
$sidebar_configs = listdo_get_page_layout_configs();
listdo_render_breadcrumbs();
?>
<section class="page-404">
	<section id="main-container" class="<?php echo apply_filters('listdo_page_content_class', 'container');?> inner">
		<div class="row">
			<?php if ( isset($sidebar_configs['left']) ) : ?>
				<div class="<?php echo esc_attr($sidebar_configs['left']['class']) ;?>">
				  	<aside class="sidebar sidebar-left" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
				  		<?php if ( is_active_sidebar( $sidebar_configs['left']['sidebar'] ) ): ?>
				   			<?php dynamic_sidebar( $sidebar_configs['left']['sidebar'] ); ?>
				   		<?php endif; ?>
				  	</aside>
				</div>
			<?php endif; ?>
			<div id="main-content" class="main-page <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
				<section class="error-404 not-found text-center clearfix">
					<div class="text-center">
						<div class="top-404">
							<?php
							$image = listdo_get_config('404-image');
							if ( !empty($image['url']) ) { ?>
								<img src="<?php echo esc_url( $image['url'] ); ?>">
							<?php }else{ ?>
								<img src="<?php echo esc_url( get_template_directory_uri().'/images/404.png'); ?>">
							<?php } ?>
						</div>
						<div class="page-content">
							<?php
							$title = listdo_get_config('title_description');
							if( !empty($title) ) { ?>
								<h1 class="title"><?php echo esc_html($title); ?></h1>
							<?php } else { ?>
								<h1 class="title"><?php echo esc_html_e( 'We Are Sorry, Page Not Found', 'listdo' ); ?> </h1>
							<?php } ?>
							
							<?php get_search_form(); ?>	

						</div><!-- .page-content -->
					</div>
				</section><!-- .error-404 -->
			</div><!-- .content-area -->
			<?php if ( isset($sidebar_configs['right']) ) : ?>
				<div class="<?php echo esc_attr($sidebar_configs['right']['class']) ;?>">
				  	<aside class="sidebar sidebar-right" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
				  		<?php if ( is_active_sidebar( $sidebar_configs['right']['sidebar'] ) ): ?>
					   		<?php dynamic_sidebar( $sidebar_configs['right']['sidebar'] ); ?>
					   	<?php endif; ?>
				  	</aside>
				</div>
			<?php endif; ?>
		</div>
	</section>
</section>
<?php get_footer(); ?>