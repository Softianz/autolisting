<?php
global $post;
$items_data = get_post_meta( $post->ID, '_job_menu_prices_data', true );

$menu_prices = listdo_listing_menu_prices($items_data);
if ( !empty($menu_prices) ):
?>
	<div id="listing-menu-prices" class="listing-menu-prices widget">
		<h2 class="widget-title">
			<span><?php esc_html_e('Menu Prices', 'listdo'); ?></span>
		</h2>
		<div class="box-inner">
			<div class="row">
				<?php
					echo trim($menu_prices);
				?>

				<?php do_action('listdo-single-listing-menu-prices', $post); ?>
			</div>
		</div>
	</div>
<?php endif; ?>