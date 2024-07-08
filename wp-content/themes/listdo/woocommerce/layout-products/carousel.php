<?php
$product_item = isset($product_item) ? $product_item : 'inner';
$show_nav = isset($show_nav) ? $show_nav : false;
$show_smalldestop = isset($show_smalldestop) ? $show_smalldestop : false;
$show_pagination = isset($show_pagination) ? $show_pagination : false;
$rows = isset($rows) ? $rows : 1;
$columns = isset($columns) ? $columns : 4;
$products = isset($products) ? $products : '';
if($product_item == 'inner-deal'){
    $small_cols = 2;
}else{
   $small_cols = $columns <= 1 ? 1 : 2; 
}
?>
<div class="slick-carousel products <?php echo esc_attr($products); ?> <?php echo esc_attr($columns<($loop->post_count))?'':'hidden-dots'; ?>" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>"
    data-medium="2"
    data-smallmedium="<?php echo esc_attr($small_cols); ?>"

    data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>" data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>" data-rows="<?php echo esc_attr( $rows ); ?>">
    <?php while ( $loop->have_posts() ): $loop->the_post(); global $product;?>
        <div class="item">
            <div class="products-grid product">
                <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php wp_reset_postdata(); ?>