<?php
global $wp_locale;
$days = listdo_get_days_of_week();
?>
<div class="hours-field <?php echo esc_attr( is_admin() ? 'is-admin' : ''); ?> group-fields">
	<?php if ( is_admin() ) { ?>
		<h3 class="title"><?php echo esc_html( $field['label'] ) ; ?></h3>
	<?php } ?>
	<div class="hours-field-timezone hidden">
		<?php
		$timezones = timezone_identifiers_list();
		$default_timezone = date_default_timezone_get();
		$wp_timezone = get_option('timezone_string');
		$listing_timezone = isset($field['value']) && isset($field['value']['timezone']) && in_array( $field['value']['timezone'], $timezones ) ? $field['value']['timezone'] : false;

		$current_timezone = ( $listing_timezone ? $listing_timezone : ( $wp_timezone ? $wp_timezone : $default_timezone ) );
		?>
		<label><?php esc_html_e( 'Timezone', 'listdo' ) ?></label>
		<select name="job_hours[timezone]" placeholder="<?php esc_attr_e( 'Timezone', 'listdo' ) ?>">
			<?php foreach ($timezones as $timezone): ?>
				<option value="<?php echo esc_attr( $timezone ) ?>" <?php echo trim($timezone == $current_timezone ? 'selected="selected"' : ''); ?>><?php echo esc_html( $timezone ) ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="hours-field-wrapper">
		
		<div class="list-hours">

			<?php $i = 0; foreach ( $days as $key => $day ) {
				$type = !empty($field['value']['day'][esc_attr($day)]['type']) ? $field['value']['day'][esc_attr($day)]['type'] : '';
			?>
				<div class="list">
				    
				    <div class="enter-hours-content">
				    	<div class="enter-hours-content-wrapper">
				    		<?php
				    		$form = !empty($field['value']['day'][esc_attr($day)]['from']) && is_array($field['value']['day'][esc_attr($day)]['from']) ? $field['value']['day'][esc_attr($day)]['from'] : array();
				    		$to = !empty($field['value']['day'][esc_attr($day)]['to']) && is_array($field['value']['day'][esc_attr($day)]['to']) ? $field['value']['day'][esc_attr($day)]['to'] : array();
				    		if ( !empty($form) ) {
				    			
			    				?>
			    				<div class="enter-hours-content-item ">
									<div class="group-field-item-content ">

				    					<div class="row flex-middle-lg">
				    						<div class="left-inner col-lg-1 col-sm-4 col-xs-12">
												<a data-toggle="tab" href="#hours-tab-<?php echo esc_attr($day); ?>"><?php echo trim($wp_locale->get_weekday( $day )); ?></a>
											</div>
							    			<div class="col-lg-6 col-sm-8 col-xs-12 enter-hours-wrapper">
							    				<?php
							    				foreach ($form as $key => $form_val) {
							    					$to_val = !empty($to[$key]) ? $to[$key] : '';
							    				?>
								    				<div class="enter-hours-item-inner">
								    					<div class="row">
										    				<div class="col-lg-6 col-xs-6">
										    					<div class="wrapper-select">
											    				<select class="select-job-hour-from" name="job_hours[day][<?php echo esc_attr($day); ?>][from][]" placeholder="<?php esc_attr_e( 'From', 'listdo' ) ?>">
																	<option value=""><?php esc_html_e( 'From', 'listdo' ) ?></option>
																	<?php foreach (range(0, 86399, 900) as $time) {
																		$value = gmdate( 'H:i', $time);
																	?>
																		<option value="<?php echo esc_attr( $value ) ?>" <?php echo trim($value == $form_val ? 'selected="selected"' : ''); ?>><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
																	<?php }
																		$value = gmdate( 'H:i', 86399);
																	?>
																	<option value="<?php echo esc_attr( $value ) ?>" <?php echo trim($value == $form_val ? 'selected="selected"' : ''); ?>><?php echo esc_html( gmdate( get_option( 'time_format' ), 86399 ) ) ?></option>
																</select>
											    				</div>
											    			</div>
										    				<div class="col-lg-6 col-xs-6">
										    					<div class="wrapper-select">
											    				<select class="select-job-hour-to" name="job_hours[day][<?php echo esc_attr($day); ?>][to][]" placeholder="<?php esc_attr_e( 'To', 'listdo' ) ?>">
																	<option value=""><?php esc_html_e( 'To', 'listdo' ) ?></option>
																	<?php foreach (range(0, 86399, 900) as $time) {
																		$value = gmdate( 'H:i', $time);
																	?>
																		<option value="<?php echo esc_attr( $value ) ?>" <?php echo trim($value == $to_val ? 'selected="selected"' : ''); ?>><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
																	<?php }
																		$value = gmdate( 'H:i', 86399);
																	?>
																	<option value="<?php echo esc_attr( $value ) ?>" <?php echo trim($value == $to_val ? 'selected="selected"' : ''); ?>><?php echo esc_html( gmdate( get_option( 'time_format' ), 86399 ) ) ?></option>
																</select>
										    					</div>
										    				</div>
										    			</div>
								    				</div>
								    			<?php } ?>
							    			</div>
						    			
							    			<div class="hours-last col-lg-5 col-xs-12">
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="enter_hours" <?php echo trim(empty($type) || $type == 'enter_hours' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Enter Hours', 'listdo'); ?></label>
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="open_all_day" <?php echo trim($type == 'open_all_day' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Open All Days', 'listdo'); ?></label>
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="closed_all_day" <?php echo trim($type == 'closed_all_day' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Closed All Days', 'listdo'); ?></label>
										    </div>
									    </div>
					    			</div>
					    		</div>
			    				<?php
				    		} else {
				    		?>
					    		<div class="enter-hours-content-item">
									<div class="group-field-item-content">
						    			<div class="row flex-middle-lg">
						    				<div class="left-inner col-lg-1 col-sm-4 col-xs-12">
												<a data-toggle="tab" href="#hours-tab-<?php echo esc_attr($day); ?>"><?php echo trim($wp_locale->get_weekday( $day )); ?></a>
											</div>
							    			<div class="col-lg-6 col-sm-8 col-xs-12 enter-hours-wrapper">
							    				<div class="enter-hours-item-inner">
							    					<div class="row">
									    				<div class="col-lg-6 col-xs-6 ">
									    					<div class="wrapper-select">
										    				<select class="select-job-hour-from" name="job_hours[day][<?php echo esc_attr($day); ?>][from][]" placeholder="<?php esc_attr_e( 'From', 'listdo' ) ?>">
																<option value=""><?php esc_html_e( 'From', 'listdo' ) ?></option>
																<?php foreach (range(0, 86399, 900) as $time) {
																	$value = gmdate( 'H:i', $time);
																?>
																	<option value="<?php echo esc_attr( $value ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
																<?php }
																	$value = gmdate( 'H:i', 86399);
																?>
																<option value="<?php echo esc_attr( $value ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), '86399' ) ) ?></option>
															</select>
															</div>
														</div>
									    				<div class="col-lg-6 col-xs-6">
									    					<div class="wrapper-select">
										    				<select class="select-job-hour-to" name="job_hours[day][<?php echo esc_attr($day); ?>][to][]" placeholder="<?php esc_attr_e( 'To', 'listdo' ) ?>">
																<option value=""><?php esc_html_e( 'To', 'listdo' ) ?></option>
																<?php foreach (range(0, 86399, 900) as $time) {
																	$value = gmdate( 'H:i', $time);

																?>
																	<option value="<?php echo esc_attr( $value ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
																<?php }

																	$value = gmdate( 'H:i', 86399);
																?>
																<option value="<?php echo esc_attr( $value ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), '86399' ) ) ?></option>
															</select>
															</div>
														</div>
													</div>
												</div>
							    			</div>
							    			<div class="hours-last col-lg-5 col-xs-12">
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="enter_hours" <?php echo trim(empty($type) || $type == 'enter_hours' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Enter Hours', 'listdo'); ?></label>
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="open_all_day" <?php echo trim($type == 'open_all_day' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Open All Days', 'listdo'); ?></label>
										    	<label><input type="radio" name="job_hours[day][<?php echo esc_attr($day); ?>][type]" value="closed_all_day" <?php echo trim($type == 'closed_all_day' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Closed All Days', 'listdo'); ?></label>
										    </div>
						    			</div>
					    			</div>
					    		</div>
					    	<?php } ?>
				    	</div>
				    	<div class="row">
				    		<div class="left-inner col-lg-1 visible-lg">
				    		</div>
				    		<div class="left-inner col-lg-11 col-xs-12">
				    			<div class="bottom-action-hour">
							    	<a class="add-new-hour btn-action button text-success" href="javascript:void(0);"><?php esc_html_e( 'Add New', 'listdo' ); ?></a>
									<a class="remove-hour btn-action button text-danger" href="javascript:void(0);"><?php esc_html_e( 'Remove', 'listdo' ); ?></a>
								</div>
				    		</div>
						</div>
				    </div>
			  	</div>
			<?php $i++; } ?>
		</div>
	</div>
</div>