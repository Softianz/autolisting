<?php

global $post;

$hours = get_post_meta( $post->ID, '_job_hours', true );
if ( empty($hours['day']) ) {
	return;
} else {
	$hours = $hours['day'];
}
$days = listdo_get_day_hours($hours);

if ( ! empty ( $days ) ) :
	$current = listdo_get_current_time();
	$current_day_time = $output = '';
?>
	<?php foreach ($days as $day => $times) {
			$day_time = '';
			$day_time_heading = '';
			if ( $times == 'open' ) {
				$day_time = '<span class="open-text">'.esc_html__('Open All Day', 'listdo').'</span>';
			} elseif ( $times == 'closed' ) {
				$day_time = '<span class="close-text">'.esc_html__('Closed All Day', 'listdo').'</span>';
			} elseif ( is_array($times) ) {
				foreach ($times as $time) {
					$day_time .= '<div class="time-items">';
						if ($time[0]) {
							$day_time .= '<span class="start">'.$time[0].'</span>';
						}
						if ($time[1]) {
							$day_time .= ' - <span class="end">'.$time[1].'</span>';
						}
					$day_time .= '<a href="https://www.softianz.com/autolisting2/booking?ID='.$post->ID.'&slot-booking='.$day.' ['.$time[0].'-'.$time[1].']">Appointment</a></div>';
				}
				$day_time_heading = $day_time;
			}
			$current_day_class = '';
			if ( strtolower($current['day']) === strtolower($day) ) {
				$current_day_time = $day_time_heading;
				$current_day_class = 'current';
			}
			if ( !empty($day_time) ) {
				$output .= '<div class="listing-day '.$current_day_class.'">';
					$output .= '<span class="day">'.$day.'</span>';
					$output .= '<div class="bottom-inner">'.$day_time.'</div>';
				$output .= '</div>';
			}
		}
		if ( !empty($output) ) {
	?>
			<div id="listing-hours" class="listing-hours widget">
				<h2 class="widget-title">
						<i class="flaticon-stopwatch"></i><span><?php esc_html_e('Today', 'listdo'); ?></span>

						<span class="pull-right hour-present flex">
							<?php listdo_display_time_status($post); ?>
							<?php echo wp_kses_post($current_day_time); ?>
						</span>
				</h2>
				<div class="listing-hours-inner1 flex-top-lg box-inner clearfix">
					<?php echo wp_kses_post($output); ?>

					<?php do_action('listdo-single-listing-hours', $post); ?>
				</div>
			</div>
		<?php } ?>
<?php endif; ?>