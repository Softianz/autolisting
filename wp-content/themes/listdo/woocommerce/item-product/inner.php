<?php 
global $product;
$product_id = $product->get_id();
$review_count = $product->get_review_count();
?>
<div class="product-block grid" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="grid-inner">
        <div class="block-inner">
            <figure class="image">
                <?php
                    $image_size = isset($image_size) ? $image_size : 'woocommerce_thumbnail';
                    listdo_product_image($image_size);
                ?>
                <?php do_action('listdo_woocommerce_before_shop_loop_item'); ?>
            </figure>
        </div>
        <div class="metas clearfix">
                <?php
                    /**
                    * woocommerce_after_shop_loop_item_title hook
                    *
                    * @hooked woocommerce_template_loop_rating - 5
                    * @hooked woocommerce_template_loop_price - 10
                    */
                    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
                    do_action( 'woocommerce_after_shop_loop_item_title');
                    $regular_price = $product->get_regular_price();
                    if(empty($regular_price) || ($regular_price == 0) ){
                        echo '<span class="price">'.esc_html__('Free','listdo').'</span>';
                    }
                ?>
                <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php
                    $rating_html = wc_get_rating_html( $product->get_average_rating() );
                    if ( $rating_html ) {
                        ?>
                        <div class="rating clearfix">
                            <?php echo trim( $rating_html ); ?>
                            <span class="count">(<?php echo trim($review_count); ?>)</span>
                        </div>
                        <?php
                    }
                ?>
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
        </div>
    </div>
</div>