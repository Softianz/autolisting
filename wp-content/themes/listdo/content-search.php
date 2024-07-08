<?php
/**
 * The template part for displaying results in search pages
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post post-layout post-list-item'); ?>>
	<div class="list-inner">
		<div class="<?php echo (!empty($thumb))?'col-content':'col-content-full'; ?>">
	        <?php if (get_the_title()) { ?>
	            <h4 class="entry-title">
	                <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	                    <div class="stick-icon"><i class="ti-pin2"></i></div>
	                <?php endif; ?>
	                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	            </h4>
	        <?php } ?>
	        <div class="top-info flex-middle-sm">
	            <div class="flex-middle hidden-xs">
	                <div class="avatar-wrapper">
	                    <?php echo get_avatar( get_the_author_meta( 'user_email' ),40 ); ?>
	                </div>
	                <div class="name-author">
	                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
	                </div>
	            </div>
	            <div class="date">
	                <i class="far fa-calendar-check"></i><?php the_time( get_option('date_format', 'd M, Y') ); ?>
	            </div>
	            <?php if(count(wp_get_post_categories( $post->ID )) > 0) {?>
	                <div class="category">
	                    <i class="far fa-folder-open"></i><?php listdo_post_categories_first($post); ?>
	                </div>
	            <?php } ?>
	        </div>
	        <div class="description"><?php echo listdo_substring( get_the_excerpt(),35, '...' ); ?></div>
	        <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'listdo'); ?></a>
	    </div>
    </div>
</article><!-- #post-## -->