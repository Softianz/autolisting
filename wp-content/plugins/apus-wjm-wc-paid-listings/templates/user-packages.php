<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( $user_packages ) : ?>
	<div class="widget widget-your-packages">
		<h2 class="widget-title hidden"><?php esc_html_e( 'Your Packages', 'apus-wjm-wc-paid-listings' ); ?></h2>
		<ul class="job_packages">
			<?php $checked = 1; foreach ( $user_packages as $key => $package ) :
				$package_count = get_post_meta($package->ID, '_package_count', true);
				$listings_limit = get_post_meta($package->ID, '_listings_limit', true);
				$listings_duration = get_post_meta($package->ID, '_listings_duration', true);
			?>
					
						<li class="user-job-package">
							<input type="radio" <?php checked( $checked, 1 ); ?> name="awjm_listing_user_package" value="<?php echo esc_attr($package->ID); ?>" id="user-package-<?php echo esc_attr($package->ID); ?>" />
							<label for="user-package-<?php echo esc_attr($package->ID); ?>"><?php echo trim($package->post_title); ?></label><br/>

							<?php
								if ( $listings_limit ) {
									printf( _n( '%s job posted out of %d', '%s jobs posted out of %d', $package_count, 'apus-wjm-wc-paid-listings' ), $package_count, $listings_limit );
								} else {
									printf( _n( '%s job posted', '%s jobs posted', $package_count, 'apus-wjm-wc-paid-listings' ), $package_count );
								}

								if ( $listings_duration ) {
									printf(  ', ' . _n( 'listed for %s day', 'listed for %s days', $listings_duration, 'apus-wjm-wc-paid-listings' ), $listings_duration );
								}

								$checked = 0;
							?>

						</li>
					

			<?php endforeach; ?>
		</ul>
		<button class="button btn btn-theme radius-50x" type="submit">
			<?php esc_html_e('Add Listing', 'apus-wjm-wc-paid-listings') ?>
		</button>
	</div>
<?php endif; ?>