<?php
global $post;
$video = get_post_meta($post->ID, '_job_video', true);
if ($video) {
	?>
	<div id="listing-video" class="listing-video widget">
		<h2 class="widget-title">
			<i class="flaticon-multimedia"></i><span><?php esc_html_e('Video', 'listdo'); ?></span>
		</h2>
		<?php listdo_listing_video($post); ?>
	</div>
	<?php
}
?>