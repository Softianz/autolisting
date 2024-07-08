<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php wc_print_notices(); ?>

<form method="post" class="lost_reset_password">

    <p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'listdo' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

    <p class="form-group">
        <label for="user_login"><?php esc_html_e( 'Username or email', 'listdo' ); ?></label>
        <input class="input-text form-control" type="text" name="user_login" id="user_login" autocomplete="username" />
    </p>


    <?php do_action( 'woocommerce_lostpassword_form' ); ?>

    <p class="form-group">
        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'listdo' ); ?>"><?php esc_html_e( 'Reset password', 'listdo' ); ?></button>
    </p>

    <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

</form>