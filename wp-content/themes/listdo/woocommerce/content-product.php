<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$woo_display = listdo_woocommerce_get_display_mode();
if ( $woo_display == 'list' ) { 	
	$classes[] = 'list-products col-xs-12';
?>
	<div <?php wc_product_class( $classes, $product ); ?>>
	 	<?php wc_get_template_part( 'item-product/inner-list' ); ?>
	</div>
<?php
} else {

	// Store loop count we're currently on
	if ( empty( $woocommerce_loop['loop'] ) ) {
		$woocommerce_loop['loop'] = 0;
	}
	// Store column count for displaying the grid
	if ( empty( $woocommerce_loop['columns'] ) ) {
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
	}

	$columns = 12/$woocommerce_loop['columns'];
	if($woocommerce_loop['columns'] == 5){
		$columns = 'cl-5 col-md-3';
	}
	$classes[] = 'col-md-'.$columns.' col-sm-4 col-xs-12 '.($woocommerce_loop['loop']%$woocommerce_loop['columns'] == 0 ? ' md-clearfix' : '').($woocommerce_loop['loop']%3 == 0 ? ' sm-clearfix' : '').($woocommerce_loop['loop']%2 == 0 ? ' xs-clearfix' : '');
	
	?>
	<div <?php wc_product_class( $classes, $product ); ?>>
		<?php wc_get_template_part( 'item-product/inner' ); ?>
	</div>
<?php } ?>