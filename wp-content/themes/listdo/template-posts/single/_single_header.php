<?php
$post_format = get_post_format();
global $post;
$featured_img_url = get_the_post_thumbnail_url($post->ID, 'full');
if(!empty($featured_img_url)){
    $style = "style=background-image:url(".$featured_img_url.")";
}else{
    $style = "";
}
?>
<div class="detail-top" <?php echo esc_attr($style); ?>>
    <div class="detail-inner flex-column flex">
        <h1 class="entry-title">
            <?php the_title(); ?>
        </h1>
        <div class="detail-info-bottom">
            <div class="date">
                <?php the_time( get_option('date_format', 'd M, Y') ); ?>
            </div>
            <div class="category">
                <?php listdo_post_categories($post); ?>
            </div>
            <?php if( listdo_get_config('show_blog_social_share', false) ) { ?>
                <span class="show-social">
                    <i class="flaticon-share"></i><?php echo esc_html__('Share','listdo'); ?>
                </span>
                <?php get_template_part( 'template-parts/modal-sharebox' ); ?>
            <?php } ?>
        </div>
        <?php if( $post_format == 'link' ) {
            $format = listdo_post_format_link_helper( get_the_content(), get_the_title() );
            $title = $format['title'];
            $link = listdo_get_link_attributes( $title );
            $thumb = listdo_post_thumbnail('', $link);
            echo trim($thumb);
        } ?>

    </div>
</div>