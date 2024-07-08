<?php
global $post;

$virtual_tour = get_post_meta( $post->ID, '_job_virtual_tour', true ); ?>
<?php if ( ! empty( $virtual_tour ) ) : ?>
	<div id="listing-virtual-tour" class="listing-virtual-tour widget">
		<h2 class="widget-title"><?php echo esc_html__( 'Virtual Tour', 'listdo' ); ?></h2>
		<div class="box-inner">
			<iframe src="<?php echo esc_url($virtual_tour); ?>" allowfullscreen="" allowvr="" height="500" frameborder="0" width="100%"></iframe>
		</div>
	</div>
<?php endif; ?>