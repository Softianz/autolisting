<?php
	$product_item_default = isset($product_item) ? $product_item : 'service';
?>
<div class="apus-products-service clearfix">
	<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product;
		$inner = $product_item_default;
		$product_type = $product->get_type();
		if ( $product_type == 'booking' ) {
			$inner = 'booking';
		}
		wc_get_template_part( 'item-product/inner-'.$inner );
	endwhile; ?>
</div>
<?php wp_reset_postdata(); ?>