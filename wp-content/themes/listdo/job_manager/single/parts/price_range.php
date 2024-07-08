<?php
global $post;
$price_range = get_post_meta($post->ID, '_job_price_range', true);
$price_from = get_post_meta($post->ID, '_job_price_from', true);
$price_to = get_post_meta($post->ID, '_job_price_to', true);

$price_range_labels = listdo_job_manager_price_range_icons();

if ( ($price_range && isset($price_range_labels[$price_range])) || $price_from || $price_to) {
?>
<div id="listing-price_range" class="listing-price_range widget">
	<h2 class="widget-title">
		<i class="flaticon-price-tag-1"></i><span><?php esc_html_e('Price Range', 'listdo'); ?></span>
	</h2>
	<div class="inner">
		<?php
		if ( $price_range && isset($price_range_labels[$price_range])) {
			$max = end($price_range_labels);
			$labels = $price_range_labels[$price_range];
			?>
				<div class="wrapper-price">
					<?php if ( !empty($max['icon']) ) { ?>
						<span class="listing-price-range">
							<?php echo trim($max['icon']); ?>
						</span>
					<?php } ?>
					<span class="listing-price-range active" data-placement="top" data-toggle="tooltip" title="<?php echo esc_attr($labels['label']); ?>">
						<?php echo esc_attr($labels['icon']); ?>
					</span>
				</div>
			<?php
		}
		?>
		<span class="highlight hidden"> <?php echo esc_html_e('Price Range', 'listdo'); ?></span>
		<?php
		listdo_display_price_range($post);
		?>

		<?php do_action('listdo-single-listing-price-range', $post); ?>
	</div>
</div>
<?php }