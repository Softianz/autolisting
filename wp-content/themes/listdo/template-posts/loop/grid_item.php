<?php 
    $thumbsize = !isset($thumbsize) ? listdo_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
    $thumb = listdo_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-grid-v1'); ?>>
    <?php if($thumb) {?>
        <div class="top-image">
            <?php
                echo trim($thumb);
            ?>
            <div class="category">
                <?php listdo_post_categories($post); ?>
            </div>
         </div>
    <?php } ?>
    <div class="inner-bottom">
        <?php if(!$thumb) {?>
        <div class="category">
            <?php listdo_post_categories_first($post); ?>
        </div>
         <?php } ?>
        <?php if (get_the_title()) { ?>
            <h4 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        <?php } ?>
        <div class="date">
            <?php the_time( get_option('date_format', 'd M, Y') ); ?>
        </div>
    </div>
</article>