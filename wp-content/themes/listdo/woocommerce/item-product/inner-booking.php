<?php 
global $product;
$product_type_class = "";
$product_details = wc_get_product( $product->get_id() );
if ( ! empty( $product_details ) && ! empty( $product_details->product_type ) ) {
    $product_type_class = "product-type_" . strtolower( $product_details->product_type );
}

?>

<?php
/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
?>

<div class="product-block grid woocommerce add_to_cart_inline <?php echo esc_attr($product_type_class); ?>">
    <div class="clearfix">
        <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php
        //we start from the single product page but cherry-pick what we need
        wc_get_template( 'single-product/price.php' );
        wc_get_template( 'single-product/short-description.php' );
        woocommerce_template_single_add_to_cart(); ?>

    </div><!-- .summary -->
</div>