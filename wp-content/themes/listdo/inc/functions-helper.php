<?php

if ( ! function_exists( 'listdo_body_classes' ) ) {
	function listdo_body_classes( $classes ) {
		global $post;
		if ( is_page() && is_object($post) ) {
			$class = get_post_meta( $post->ID, 'apus_page_extra_class', true );
			if ( !empty($class) ) {
				$classes[] = trim($class);
			}

			if ( listdo_is_wp_job_manager_activated() && ($post->ID == get_option('job_manager_jobs_page_id') || basename( get_page_template() ) == 'page-listing.php') ) {
				$version = listdo_get_listing_archive_version();
				$halfmaps = listdo_get_listing_all_half_map_version();
				if ( in_array($version, $halfmaps) ) {
					$classes[] = 'no-footer fix-header';
				}
				$sidebar_position = listdo_get_archive_layout();
				if ($version == 'default' && $sidebar_position == 'main') {
					$classes[] = 'listings-default-main';
				}
				
			} else {
				$transparent = get_post_meta( $post->ID, 'apus_page_header_transparent', true );
				if ( $transparent == 'yes' ) {
					$classes[] = 'header_transparent';
				}
			}
		} elseif ( is_singular('post') ) {
			$classes[] = 'header_transparent';
		}

		if( listdo_check_breadcrumbs() == true ){
			$classes[] = 'header_transparent';
		}

		if ( is_author() ) {
			$classes[] = 'header_transparent';
		}

		if ( listdo_get_config('image_lazy_loading') ) {
			$classes[] = 'image-lazy-loading';
		}
		if ( listdo_get_config('preload', true) ) {
			$classes[] = 'apus-body-loading';
		}
		// no breadscrumb
		$post_type = get_query_var('post_type');
		if ( is_singular('post') || is_category() ) {
			$show = listdo_get_config('show_blog_breadcrumbs', true);
			if ( !$show  ) {
				$classes[] = 'no-breadscrumb';
			}
		} elseif ( is_post_type_archive('job_listing') || is_tax('job_listing_tag') || is_tax('job_listing_amenity') || is_tax('job_listing_category') || is_tax('job_listing_region') || is_tax('job_listing_type') || ( is_search() && $post_type == 'job_listing' )) {
			$classes[] = ' archive-jobs-listings ';
			$show_bread = listdo_get_config('show_listing_breadcrumbs', true);
			$show = true;
			if ( !is_singular('job_listing') ) {
				$version = listdo_get_listing_archive_version();
				$halfmaps = listdo_get_listing_all_half_map_version();
				if ( in_array($version, $halfmaps) ) {
					$show = false;
					$classes[] = 'no-footer fix-header';
				} else {
					$classes[] = 'listings-default-layout';
				}
				$sidebar_position = listdo_get_archive_layout();
				if ($version == 'default' && $sidebar_position == 'main') {
					$classes[] = 'listings-default-main';
				}
			} else {
				$show = false;
				
			}
			if ( !$show_bread || !$show  ) {
				$classes[] = 'no-breadscrumb';
			}

		} elseif ( is_singular('job_listing') ) {
			if ( listdo_get_config('listing_single_transparent_header', true) ) {
				$classes[] = 'header_transparent';
			}
		} elseif ( is_404() ) {
			if ( listdo_get_config('404_transparent_header', true) ) {
				$classes[] = 'header_transparent';
			}
		}
		return $classes;
	}
	add_filter( 'body_class', 'listdo_body_classes' );
}

if ( ! function_exists( 'listdo_get_shortcode_regex' ) ) {
	function listdo_get_shortcode_regex( $tagregexp = '' ) {
		// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
		// Also, see shortcode_unautop() and shortcode.js.
		return
			'\\['                                // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($tagregexp)"                     // 2: Shortcode name
			. '(?![\\w-])'                       // Not followed by word character or hyphen
			. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
			. '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			. '(?:'
			. '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			. '[^\\]\\/]*'               // Not a closing bracket or forward slash
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)'                        // 4: Self closing tag ...
			. '\\]'                          // ... and closing bracket
			. '|'
			. '\\]'                          // Closing bracket
			. '(?:'
			. '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			. '[^\\[]*+'             // Not an opening bracket
			. '(?:'
			. '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			. '[^\\[]*+'         // Not an opening bracket
			. ')*+'
			. ')'
			. '\\[\\/\\2\\]'             // Closing shortcode tag
			. ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}
}

if ( ! function_exists( 'listdo_tagregexp' ) ) {
	function listdo_tagregexp() {
		return apply_filters( 'listdo_custom_tagregexp', 'video|audio|playlist|video-playlist|embed|listdo_media' );
	}
}

if ( !function_exists('listdo_get_header_layouts') ) {
	function listdo_get_header_layouts() {
		$headers = array();
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'apus_header',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$headers[$post->post_name] = $post->post_title;
		}
		return $headers;
	}
}

if ( !function_exists('listdo_get_header_layout') ) {
	function listdo_get_header_layout() {
		global $post;
		if ( is_page() && is_object($post) && isset($post->ID) ) {
			global $post;
			$header = get_post_meta( $post->ID, 'apus_page_header_type', true );
			if ( empty($header) || $header == 'global' ) {
				return listdo_get_config('header_type');
			}
			return $header;
		}
		return listdo_get_config('header_type');
	}
	add_filter( 'listdo_get_header_layout', 'listdo_get_header_layout' );
}

if ( !function_exists('listdo_display_header_builder') ) {
	function listdo_display_header_builder($header_slug) {
		$args = array(
			'name'        => $header_slug,
			'post_type'   => 'apus_header',
			'post_status' => 'publish',
			'numberposts' => 1
		);
		$posts = get_posts($args);
		foreach ( $posts as $post ) {
			$classes = array('apus-header');
			if ( listdo_get_config('separate_header_mobile', true) ) {
				$classes[] = 'visible-lg';
			}
			$classes[] = $post->post_name.'-'.$post->ID;
			if ( !listdo_get_config('keep_header') ) {
				$classes[] = 'no-sticky';
			}
			echo '<div id="apus-header" class="'.esc_attr(implode(' ', $classes)).'">';
			if ( listdo_get_config('keep_header') ) {
		        echo '<div class="main-sticky-header">';
		    }
				echo apply_filters( 'listdo_generate_post_builder', do_shortcode( $post->post_content ), $post, $post->ID);
			if ( listdo_get_config('keep_header') ) {
				echo '</div>';
		    }
			echo '</div>';
		}
	}
}

if ( !function_exists('listdo_get_footer_layouts') ) {
	function listdo_get_footer_layouts() {
		$footers = array();
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'apus_footer',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$footers[$post->post_name] = $post->post_title;
		}
		return $footers;
	}
}

if ( !function_exists('listdo_get_footer_layout') ) {
	function listdo_get_footer_layout() {
		if ( is_page() ) {
			global $post;
			$footer = '';
			if ( is_object($post) && isset($post->ID) ) {
				$footer = get_post_meta( $post->ID, 'apus_page_footer_type', true );
				if ( empty($footer) || $footer == 'global' ) {
					return listdo_get_config('footer_type', '');
				}
			}
			return $footer;
		}
		return listdo_get_config('footer_type', '');
	}
	add_filter('listdo_get_footer_layout', 'listdo_get_footer_layout');
}

if ( !function_exists('listdo_display_footer_builder') ) {
	function listdo_display_footer_builder($footer_slug) {
		$show_footer_desktop_mobile = listdo_get_config('show_footer_desktop_mobile', false);
		$args = array(
			'name'        => $footer_slug,
			'post_type'   => 'apus_footer',
			'post_status' => 'publish',
			'numberposts' => 1
		);
		$posts = get_posts($args);
		foreach ( $posts as $post ) {
			$classes = array('apus-footer footer-builder-wrapper');
			if ( !$show_footer_desktop_mobile ) {
				$classes[] = '';
			}
			$classes[] = $post->post_name;


			echo '<div id="apus-footer-inner" class="'.esc_attr(implode(' ', $classes)).'">';
			echo '<div class="apus-footer-inner">';
			echo apply_filters( 'listdo_generate_post_builder', do_shortcode( $post->post_content ), $post, $post->ID);
			echo '</div>';
			echo '</div>';
		}
	}
}

if ( !function_exists('listdo_blog_content_class') ) {
	function listdo_blog_content_class( $class ) {
		$page = 'archive';
		if ( is_singular( 'post' ) ) {
            $page = 'single';
        }
		if ( listdo_get_config('blog_'.$page.'_fullwidth') ) {
			return 'container-fluid no-padding';
		}
		return $class;
	}
}
add_filter( 'listdo_blog_content_class', 'listdo_blog_content_class', 1 , 1  );


if ( !function_exists('listdo_get_blog_layout_configs') ) {
	function listdo_get_blog_layout_configs() {
		$page = 'archive';
		$addition_class = '';
		if ( is_singular( 'post' ) ) {
            $page = 'single';
            $addition_class = 'main-content-only';
        }
		$left = listdo_get_config('blog_'.$page.'_left_sidebar');
		$right = listdo_get_config('blog_'.$page.'_right_sidebar');

		switch ( listdo_get_config('blog_'.$page.'_layout') ) {
		 	case 'left-main':
		 		if ( is_active_sidebar( $left ) ) {
			 		$configs['left'] = array( 'sidebar' => $left, 'class' => 'col-md-4 sidebar-blog col-sm-12 col-xs-12'  );
			 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12 pull-right' );
			 	}
		 		break;
		 	case 'main-right':
		 		if ( is_active_sidebar( $right ) ) {
			 		$configs['right'] = array( 'sidebar' => $right,  'class' => 'col-md-4 sidebar-blog col-sm-12 col-xs-12 pull-right' ); 
			 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
			 	}
		 		break;
	 		case 'main':
	 			$configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12 '.$addition_class );
	 			break;
		 	default:
		 		if ( is_active_sidebar( 'sidebar-default' ) ) {
			 		$configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-4 sidebar-blog col-sm-12 col-xs-12 pull-right' ); 
			 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
			 	}
		 		break;
		}

		if ( empty($configs) ) {
			if ( is_active_sidebar( 'sidebar-default' ) ) {
				$configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-4 sidebar-blog col-sm-12 col-xs-12 pull-right' ); 
		 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
		 	} else {
		 		$configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12 '.$addition_class );
		 	}
		}

		return $configs; 
	}
}

if ( !function_exists('listdo_page_content_class') ) {
	function listdo_page_content_class( $class ) {
		global $post;
		if (is_object($post)) {
			$fullwidth = get_post_meta( $post->ID, 'apus_page_fullwidth', true );

			if ( !$fullwidth || $fullwidth == 'no' ) {
				return $class;
			}
		}
		return 'container-fluid';
	}
}
add_filter( 'listdo_page_content_class', 'listdo_page_content_class', 1 , 1  );

if ( !function_exists('listdo_get_page_layout_configs') ) {
	function listdo_get_page_layout_configs() {
		global $post;
		if ( is_object($post) ) {
			$sidebar = get_post_meta( $post->ID, 'apus_page_sidebar', true );

			switch ( get_post_meta( $post->ID, 'apus_page_layout', true ) ) {
			 	case 'left-main':
			 		if ( is_active_sidebar( $sidebar ) ) {
				 		$configs['left'] = array( 'sidebar' => $sidebar, 'class' => 'col-md-4 col-sm-12 col-xs-12'  );
				 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
				 	}
			 		break;
			 	case 'main-right':
			 		if ( is_active_sidebar( $sidebar ) ) {
				 		$configs['right'] = array( 'sidebar' => $sidebar,  'class' => 'col-md-4 col-sm-12 col-xs-12' ); 
				 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
				 	}
			 		break;
		 		case 'main':
		 			$configs['main'] = array( 'class' => 'col-xs-12' );
		 			break;
			 	default:
			 		if ( listdo_is_woocommerce_activated() && (is_cart() || is_checkout()) ) {
			 			$configs['main'] = array( 'class' => 'col-xs-12' );
			 		} elseif ( is_active_sidebar( 'sidebar-default' ) ) {
				 		$configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-4 col-sm-12 col-xs-12 pull-right' ); 
			 			$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
			 		}
			 		break;
			}

			if ( empty($configs) ) {
				if ( is_active_sidebar( 'sidebar-default' ) ) {
					$configs['right'] = array( 'sidebar' => 'sidebar-default',  'class' => 'col-md-4 col-sm-12 col-xs-12 pull-right' ); 
			 		$configs['main'] = array( 'class' => 'col-md-8 col-sm-12 col-xs-12' );
			 	} else {
			 		$configs['main'] = array( 'class' => 'col-xs-12 full-default' );
			 	}
			}
		} else {
			$configs['main'] = array( 'class' => 'col-md-12 col-xs-12' );
		}
		return $configs; 
	}
}

if ( !function_exists('listdo_page_header_layout') ) {
	function listdo_page_header_layout() {
		global $post;
		$header = get_post_meta( $post->ID, 'apus_page_header_type', true );
		if ( empty($header) || $header == 'global' ) {
			return listdo_get_config('header_type');
		}
		return $header;
	}
}

if ( !function_exists( 'listdo_random_key' ) ) {
    function listdo_random_key($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $return = '';
        for ($i = 0; $i < $length; $i++) {
            $return .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $return;
    }
}

if ( !function_exists('listdo_substring') ) {
    function listdo_substring($string, $limit, $afterlimit = '[...]') {
        if ( empty($string) ) {
        	return $string;
        }
       	$string = explode(' ', strip_tags( $string ), $limit);

        if (count($string) >= $limit) {
            array_pop($string);
            $string = implode(" ", $string) .' '. $afterlimit;
        } else {
            $string = implode(" ", $string);
        }
        $string = preg_replace('`[[^]]*]`','',$string);
        return strip_shortcodes( $string );
    }
}

function listdo_get_user_url($user_id, $nicename, $tags = array()) {
	$url = get_author_posts_url( $user_id, $nicename );
	if ( !empty($tags) ) {
		foreach ($tags as $tag => $value) {
			$url = add_query_arg( $tag, $value, remove_query_arg( $tag, $url ) );
		}
	}
	return apply_filters('listdo_get_user_url', $url, $user_id, $tags);
}


function listdo_is_apus_framework_activated() {
	return defined('APUS_FRAMEWORK_VERSION') ? true : false;
}

function listdo_is_cmb2_activated() {
	return defined('CMB2_LOADED') ? true : false;
}

function listdo_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

function listdo_is_revslider_activated() {
	return function_exists( 'putRevSlider' );
}

function listdo_is_dokan_activated() {
	return class_exists( 'WeDevs_Dokan' ) ? true : false;
}

function listdo_is_wp_job_manager_activated() {
	return class_exists( 'WP_Job_Manager' ) ? true : false;
}

function listdo_is_apus_wc_paid_listings_activated() {
	return class_exists( 'ApusWJMWCPaidListings' ) ? true : false;
}

function listdo_is_mailchimp_activated() {
	return class_exists( 'MC4WP_Form_Manager' ) ? true : false;
}

function listdo_is_wp_private_message() {
	return class_exists( 'WP_Private_Message' ) ? true : false;
}

function listdo_marital_status_defaults() {
	return apply_filters( 'listdo_marital_status_defaults', array(
		'single' => esc_html__('Single', 'listdo'),
		'engaged' => esc_html__('Engaged', 'listdo'),
		'married' => esc_html__('Married', 'listdo'),
		'separated' => esc_html__('Separated', 'listdo'),
		'divorced' => esc_html__('Divorced', 'listdo'),
		'widow' => esc_html__('Widow', 'listdo'),
		'widower' => esc_html__('Widower', 'listdo'),
	) );
}

function listdo_sex_defaults() {
	return apply_filters( 'listdo_sex_defaults', array(
		'male' => esc_html__('Male', 'listdo'),
		'female' => esc_html__('Female', 'listdo'),
		'other' => esc_html__('Other', 'listdo')
	) );
}

function listdo_user_social_defaults() {
	return apply_filters( 'listdo_user_social_defaults', array(
		'facebook' => esc_html__('Facebook', 'listdo'),
		'twitter' => esc_html__('Twitter', 'listdo'),
		'google-plus' => esc_html__('Google+', 'listdo'),
		'pinterest' => esc_html__('Pinterest', 'listdo'),
		'linkedin' => esc_html__('Linkedin', 'listdo'),
		'instagram' => esc_html__('Instagram', 'listdo'),
	) );
}

function listdo_get_attachment_id_from_url( $attachment_url = '' ) {

	global $wpdb;
	$attachment_id = false;

	// If there is no url, bail.
	if ( '' == $attachment_url ) {
		return false;
	}

	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

	}

	return $attachment_id;
}

function listdo_create_attachment( $attachment_url, $post_id = 0 ) {
	include_once ABSPATH . 'wp-admin/includes/image.php';
	include_once ABSPATH . 'wp-admin/includes/media.php';

	$upload_dir     = wp_upload_dir();
	$attachment_url = esc_url( $attachment_url, array( 'http', 'https' ) );
	if ( empty( $attachment_url ) ) {
		return 0;
	}

	$attachment_url = str_replace( array( $upload_dir['baseurl'], WP_CONTENT_URL, site_url( '/' ) ), array( $upload_dir['basedir'], WP_CONTENT_DIR, ABSPATH ), $attachment_url );
	if ( empty( $attachment_url ) || ! is_string( $attachment_url ) ) {
		return 0;
	}

	$attachment = array(
		'post_title'   => esc_html__('Attachment Image', 'listdo'),
		'post_content' => '',
		'post_status'  => 'inherit',
		'post_parent'  => $post_id,
		'guid'         => $attachment_url,
	);

	$info = wp_check_filetype( $attachment_url );
	if ( $info ) {
		$attachment['post_mime_type'] = $info['type'];
	}

	$attachment_id = wp_insert_attachment( $attachment, $attachment_url, $post_id );

	if ( ! is_wp_error( $attachment_id ) ) {
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $attachment_url ) );
		return $attachment_id;
	}

	return 0;
}

function listdo_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'listdo_pingback_header' );