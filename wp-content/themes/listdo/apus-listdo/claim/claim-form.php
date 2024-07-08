<?php
	if ( function_exists('listdo_get_config') ) {
		$claim_title = listdo_get_config('claim_title', '');
		$claim_banner = listdo_get_config('claim_banner');
	} else {
		$claim_title = '';
		$claim_banner = '';
	}
	$img = '';
	if ( !empty($claim_banner['id']) ) {
		$image = wp_get_attachment_image_src($claim_banner['id'], 'full');
		if ( !empty($image[0]) ) {
			$img = $image[0];
		}
	}
?>
<div id="claim-listing-form-hidden" class="hidden">
	<div class="claim-listing-form-wrapper">
		<div class="row">
			<?php if ( $img ) { ?>
				<div class="col-md-6 col-sm-12">
					<img src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e( 'Claim Image', 'listdo' ); ?>">
				</div>
			<?php } ?>
			<div class="<?php echo esc_attr( $img ? 'col-md-6' : ''); ?> col-sm-12">
				<form action="" class="claim-listing-form" method="post">
					<input type="hidden" name="post_id" class="post_id_input">
					<?php if ( $claim_title ) { ?>
						<h4 class="title text-theme"><?php echo esc_html($claim_title); ?></h4>
					<?php } ?>
					<div class="msg"></div>
					<div class="form-group">
			            <input type="text" class="form-control" name="fullname" placeholder="<?php esc_attr_e( 'Fullname', 'listdo' ); ?>" required="required">
			        </div><!-- /.form-group -->
			        <div class="form-group">
			            <input type="text" class="form-control" name="phone" placeholder="<?php esc_attr_e( 'Phone', 'listdo' ); ?>" required="required">
			        </div><!-- /.form-group -->
			        <div class="form-group">
			            <textarea class="form-control" name="message" placeholder="<?php esc_attr_e( 'Additional proof to expedite your claim approval...', 'listdo' ); ?>" cols="30" rows="5" required="required"></textarea>
			        </div><!-- /.form-group -->

			        <button class="button btn btn-block btn-theme" name="submit-claim-listing" value=""><?php echo esc_html__( 'Claim This Business', 'listdo' ); ?></button>
				</form>
			</div>
		</div>
	</div>
</div>