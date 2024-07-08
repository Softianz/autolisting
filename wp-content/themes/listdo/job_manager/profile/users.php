<?php
if ( !empty($user_ids) && is_array($user_ids) ) {
	$number = listdo_get_config('user_profile_follow_number', 25);
	$tuser_ids = array_chunk($user_ids, $number);
	$page = !empty($_GET['cpage']) ? $_GET['cpage'] : 1;
	$cpage = $page - 1;
	$user_ids = isset($tuser_ids[$cpage]) ? $tuser_ids[$cpage] : array();
	?>
	<?php 
		if(!empty($member)) echo '<div class="box-user">';
	?>
		<div class="box-list-2">
			<?php foreach ($user_ids as $user_id) {
				get_job_manager_template( 'job_manager/profile/user-item.php', array('user_id' => $user_id) );
			} ?>
		</div>
	<?php 
		if(!empty($member)) echo '</div>';
	?>
	<?php if ( count($tuser_ids) > 1 ) {

		$pargs = array(
			'base' => add_query_arg( 'cpage', '%#%' ),
			'format' => '',
			'total' => count($tuser_ids),
			'current' => $page,
			'echo' => true,
			'add_fragment' => ''
		);
		listdo_paginate_links( $pargs );
	}
} else {
	?>
		<div class="text-warning"><?php esc_html_e('No users found.', 'listdo'); ?></div>
	<?php
}