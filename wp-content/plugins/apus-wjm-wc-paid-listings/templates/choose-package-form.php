<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$form      = WP_Job_Manager_Form_Submit_Job::instance();
$job_id    = $form->get_job_id();
$step      = $form->get_step();
$form_name = $form->form_name;

$user_id = get_current_user_id();
$user_packages = apus_wjm_wc_paid_listings_get_packages_by_user($user_id, true);
$packages = ApusWJMWCPaidListings_Submit_Form::get_products();

?>
<form method="post" id="job_package_selection">
	<?php if ( job_manager_user_can_post_job() || job_manager_user_can_edit_job( $job_id ) ) { ?>
		<div class="job_listing_packages_title">
			<input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
			<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
			<input type="hidden" name="job_manager_form" value="<?php echo esc_attr($form_name); ?>" />
			<h2><?php esc_html_e( 'Choose a package', 'apus-wjm-wc-paid-listings' ); ?></h2>
		</div>
		<div class="job_listing_types">
			<?php echo ApusWJMWCPaidListings_Template_Loader::get_template_part('user-packages', array('user_packages' => $user_packages) ); ?>
			<?php echo ApusWJMWCPaidListings_Template_Loader::get_template_part('packages', array('packages' => $packages) ); ?>
		</div>
	<?php } else { ?>
		<div class="text-warning">
			<?php esc_html_e('Please sign in with "Owner" account before accessing this page.', 'apus-wjm-wc-paid-listings'); ?>
		</div>
	<?php } ?>
</form>