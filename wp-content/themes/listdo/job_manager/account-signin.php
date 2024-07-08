<?php if ( is_user_logged_in() ) : ?>

	<fieldset>
		<div class="field account-sign-in">
			<?php
				$user = wp_get_current_user();
				esc_html_e( 'You are currently signed in as ', 'listdo' );
			?>
			<strong><?php echo wp_kses_post($user->user_login); ?></strong>.
			<a class="button btn btn-theme logout-link" href="<?php echo apply_filters( 'submit_job_form_logout_url', wp_logout_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Sign out', 'listdo' ); ?></a>
		</div>
	</fieldset>

<?php else :

	$account_required            = job_manager_user_requires_account();
	$registration_enabled        = job_manager_enable_registration();
	$registration_fields         = wpjm_get_registration_fields();
	$use_standard_password_email = wpjm_use_standard_password_setup_email();
	?>
	<fieldset>
		<label><?php esc_html_e( 'Have an account?', 'listdo' ); ?></label>
		<div class="field account-sign-in">
			<a class="button btn btn-theme btn-xs" href="<?php echo apply_filters( 'submit_job_form_login_url', wp_login_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Sign in', 'listdo' ); ?></a>

			<?php if ( $registration_enabled ) : ?>

				<?php printf( esc_html__( 'If you don&rsquo;t have an account you can %screate one below by entering your email address/username. Your account details will be confirmed via email.', 'listdo' ), $account_required ? '' : esc_html__( 'optionally', 'listdo' ) . ' ' ); ?>
				<?php if ( $use_standard_password_email ) : ?>
					<?php printf( esc_html__( 'Your account details will be confirmed via email.', 'listdo' ) ); ?>
				<?php endif; ?>

			<?php elseif ( $account_required ) : ?>

				<?php echo apply_filters( 'submit_job_form_login_required_message',  esc_html__('You must sign in to create a new listing.', 'listdo' ) ); ?>

			<?php endif; ?>
		</div>
	</fieldset>
	<?php
	if ( ! empty( $registration_fields ) ) {
		foreach ( $registration_fields as $key => $field ) {
			?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[ 'label' ] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field[ 'required' ] ? '' : ' <small>' . esc_html__( '(optional)', 'listdo' ) . '</small>', $field ) ); ?></label>
				<div class="field <?php echo esc_attr($field[ 'required' ] ? 'required-field draft-required' : ''); ?>">
					<?php get_job_manager_template( 'form-fields/' . $field[ 'type' ] . '-field.php', [ 'key'   => $key, 'field' => $field ] ); ?>
				</div>
			</fieldset>
			<?php
		}
		do_action( 'job_manager_register_form' );
	}
	?>
<?php endif; ?>
