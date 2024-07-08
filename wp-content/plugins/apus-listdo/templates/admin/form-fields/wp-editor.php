<?php

if ( ! empty( $field['editor-type'] ) && ( $template = locate_job_manager_template( "form-fields/{$field['editor-type']}-field.php" ) ) ) {
	require $template;
} else {
	require locate_job_manager_template( 'form-fields/wp-editor-field.php' );
}