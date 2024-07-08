<?php
global $post;
// get our custom meta
$location = get_post_meta( get_the_ID(), '_job_location', true);

$phone = get_post_meta( get_the_ID(), '_job_phone', true);
$email = get_post_meta( get_the_ID(), '_job_email', true);
$website = get_post_meta( get_the_ID(), '_job_website', true);
?>

<div id="listing-business-info" class="listing-business-info widget">
	<h2 class="widget-title">
		<i class="flaticon-suitcase"></i><span><?php esc_html_e('Business Info', 'listdo'); ?></span>

		<?php
			$location = get_the_job_location( $post );
			if ( $location ) {
				?>
				<a class="map-direction direction-map pull-right" href="<?php echo esc_url( 'http://maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ) ; ?>" target="_blank">
					<i class="far fa-hand-point-right"></i>
					<?php esc_html_e('Get Directions', 'listdo'); ?>
				</a>
				<?php
			}
		?>
	</h2>
	<div class="box-inner">
		
		<div id="apus-listing-map-sidebar" class="apus-single-listing-map" style="width: 100%; height: 300px;"></div>

		<ul class="business-info">
			<?php
			$location_friendly = get_post_meta($post->ID, '_job_location_friendly', true);
			if ( empty($location_friendly) ) {
				$location_friendly = $location;
			}
			if ( $location_friendly ) {
				if ( empty($location) ) {
					$location = $location_friendly;
				}
			?>
				<li>
					<span class="text-label"><i class="flaticon-pin"></i></span>
					<?php 
						echo apply_filters( 'the_job_location_map_link', '<a class="google_map_link" href="' . esc_url( 'https://maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ) . '" target="_blank">' . esc_html( strip_tags( $location_friendly ) ) . '</a>', $location, $post );
					?>
				</li>
				<?php
			} ?>
			<?php
			if ( ! empty( $phone ) ) :
				$show_full = listdo_get_config('listing_show_full_phone', false);
				$hide_phone = $show_full ? false : true;
				$hide_phone = apply_filters('listdo_phone_hide_number', $hide_phone );
				$add_class = '';
		        if ( $hide_phone ) {
		            $add_class = 'phone-hide';
		        }
			?>
				<li>
					<span class="text-label"><i class="flaticon-call"></i></span>
					

					<span class="phone-wrapper listing-phone <?php echo esc_attr($add_class); ?>">
						<a class="phone" href="tel:<?php echo trim($phone); ?>" itemprop="telephone"><?php echo trim($phone); ?></a>
						<?php if ( $hide_phone ) {
			                $dispnum = substr($phone, 0, (strlen($phone)-6) ) . str_repeat("*", 3);
			            ?>
			                <span class="phone-show" onclick="this.parentNode.classList.add('show');"><?php echo trim($dispnum); ?> <span class="bg-theme"><?php esc_html_e('show', 'listdo'); ?></span></span>
			            <?php } ?>
					</span>
				</li>
			<?php endif;

			if ( ! empty( $email ) ) : ?>
				<li>
					<span class="text-label"><i class="flaticon-mail"></i></span>
					<a class="listing--email" href="mailto:<?php echo trim($email); ?>" itemprop="email"><?php echo trim($email); ?></a>
				</li>
			<?php endif;
			
			if ( ! empty($website) ) {
				$website_pure = preg_replace('#^https?://#', '', rtrim(esc_url($website),'/'));
				?>
					<li>
						<span class="text-label"><i class="flaticon-unlink"></i></span>
						<a class="listing--website" href="<?php echo esc_url( $website ); ?>" itemprop="url" target="_blank" rel="nofollow"><?php echo trim($website_pure); ?></a>
					</li>
			<?php } ?>
		</ul>
		
		<?php do_action('listdo-single-listing-contact', $post); ?>

		<!-- social icons -->
		<?php

		$socials = get_post_meta( get_the_ID(), '_job_socials', true);
		if ( !empty($socials) ) {
		?>
			<h5 class="title-follow"><?php esc_html_e('Follow Us', 'listdo'); ?></h5>
			<ul class="social-icons">
				<?php foreach ($socials as $social) {
						if ( isset($social['network_url']) ) {
					?>
						<li><a href="<?php echo esc_url($social['network_url']); ?>" class="<?php echo esc_attr( substr($social['network'],7,4) ); ?>" target="_blank"><i class="<?php echo esc_attr(strtolower($social['network'])); ?>"></i></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
	<!-- form contact -->
</div>