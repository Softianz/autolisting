<?php

function listdo_child_enqueue_styles()
{
	wp_enqueue_style('listdo-child-style', get_stylesheet_uri());
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'listdo_child_enqueue_styles', 100);

// Yogesh Sharmatech
function ys_ajaxurl()
{
	echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('wp_head', 'ys_ajaxurl');

add_action('wp', 'add_single_listing_meeting', 10);
function add_single_listing_meeting()
{
	global $apus_options;
	$apus_options['listing_single_sort_sidebar']['enabled']['schedule-meeting'] = 'Schedule Meeting';
	return $apus_options;
}
add_action('wp_ajax_get_listing_time_slot', 'get_listing_time_slot');
add_action('wp_ajax_nopriv_get_listing_time_slot', 'get_listing_time_slot');
function get_listing_time_slot()
{
	$post_id = $_POST['post_id'];
	$date = $_POST['date'];
	$day  = date('l', strtotime($date));
	$hours = get_post_meta($post_id, '_job_hours', true);
	if (empty($hours['day'])) {
		return;
	}
	$days = listdo_get_day_hours($hours['day']);
	$times = $days[$day];
	$day_time = '';
	$status  = true;
	if ($times == 'open') {
		$day_time = '<span class="open-text">' . esc_html__('Open All Day', 'listdo') . '</span>';
	} elseif ($times == 'closed') {
		$status  = false;
		$day_time = '<span class="close-text">' . esc_html__('Closed All Day', 'listdo') . '</span>';
	} elseif (is_array($times)) {
		$startTime = isset($times[0][0]) ? $times[0][0] : '';
		$endTime = isset($times[0][1]) ? $times[0][1] : '';
		$day_time = '<span class="open-text">You can choose time between ' . $startTime . ' to ' . $endTime . '</span>';
	}

	$response = array('status' => $status, 'data' =>  $day_time);
	wp_send_json($response);
	die;
}
// Schedule Meeting
add_action('wp_ajax_submit_meeting_schedule', 'submit_meeting_schedule');
add_action('wp_ajax_nopriv_submit_meeting_schedule', 'submit_meeting_schedule');
function submit_meeting_schedule()
{
	if (!is_user_logged_in()) {
		$response = array('status' => false, 'msg' =>  '<span class="close-text">' . esc_html__('Please login.', 'listdo') . '</span>');
		wp_send_json($response);
		die;
	}
	global $wpdb;
	$status = false;
	$rmessage = '<span class="close-text">' . esc_html__('Someting wrong! try latter ', 'listdo') . '</span>';
	parse_str($_POST['data'], $params);
	$insertData = [
		'user_id'    => get_current_user_id(),
		'listing_id' => $params['post_id'],
		'author_id'  => $params['post_author'],
		'schedule'    => $params['meeting_date'] . ' ' . $params['meeting_time'],
		'schdule_date' => $params['meeting_date'],
		'schdule_time' => $params['meeting_time'],
	];
	if (isset($params['offer_price'])) {
		$insertData['price'] = $params['offer_price'];
	}
	$ok = $wpdb->insert($wpdb->prefix . 'meetings', $insertData);
	if ($ok) {
		$author = get_user_by('id', $params['post_author']);
		$user = get_user_by('id', get_current_user_id());
		$userName = $user->display_name;
		$authorEmail = $author->user_email;
		$post = get_post($params['post_id']);
		$postTitle = $post->post_title;
		$dateTime = $params['meeting_date'] . ' @ ' . $params['meeting_time'];
		$message = "Hello , You have new meeting for listing $postTitle at  $dateTime with $userName .";
		wp_mail($authorEmail, 'New Meeting Schedule', $message);
		$rmessage = '<span class="open-text">' . esc_html__('Thank you.', 'listdo') . '</span>';
		$status = true;
	}
	$response = array('status' => $status, 'msg' =>  $rmessage);
	wp_send_json($response);
	die;
}
// Cron for send mail for metting
add_action('send_mail_5_min_before_metting', 'send_mail_5_min_before_metting_callback');
add_action('wp', 'send_mail_5_min_before_metting_callback');
function send_mail_5_min_before_metting_callback()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'meetings';
	$meetings = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE (schedule BETWEEN NOW() AND NOW() + INTERVAL 15 MINUTE) AND status = 'Active' AND is_mail = 0");
	if ($meetings) {
		foreach ($meetings as $meeting) {
			$user = get_user_by('id', $meeting->user_id);
			$author = get_user_by('id', $meeting->author_id);
			$schedule = $meeting->schedule;
			$userName = $user->display_name;
			$AuthorName = $author->display_name;

			$message = " Hello $userName, Your meeting with $AuthorName will schedule on $schedule.";
			$message1 = " Hello $AuthorName, Your meeting with $userName will schedule on $schedule.";
			$wpdb->update($table_name, ['is_mail' => 1], ['id' => $meeting->id]);
			wp_mail($user->user_email, 'Meeting Schedule Status', $message);
			wp_mail($author->user_email, 'Meeting Schedule Status', $message1);
		}
	}
}
function my_custom_cron_schedule()
{

	if (!wp_next_scheduled('send_mail_5_min_before_metting')) {
		$res = wp_schedule_event(time(), 'every_minute', 'send_mail_5_min_before_metting');
	}
}
add_action('wp', 'my_custom_cron_schedule');