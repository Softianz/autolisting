<?php
if ( !class_exists('Listdo_Bookmark') ) {
	return;
}
global $apus_author;

$args = array( 'user_id' => $apus_author->ID );
$comments = listdo_get_review_comments( $args );
$number = listdo_get_config('user_profile_reviews_number', 25);
$max_page = ceil(count($comments)/$number);
$page = !empty($_GET['cpage']) ? $_GET['cpage'] : 1;
echo '<div class="box-list">';
echo '<h3 class="title"><i class="flaticon-layers"></i>'.esc_html__('Your Reviews','listdo').'</h3>';
echo '<div class="inner">';
echo '<ul class="list-reviews">';
	wp_list_comments(array(
		'per_page' => $number,
		'page' => $page,
		'reverse_top_level' => false,
		'callback' => 'listdo_my_review'
	), $comments);
echo '</ul>';

$pargs = array(
	'base' => add_query_arg( 'cpage', '%#%' ),
	'format' => '',
	'total' => $max_page,
	'current' => $page,
	'echo' => true,
	'add_fragment' => ''
);
listdo_paginate_links( $pargs );
echo '</div>';
echo '</div>';
?>