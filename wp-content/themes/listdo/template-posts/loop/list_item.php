<?php 
global $post;
$thumbsize = !isset($thumbsize) ? listdo_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
$thumb = listdo_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-list-item'); ?>>
    <div class="list-inner">
        <?php
            if ( !empty($thumb) ) {
                ?>
                <div class="top-image">
                    <?php
                        echo trim($thumb);
                    ?>
                 </div>
                <?php
            }
        ?>
        <div class="<?php echo (!empty($thumb))?'col-content':'col-content-full'; ?>">
            <?php if (get_the_title()) { ?>
                <h4 class="entry-title">
                    <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
                        <div class="stick-icon"><i class="ti-pin2"></i></div>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
            <?php } ?>
            <div class="top-info flex-middle">
                <div class="name-author">
                    <a href="<?php the_permalink(); ?>"><i class="far fa-user"></i><?php echo get_the_author(); ?></a>
                </div>
                <div class="date">
                    <i class="far fa-calendar-check"></i><?php the_time( get_option('date_format', 'd M, Y') ); ?>
                </div>
                <?php
                $categories = get_the_category();
                if( ! empty( $categories ) ) {?>
                    <div class="category">
                        <i class="far fa-folder-open"></i><?php listdo_post_categories_first($post); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="description hidden-xs"><?php echo listdo_substring( get_the_excerpt(),40, '...' ); ?></div>
            <div class="description visible-xs"><?php echo listdo_substring( get_the_excerpt(),15, '...' ); ?></div>
            <a class="btn-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'listdo'); ?></a>
        </div>
    </div>
</article>