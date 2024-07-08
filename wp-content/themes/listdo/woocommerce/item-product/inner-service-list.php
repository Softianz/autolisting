<?php 
global $product;
$product_id = $product->get_id();
?>
<div class="product-service-inner product-service-list clearfix" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="row flex-middle ">
        <div class="col-sm-7 col-xs-6 flex-middle">
            <div class="wrapper-image">
                <?php echo trim($product->get_image()); ?>
            </div>
            <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </div>
        <div class="col-sm-5 col-xs-6 right-inner flex-middle">
            <?php
                /**
                * woocommerce_after_shop_loop_item_title hook
                *
                * @hooked woocommerce_template_loop_rating - 5
                * @hooked woocommerce_template_loop_price - 10
                */
                remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5);
                do_action( 'woocommerce_after_shop_loop_item_title');
            ?>
            <div class="groups-button">
                <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
            </div> 
        </div>
    </div>
</div>