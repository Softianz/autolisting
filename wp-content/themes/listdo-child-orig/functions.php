<?php

function listdo_child_enqueue_styles() {
	wp_enqueue_style( 'listdo-child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'listdo_child_enqueue_styles', 100 );