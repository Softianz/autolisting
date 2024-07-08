<h3 class="title-account"><?php echo esc_html__('Create an account','listdo'); ?></h3>
<?php if ( defined('LISTDO_DEMO_MODE') && LISTDO_DEMO_MODE ) { ?>
  <div class="sign-in-demo-notice">
    Username: <strong>demo</strong><br>
    Password: <strong>demo</strong>
  </div>
<?php } ?>
<div class="form-register">
  <div class="inner">
  	<div class="container-form">
          <form name="apusRegisterForm" method="post" class="apus-register-form">
              <div id="apus-reg-loader-info" class="apus-loader hidden">
                  <span><?php esc_html_e('Please wait ...', 'listdo'); ?></span>
              </div>
              <div id="apus-register-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
              <div id="apus-mail-alert" class="alert alert-danger" role="alert" style="display:none;"></div>

             	<div class="form-group">
                	<label class="hidden" for="username"><?php esc_html_e('Username', 'listdo'); ?></label>
                	<sup class="apus-required-field hidden">*</sup>
                	<input type="text" class="form-control" name="username" id="username" placeholder="<?php esc_attr_e("Enter Username",'listdo'); ?>">
            	</div>
            	<div class="form-group">
                	<label class="hidden" for="reg-email"><?php esc_html_e('Email', 'listdo'); ?></label>
                	<sup class="apus-required-field hidden">*</sup>
                	<input type="text" class="form-control" name="email" id="reg-email" placeholder="<?php esc_attr_e("Enter Email",'listdo'); ?>">
            	</div>
              <div class="form-group">
                  <label class="hidden" for="password"><?php esc_html_e('Password', 'listdo'); ?></label>
                  <sup class="apus-required-field hidden">*</sup>
                  <input type="password" class="form-control" name="password" id="password" placeholder="<?php esc_attr_e("Enter Password",'listdo'); ?>">
              </div>
              <div class="form-group space-bottom-30">
                  <label class="hidden" for="confirmpassword"><?php esc_html_e('Confirm Password', 'listdo'); ?></label>
                  <sup class="apus-required-field hidden">*</sup>
                  <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="<?php esc_attr_e("Confirm Password",'listdo'); ?>">
              </div>
              
              <?php wp_nonce_field('ajax-apus-register-nonce', 'security_register'); ?>

              <?php if ( Listdo_Recaptcha::is_recaptcha_enabled() && listdo_get_config('use_recaptcha_register_form', true) ) { ?>
                    <div id="recaptcha-register-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(get_option( 'job_manager_recaptcha_site_key' )); ?>"></div>
              <?php } ?>

              <div class="form-group clear-submit">
                <button type="submit" class="btn btn-action-login btn-block" name="submitRegister">
                    <?php echo esc_html__('Register now', 'listdo'); ?>
                </button>
              </div>

              <?php do_action('register_form'); ?>
          </form>
    </div>
	</div>
</div>
<div class="bottom-login text-center">
  <?php echo esc_html__('Already have an account?','listdo') ?>
</div>