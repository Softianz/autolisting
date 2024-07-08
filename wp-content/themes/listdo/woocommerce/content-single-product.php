<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

global $product;

?>
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	}
	
    $thumbs_pos = listdo_get_config('product_thumbs_position', 'thumbnails-bottom');
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'details-product', $product ); ?>>
	
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<div class="image-mains <?php echo esc_attr( $thumbs_pos ); ?>">
				<?php
					/**
					 * woocommerce_before_single_product_summary hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
					do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="information clearfix">
				<div class="summary entry-summary no-margin">

					<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */

						//add_filter( 'woocommerce_single_product_summary', 'listdo_woocommerce_share_box', 30 );
						remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
						remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
						remove_action('woocommerce_single_product_summary','listdo_woocommerce_share_box',100);

						add_action('woocommerce_single_product_summary','woocommerce_template_single_rating',6);
						add_action('woocommerce_single_product_summary','woocommerce_template_single_price',21);
						do_action( 'woocommerce_single_product_summary' );
					?>
				</div><!-- .summary -->
				
				<?php do_action( 'listdo_after_woocommerce_single_product_summary' ); ?>
			</div>
		</div>
	</div>

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>