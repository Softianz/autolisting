<div class="layout-blog style-list">
    <div class="clerfix">
        <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-posts/loop/list_item' ); ?>
        <?php endwhile; ?>
    </div>
</div>