<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty($field['fields']) || !is_array($field['fields']) ) {
	return;
}
?>
<div class="wc-job-manager-repeater-rows section-item-wrapper">
	<div class="repeater-fields-rows">
		<?php
		if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) : ?>
			<?php foreach ( $field['value'] as $index => $value ) : ?>
				<div class="repeater-field">
					<input type="hidden" class="repeater-row-index" name="repeater-row-<?php echo esc_attr( $key ); ?>[]" value="<?php echo absint( $index ); ?>" />
					
					<?php foreach ( $field['fields'] as $subkey => $subfield ) : ?>
						<fieldset class="fieldset-<?php echo esc_attr( $subkey ); ?>">
							<label for="<?php echo esc_attr( $subkey ); ?>"><?php echo wp_kses_post($subfield['label']) . ( !empty($subfield['required']) && $subfield['required'] ? '' : ' <small>' . esc_html__( '(optional)', 'listdo' ) . '</small>' ); ?></label>
							<div class="field">
								<?php
									// Get name and value
									$subfield['name']  = $key . '_' . $subkey . '_' . $index;
									$subfield['value'] = $value[ $subkey ];

									get_job_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
								?>
							</div>
						</fieldset>
					<?php endforeach; ?>

					<a href="#" class="delete-repeat-row"><?php esc_html_e( 'Remove', 'listdo' ); ?></a>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<a href="#" class="repeate_field_add_row text-success" data-row="<?php

		ob_start();
		?>
			<div class="repeater-field">
				<input type="hidden" class="repeater-row-index" name="repeater-row-<?php echo esc_attr( $key ); ?>[]" value="%%repeater-row-index%%" />
				
				<?php foreach ( $field['fields'] as $subkey => $subfield ) : ?>
					<fieldset class="fieldset-<?php echo esc_attr( $subkey ); ?>">
						<label for="<?php echo esc_attr( $subkey ); ?>"><?php echo wp_kses_post($subfield['label']) . ( !empty($subfield['required']) && $subfield['required'] ? '' : ' <small>' . esc_html__( '(optional)', 'listdo' ) . '</small>' ); ?></label>
						<div class="field">
							<?php
								$subfield['name']  = $key . '_' . $subkey . '_%%repeater-row-index%%';

								get_job_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
							?>
						</div>
					</fieldset>
				<?php endforeach; ?>

				<a href="#" class="delete-repeat-row text-danger"><?php esc_html_e( 'Remove', 'listdo' ); ?></a>
			</div>
		<?php
		echo esc_attr( ob_get_clean() );

	?>">+ <?php echo esc_html( ! empty( $field['add-row-text'] ) ? $field['add-row-text'] : esc_html__( 'Add Row', 'listdo' ) ); ?></a>
	<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo wp_kses_post($field['description']); ?></small><?php endif; ?>
</div>