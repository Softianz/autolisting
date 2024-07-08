<?php
	$columns = listdo_get_config('blog_columns', 1);
	$bcol = floor( 12 / $columns );
?>
<div class="layout-blog style-grid">
    <div class="row">
        <?php $count = 1; while ( have_posts() ) : the_post(); ?>
            <div class="item-list-blog col-sm-<?php echo esc_attr(( $columns > 1)?6:12); ?> col-md-<?php echo esc_attr($bcol); ?> col-xs-12 <?php echo (($count%$columns)==1)?'md-clearfix':''; ?> <?php echo (($count%2)==1)?'sm-clearfix':''; ?>">
                <?php get_template_part( 'template-posts/loop/grid_item' ); ?>
            </div>
        <?php $count++; endwhile; ?>
    </div>
</div>