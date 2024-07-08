<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('no-margin'); ?>>
    <div class="entry-content-detail <?php echo esc_attr((!has_post_thumbnail())?'not-img-featured':'' ); ?>">
        <div class="single-info info-bottom">
            <div class="entry-description">
                <?php
                    the_content();
                ?>
            </div><!-- /entry-content -->
            <?php
            wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'listdo' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'listdo' ) . ' </span>%',
                'separator'   => '',
            ) );
            ?>
            <?php  
                $posttags = get_the_tags();
            ?>
            <?php if( !empty($posttags) ){ ?>
                <div class="tag-social clearfix">
                    <?php listdo_post_tags(); ?>
                </div>
            <?php } ?>
            
        </div>
    </div>
    <?php
        //Previous/next post navigation.
        the_post_navigation( array(
            'next_text' => '<span class="meta-nav"><i class="flaticon-right-arrow"></i></span> ' .
                '<div class="inner">'.
                '<div class="navi">' . esc_html__( 'Next Post', 'listdo' ) . '</div>'.
                '<span class="title-direct">%title</span></div>',
            'prev_text' => '<span class="meta-nav"><i class="flaticon-left-arrow"></i></span> ' .
                '<div class="inner">'.
                '<div class="navi"> ' . esc_html__( 'Prev Post', 'listdo' ) . '</div>'.
                '<span class="title-direct">%title</span></div>',
        ) );
    ?>
</article>