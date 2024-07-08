<?php
/**
 * The template for displaying the WP Job Manager frontend listing submission form
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $job_manager;
?>
<div class="clearfix">
	<h3 class="user-name"><?php echo esc_html__('Add Listing','listdo'); ?></h3>
	<form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">
		<?php do_action( 'submit_job_form_start' ); ?>
		<?php if ( apply_filters( 'submit_job_form_show_signin', true ) ) : ?>

			<?php get_job_manager_template( 'account-signin.php' ); ?>

		<?php endif; ?>

		<?php if ( job_manager_user_can_post_job() || job_manager_user_can_edit_job( $job_id ) ) : ?>

			<!-- Job Information Fields -->
			<?php do_action( 'submit_job_form_job_fields_start' );

			$all_fields = $job_fields;
			if ( $company_fields ) {
				$all_fields = $job_fields + $company_fields;
			}
			uasort( $all_fields, 'listdo_sort_array_by_priority' );
			
			if ( !empty($all_fields) ) {
				$i = 1;

				foreach ( $all_fields as $key => $field ) {
			?>
					<?php 
						if ( $i == 1 ) {
							echo '<div class="box-list-2 column-'.(!empty($field['number_columns']) ? $field['number_columns'] : '1').'">';
						} elseif ( $field['type'] == 'heading' ) {
							echo '</div></div><div class="box-list-2 column-'.(!empty($field['number_columns']) ? $field['number_columns'] : '1').'">';
						}
					?>
					<fieldset class="fieldset-<?php echo esc_attr( $key.' '.$field['type'] ); ?> <?php echo esc_attr($field['type'] == 'hidden' ? 'hidden' : ''); ?>">
						<?php if ( !empty($field['type']) && $field['type'] !== 'heading' && $field['type'] !== 'listdo-location' ) { ?>
							<label for="<?php echo esc_attr( $key ); ?>">
								<?php
								if ( isset( $field['label'] ) ) {
									echo trim($field['label']);
								}
								echo apply_filters( 'submit_job_form_required_label', (isset($field['required'])&&$field['required']) ? ' <span class="text-red">*</span>' : ' ', $field );
								?>
							</label>
						<?php } ?>
						<div class="field <?php echo ( isset($field['required']) && $field['required'] ) ? 'required-field' : ''; ?>">
							<?php
							if ( isset( $field['type'] ) ) {
								get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) );
							} ?>
						</div>
					</fieldset>
					<?php
						if ( $i == 1 ) {
							echo '<div class="box-list-2-inner">';
						} elseif ( $field['type'] == 'heading' ) {
							echo '<div class="box-list-2-inner">';
						}

						if( count($all_fields) == $i ){
							echo '</div></div>';
						}
						$i++;
					?>
			<?php }
			} ?>

			<?php do_action( 'submit_job_form_job_fields_end' ); ?>

			<!-- Company Information Fields -->
			<?php if ( $company_fields ) : ?>
				<?php do_action( 'submit_job_form_company_fields_start' ); ?>
				<?php do_action( 'submit_job_form_company_fields_end' ); ?>
			<?php endif; ?>

			<?php do_action( 'submit_job_form_end' ); ?>
			
			<p class="clearfix">
				<input type="hidden" name="job_manager_form" value="<?php echo trim($form); ?>" />
				<input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
				<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />

				<input type="submit" name="submit_job" class="button btn-submit btn btn-theme" value="<?php echo esc_html__('Save & Preview','listdo'); ?>" />
			</p>

		<?php else : ?>

			<?php do_action( 'submit_job_form_disabled' ); ?>

		<?php endif; ?>
	</form>
</div>