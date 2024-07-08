<?php
global $wp_locale, $thepostid;
?>
<div class="heading-field">
	<h3 class="heading">
		<?php if ( !empty($field['icon']) ) { ?>
			<i class="<?php echo esc_attr($field['icon']); ?>"></i>
		<?php } ?>
		<?php echo esc_html( $field['label'] ) ; ?>
	</h3>
</div>