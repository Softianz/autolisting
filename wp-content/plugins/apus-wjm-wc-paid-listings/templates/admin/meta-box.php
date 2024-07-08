<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$packages = ApusWJMWCPaidListings_Submit_Form::get_products();
$_product_id = get_post_meta($post->ID, '_product_id', true);
$_order_id = get_post_meta($post->ID, '_order_id', true);
$_package_count = get_post_meta($post->ID, '_package_count', true);
$_user_id = get_post_meta($post->ID, '_user_id', true);

$_feature_listings = get_post_meta($post->ID, '_feature_listings', true);
$_listings_duration = get_post_meta($post->ID, '_listings_duration', true);
$_listings_limit = get_post_meta($post->ID, '_listings_limit', true);

$users = get_users();

?>

<div class="listing-package-general">
	<div class="form-group">
		<label><?php esc_html_e('Package', 'apus-wjm-wc-paid-listings'); ?></label>
		<select name="_product_id">
			<option value=""><?php esc_html_e('Choose a package', 'apus-wjm-wc-paid-listings'); ?></option>
			<?php foreach ($packages as $package) { ?>
				<option value="<?php echo esc_attr($package->ID); ?>" <?php selected( $_product_id, $package->ID); ?>><?php echo esc_attr($package->post_title); ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label><?php esc_html_e('Order ID', 'apus-wjm-wc-paid-listings'); ?></label>
		<input type="text" name="_order_id" value="<?php echo esc_attr($_order_id); ?>">
	</div>
	<div class="form-group">
		<label><?php esc_html_e('Package Count', 'apus-wjm-wc-paid-listings'); ?></label>
		<input type="number" name="_package_count" value="<?php echo esc_attr($_package_count); ?>">
	</div>
	<div class="form-group">
		<label><?php esc_html_e('User', 'apus-wjm-wc-paid-listings'); ?></label>
		<select name="_user_id">
			<option value=""><?php esc_html_e('Choose a user', 'apus-wjm-wc-paid-listings'); ?></option>
			<?php foreach ($users as $user) { ?>
				<option value="<?php echo esc_attr($user->ID); ?>" <?php selected( $_user_id, $user->ID); ?>><?php echo esc_attr($user->user_nicename); ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="form-group">
		<label><?php esc_html_e('Featured listings', 'apus-wjm-wc-paid-listings'); ?></label>
		<input type="checkbox" name="_feature_listings" value="yes" <?php checked($_feature_listings, 'yes'); ?>>
	</div>
	<div class="form-group">
		<label><?php esc_html_e('Listings Duration', 'apus-wjm-wc-paid-listings'); ?></label>
		<input type="text" name="_listings_duration" value="<?php echo esc_attr($_listings_duration); ?>">
	</div>
	<div class="form-group">
		<label><?php esc_html_e('Listings Limit', 'apus-wjm-wc-paid-listings'); ?></label>
		<input type="text" name="_listings_limit" value="<?php echo esc_attr($_listings_limit); ?>">
	</div>
</div>