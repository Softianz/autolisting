<?php
global $post;
$pids = get_post_meta( $post->ID, '_job_products', true );

if ( ! $pids || ! is_array( $pids ) ) {
	return;
}

$args = array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => -1,
	'post__in' => $pids,
);

$loop = new WP_Query( $args );

if ( $loop->have_posts() ) {
	$layout_type = 'service';
	$columns = listdo_get_config('listing_products_columns', 1);
?>

	<div class="listing-products-booking widget woocommerce">

		<h2 class="widget-title">
			<span><?php esc_html_e('Products', 'listdo'); ?></span>
		</h2>

		<?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns, 'product_item' => 'service') ); ?>
	</div>
<?php
}