<?php
/**
 * Shows the `text` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/text-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( isset( $field['value'] ) ) {
	$value = $field['value'];
} elseif (  !empty( $field['default'] ) ) {
	$value = $field['default'];
} else {
	$value = '';
}
?>
<input type="hidden" class="input-text" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"<?php if ( isset( $field['autocomplete'] ) && false === $field['autocomplete'] ) { echo ' autocomplete="off"'; } ?> id="<?php echo esc_attr( $key ); ?>" value="<?php echo intval($value); ?>" maxlength="<?php echo ! empty( $field['maxlength'] ) ? $field['maxlength'] : ''; ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>"/>

