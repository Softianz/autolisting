<?php
/**
 * Job listing preview when submitting job listings.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-preview.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

if ( $form->get_job_id() ) {
	$post        = get_post( $form->get_job_id() );
	setup_postdata( $post );
	
	$post->post_status = 'preview';
	?>
	<form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
		<?php
		/**
		 * Fires at the top of the preview job form.
		 *
		 * @since 1.32.2
		 */
		do_action( 'preview_job_form_start' );
		?>
		<div class="job_listing_preview_title">
			<input type="submit" name="continue" id="job_preview_submit_button" class="button job-manager-button-submit-listing btn btn-danger btn-sm" value="<?php echo apply_filters( 'submit_job_step_preview_submit_text', esc_html__( 'Submit Listing', 'listdo' ) ); ?>"/>
			<input type="submit" name="edit_job" class="button job-manager-button-edit-listing btn btn-theme btn-sm" value="<?php esc_html_e( 'Edit listing', 'listdo' ); ?>"/>

			<input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
			<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
			<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form->get_form_name() ); ?>" />
			
			<h2>
				<?php esc_html_e( 'Preview', 'listdo' ); ?>
			</h2>
		</div>
		<?php
		/**
		 * Fires at the bottom of the preview job form.
		 *
		 * @since 1.32.2
		 */
		do_action( 'preview_job_form_end' );
		?>
	</form>
	<?php
		global $listdo_preview;
		$listdo_preview = 'preview';
		get_template_part( 'job_manager/single/layouts/v1' );
	
	wp_reset_postdata();
}