<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $apus_author;
$data = get_userdata( $apus_author->ID );

$author_email = !empty($data->user_email) ? $data->user_email : '';
?>

<?php if ( ! empty( $_POST ) && array_key_exists( 'contact-form', $_POST ) ) : ?>
    <?php
	$is_form_filled = ! empty( $_POST['email'] ) && ! empty( $_POST['subject'] ) && ! empty( $_POST['message'] );
    if ( Listdo_Recaptcha::is_recaptcha_enabled() ) {
        $is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? Listdo_Recaptcha::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
        if ( !$is_recaptcha_valid ) {
            $is_form_filled = false;
        }
    }
	?>

    <?php if ( $is_form_filled ) : ?>
        <?php
        $email = sanitize_text_field( $_POST['email'] );
        $subject = sanitize_text_field( $_POST['subject'] );
        $message = sanitize_text_field( $_POST['message'] );
        $headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", $email, $email );

        $result = false;
        if ( function_exists('apus_wjm_send_mail') ) {
            $result = apus_wjm_send_mail( $author_email, $subject, $message, $headers );
        }
        ?>

        <?php if ( $result ) : ?>
            <div class="alert alert-success"><?php echo esc_html__( 'Your message has been successfully sent.', 'listdo' ); ?></div>
        <?php else : ?>
            <div class="alert alert-warning"><?php echo esc_html__( 'An error occurred when sending an email.', 'listdo' ); ?></div>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-warning"><?php echo esc_html__( 'Form has been not filled correctly.', 'listdo' ); ?></div>
    <?php endif; ?>
<?php endif; ?>

<?php if ( ! empty( $author_email ) ) : ?>
    <div class="widget widget-contact-profile">
        <h4 class="contact-form-title widget-title"><?php echo esc_html__( 'Contact Form', 'listdo' ); ?></h4>
        <div class="inner-content">
            <form method="post" action="?">
                <div class="form-group">
                    <input type="text" class="form-control style2" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'listdo' ); ?>">
                </div><!-- /.form-group -->

                <div class="form-group">
                    <input type="email" class="form-control style2" name="email" placeholder="<?php esc_attr_e( 'E-mail', 'listdo' ); ?>">
                </div><!-- /.form-group -->

                <div class="form-group space-30">
                    <textarea class="form-control style2" name="message" placeholder="<?php esc_attr_e( 'Message', 'listdo' ); ?>"></textarea>
                </div><!-- /.form-group -->

                <?php if ( Listdo_Recaptcha::is_recaptcha_enabled() ) { ?>
                    <div id="recaptcha-contact-form" class="ga-recaptcha space-30" data-sitekey="<?php echo esc_attr(get_option( 'job_manager_recaptcha_site_key' )); ?>"></div>
                <?php } ?>

                <button class="button btn btn-block btn-theme" name="contact-form"><?php echo esc_html__( 'Send Message', 'listdo' ); ?></button>
            </form>
        </div>
    </div>
<?php endif; ?>