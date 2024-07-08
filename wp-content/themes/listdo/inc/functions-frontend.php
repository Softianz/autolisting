<?php

if ( ! function_exists( 'listdo_post_tags' ) ) {
	function listdo_post_tags() {
		$posttags = get_the_tags();
		if ( $posttags ) {
			echo '<span class="entry-tags-list"><strong>'.esc_html__( 'Tags: ' , 'listdo' ).'</strong> ';
			$size = count( $posttags );
			$count = 1;
			foreach ( $posttags as $tag ) {
				echo '<a href="' . get_tag_link( $tag->term_id ) . '">';
				echo esc_attr($tag->name);
				echo '</a>';
				if($count < $size){
					echo '';
				}
				$count++;
			}
			echo '</span>';
		}
	}
}

if ( ! function_exists( 'listdo_breadcrumb_title' ) ) {
	function listdo_breadcrumb_title() {
		$title = '';

		if ( !is_front_page() || is_paged()) {
			global $post;
			$homeLink = esc_url( home_url('/') );
			if ( is_home() ) {
				$title = esc_html__( 'Blog', 'listdo' );
			} 
			elseif (is_category()) {
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				$title = $cat_obj->name;
			} elseif (is_day()) {
				$title = get_the_time('d');
			} elseif (is_month()) {
				$title = get_the_time('F');
			} elseif (is_year()) {
				$title = get_the_time('Y');
			} elseif (is_single() && !is_attachment()) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$title = esc_html__( 'Blog', 'listdo' );
			} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_search() ) {
				if (is_tax('job_listing_category') || is_tax('job_listing_tag') || is_tax('job_listing_region') || is_tax('job_listing_type')) {
					global $wp_query;
					$cat_obj = $wp_query->get_queried_object();
					$thisCat = get_term($cat_obj->term_id);
					if (!empty($thisCat)) {
						$title = $thisCat->name;
					}
				} else {
					$post_type = get_post_type_object(get_post_type());
					if (is_object($post_type)) {
						$title = $post_type->labels->name;
					}
				}
			} elseif (is_attachment()) {
				$title = get_the_title();
			} elseif ( is_page() && !$post->post_parent ) {
				$title = get_the_title();
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . esc_url( get_permalink($page->ID) ) . '">' . get_the_title($page->ID) . '</a></li>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				$title = get_the_title();
			} elseif ( is_search() ) {
				$title = esc_html__('Search results for ','listdo') . '"'.get_search_query().'"';
			} elseif ( is_tag() ) {
				$title = esc_html__('Posts tagged', 'listdo') . '"' . single_tag_title('', false) . '"';
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				$title = esc_html__('Profile', 'listdo');
			} elseif ( is_404() ) {
				$title = esc_html__('Error 404', 'listdo');
			}
		} elseif ( is_front_page() ) {
			$title = esc_html__( 'Blog', 'listdo' );
		}
		return $title;
	}
}

if ( ! function_exists( 'listdo_breadcrumbs' ) ) {
	function listdo_breadcrumbs($show_title = true) {
		$delimiter = ' ';
		$home = esc_html__('Home', 'listdo');
		$before = '<li><span class="active">';
		$after = '</span></li>';
		$title = '';
		if ( !is_front_page() || is_paged()) {
			global $post;
			$homeLink = esc_url( home_url('/') );
			

			echo '<ol class="breadcrumb">';

			echo '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . '</li> ';

			if (is_category()) {
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				echo '<li>';
				if ($thisCat->parent != 0)
					echo get_category_parents($parentCat, TRUE, '</li><li>');
				echo '<span class="active">'.single_cat_title('', false) . $after;
			} elseif (is_day()) {
				echo '<li><a href="' . esc_url( get_year_link(get_the_time('Y')) ) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
				echo '<li><a href="' . esc_url( get_month_link(get_the_time('Y'),get_the_time('m')) ) . '">' . get_the_time('F') . '</a></li> ' . $delimiter . ' ';
				echo trim($before) . get_the_time('d') . $after;
			} elseif (is_month()) {
				echo '<li><a href="' . esc_url( get_year_link(get_the_time('Y')) ) . '">' . get_the_time('Y') . '</a></li> ' . $delimiter . ' ';
				echo trim($before) . get_the_time('F') . $after;
			} elseif (is_year()) {
				echo trim($before) . get_the_time('Y') . $after;
			} elseif (is_single() && !is_attachment()) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->name . '</a></li> ' . $delimiter . ' ';
					echo trim($before) . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					echo '<li>'.get_category_parents($cat, TRUE, '</li><li>');
					echo '<span class="active">'.get_the_title() . $after;
				}
			} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_author() && !is_search()) {
				$post_type = get_post_type_object(get_post_type());
				if (is_tax('job_listing_category') || is_tax('job_listing_tag') || is_tax('job_listing_amenity') || is_tax('job_listing_region') || is_tax('job_listing_type')) {
					global $wp_query;
					$cat_obj = $wp_query->get_queried_object();
					$thisCat = get_term($cat_obj->term_id);
					

					$parentCat = get_term($thisCat->parent);
					if ($thisCat->parent != 0) {
						echo '<li><a href="' . listdo_get_listings_page_url() . '">' . esc_html__('Listings', 'listdo') . '</a></li> ' . $delimiter . ' ';

						$tax = 'job_listing_category';
						if ( is_tax('job_listing_tag') ) {
							$tax = 'job_listing_tag';
						}
						if ( is_tax('job_listing_amenity') ) {
							$tax = 'job_listing_amenity';
						}
						if ( is_tax('job_listing_region') ) {
							$tax = 'job_listing_region';
						}
						if ( is_tax('job_listing_type') ) {
							$tax = 'job_listing_type';
						}
						$args = array(
							'separator' => ' <span class="tarrow"> > </span> ',
							'link'      => true,
							'format'    => 'name',
						);

						echo '<li>'.get_term_parents_list( $parentCat->term_id, $tax, $args ).'</li>';

						echo trim($before.single_cat_title('', false) . $after);
					} else {
						echo '<li><a href="' . listdo_get_listings_page_url() . '">' . esc_html__('Listings', 'listdo') . '</a></li>';
						echo trim($before.single_cat_title('', false) . $after);
					}

				} elseif (is_object($post_type)) {
					echo trim($before) . $post_type->labels->name . $after;
				}

			} elseif (is_attachment()) {
				$parent = get_post($post->post_parent);
				$cat = get_the_category($parent->ID);
				echo '<li>';
				if ( !empty($cat) ) {
					$cat = $cat[0];
					echo get_category_parents($cat, TRUE, '</li><li>');
				}
				if ( !empty($parent) ) {
					echo '<a href="' . esc_url( get_permalink($parent) ) . '">' . $parent->post_title . '</a></li><li>';
				}
				echo '<span class="active">'.get_the_title() . $after;
			} elseif ( is_page() && !$post->post_parent ) {
				echo trim($before) . get_the_title() . $after;
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . esc_url( get_permalink($page->ID) ) . '">' . get_the_title($page->ID) . '</a></li>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) {
					echo trim($crumb) . ' ' . $delimiter . ' ';
				}
				echo trim($before) . get_the_title() . $after;
			} elseif ( is_search() ) {
				echo trim($before) . esc_html__('Search', 'listdo') . $after;
			} elseif ( is_tag() ) {
				echo trim($before) . esc_html__('Posts tagged "', 'listdo'). single_tag_title('', false) . '"' . $after;
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo trim($before) . $userdata->display_name . $after;
			} elseif ( is_404() ) {
				echo trim($before) . esc_html__('Error 404', 'listdo') . $after;
			} elseif ( is_home() ) {
				echo trim($before) . esc_html__('blog', 'listdo') . $after;
			}
			echo '</ol>';
		}
	}
}

function listdo_listing_breadcrumbs() {
	$delimiter = ' ';
	$home = esc_html__('Home', 'listdo');
	global $post;
	$homeLink = esc_url( home_url('/') );

	echo '<ol class="breadcrumb">';
		echo '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . '</li> ';
		echo '<li><a href="'.listdo_get_listings_page_url().'">'.esc_html__( 'Listings', 'listdo' ).'</a> ' . $delimiter . '</li> ';
		
		$term_list = wp_get_post_terms(
			$post->ID,
			'job_listing_category',
			array(
				"fields" => "all",
				'orderby' => 'parent',
			)
		);

		if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
			$main_term = $term_list[0];
			$ancestors = get_ancestors( $main_term->term_id, 'job_listing_category' );
			$ancestors = array_reverse( $ancestors );

			$count = 0;
			foreach ( $ancestors as $ancestor ) {
				$ancestor = get_term( $ancestor, 'job_listing_category' );

				if ( ! is_wp_error( $ancestor ) && $ancestor ) {
					echo '<li><a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . $ancestor->name . '</a>';
						echo trim($delimiter);
					echo '</li> ';
				}
				$count++;
			}
			echo '<li><a href="' . esc_url( get_term_link( $main_term ) ) . '">' . $main_term->name . '</a>';
			echo '</li> ';

		}
	echo '</ol>';
}

if ( ! function_exists( 'listdo_check_breadcrumbs' ) ) {
	function listdo_check_breadcrumbs() {
		global $post;
		$show = true;
		if ( is_page() && is_object($post) ) {
			$show = get_post_meta( $post->ID, 'apus_page_show_breadcrumb', true );
			if ( $show == 'no' ) {
				$show = false; 
			}else{
				$show = true; 
			}
		} elseif ( is_singular('post') || is_category() || is_home() ) {
			$show = false;
		} elseif ( is_post_type_archive('job_listing') || is_tax('job_listing_tag') || is_tax('job_listing_amenity') || is_tax('job_listing_category') || is_tax('job_listing_region') || is_tax('job_listing_type') ) {
			$show = listdo_get_config('show_listing_breadcrumbs', true);

			$version = listdo_get_listing_archive_version();
			$halfmaps = listdo_get_listing_all_half_map_version();
			if ( in_array($version, $halfmaps) ) {
				$show = false;
			}

		} elseif ( is_author() ) {
			$show = listdo_get_config('show_profile_breadcrumbs', true);
		} elseif ( is_404() ) {
			$show = listdo_get_config('show_404_breadcrumbs', true);
		}
		if ( listdo_is_woocommerce_activated() ) {
			if(is_product()) {
	            $show = false; 
	        } elseif ( is_shop() ) {
				$show = false; 
			} elseif ( is_product_category() ) {
				$show = false; 
			} elseif ( is_product_tag() ) {
				$show = false; 
			}
		}

		return $show;
	}
}

if ( ! function_exists( 'listdo_render_breadcrumbs' ) ) {
	function listdo_render_breadcrumbs($type = 'normal') {
		global $post, $listdo_page_title;
		$show = true;
		$style = array();
		$has_img = '';
		$des = '';
		$header = apply_filters( 'listdo_get_header_layout', listdo_get_config('header_type') );
		if ( !empty($header) ) {
			$container_class = 'container';
		}else{
			$container_class = 'container-fluid breadcrumb-full';
		}
		if ( is_page() && is_object($post) ) {

			$show = get_post_meta( $post->ID, 'apus_page_show_breadcrumb', true );
			if ( $show == 'no' ) {
				return ''; 
			}
			$bgimage = get_post_meta( $post->ID, 'apus_page_breadcrumb_image', true );
			$bgcolor = get_post_meta( $post->ID, 'apus_page_breadcrumb_color', true );
			$des = get_post_meta( $post->ID, 'apus_page_description', true );
			$style = array();
			if ( $bgcolor ) {
				$style[] = 'background-color:'.$bgcolor;
			}
			if ( $bgimage ) { 
				$style[] = 'background-image:url(\''.esc_url($bgimage).'\')';
				$has_img = 1;
			}

		} elseif ( is_singular('post') || is_category() || is_home() ) {
			//$show = listdo_get_config('show_blog_breadcrumbs', true);
			$show = false;
			if ( !$show ) {
				return ''; 
			}
			$breadcrumb_img = listdo_get_config('blog_breadcrumb_image');
	        $breadcrumb_color = listdo_get_config('blog_breadcrumb_color');
	        $style = array();
	        if ( $breadcrumb_color ) {
	            $style[] = 'background-color:'.$breadcrumb_color;
	        }
	        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
	            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
	            $has_img = 1;
	        }
		} elseif ( is_post_type_archive('job_listing') || is_tax('job_listing_tag') || is_tax('job_listing_amenity') || is_tax('job_listing_category') || is_tax('job_listing_region') || is_tax('job_listing_type') ) {
			$show = listdo_get_config('show_listing_breadcrumbs', true);
			$listing_archive = 1;
			if ( !is_singular('job_listing') ) {
				$version = listdo_get_listing_archive_version();
				$halfmaps = listdo_get_listing_all_half_map_version();
				if ( in_array($version, $halfmaps) ) {
					$show = false;
				}
			}
			if ( !$show  ) {
				return ''; 
			}
			$breadcrumb_img = listdo_get_config('listing_breadcrumb_image');
	        $breadcrumb_color = listdo_get_config('listing_breadcrumb_color');
	        $style = array();
	        if ( $breadcrumb_color ) {
	            $style[] = 'background-color:'.$breadcrumb_color;
	        }
	        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
	            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
	            $has_img = 1;
	        }
		} elseif ( is_author() ) {
			$show = listdo_get_config('show_profile_breadcrumbs', true);
			if ( !$show  ) {
				return ''; 
			}
			$breadcrumb_img = listdo_get_config('profile_breadcrumb_image');
	        $breadcrumb_color = listdo_get_config('profile_breadcrumb_color');
	        $style = array();
	        if ( $breadcrumb_color ) {
	            $style[] = 'background-color:'.$breadcrumb_color;
	        }
	        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
	            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
	            $has_img = 1;
	        }
		} elseif ( is_404() ) {
			$show = listdo_get_config('show_404_breadcrumbs', true);
			if ( !$show  ) {
				return ''; 
			}
			$breadcrumb_img = listdo_get_config('404_breadcrumb_image');
	        $breadcrumb_color = listdo_get_config('404_breadcrumb_color');
	        $style = array();
	        if ( $breadcrumb_color ) {
	            $style[] = 'background-color:'.$breadcrumb_color;
	        }
	        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
	            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
	            $has_img = 1;
	        }
		}
		
		$estyle = !empty($style)? ' style="'.implode(";", $style).'"':"";
		if($has_img) $has_img = 'has-img';
		echo '<section id="apus-breadscrumb" class="apus-breadscrumb '.$has_img.'"'.$estyle.'><div class="apus-inner-bread"><div class="wrapper-breads"><div class="'.$container_class.'"><div class="breadscrumb-inner text-center">';
			if ($type == 'listing' || (isset($listing_single) && $listing_single == 1) ) {
				listdo_listing_breadcrumbs();
			} else {
				$listdo_page_title = $title = listdo_breadcrumb_title();
				if(!empty($title)){
					echo '<h2 class="bread-title">'.$title.'</h2>'.$des;
				}
				listdo_breadcrumbs();
			}
		echo '</div></div></div></div></section>';
	}
}

if ( ! function_exists( 'listdo_paging_nav' ) ) {
	function listdo_paging_nav() {
		global $wp_query, $wp_rewrite;

		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $wp_query->max_num_pages,
			'current'  => $paged,
			'mid_size' => 1,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => '<i class="flaticon-left"></i>'.esc_html__( 'Prev', 'listdo' ),
			'next_text' => esc_html__( 'Next', 'listdo' ).'<i class="flaticon-right"></i>',
		) );

		if ( $links ) :

		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text hidden"><?php esc_html_e( 'Posts navigation', 'listdo' ); ?></h1>
			<div class="apus-pagination">
				<?php echo trim($links); ?>
			</div><!-- .pagination -->
		</nav><!-- .navigation -->
		<?php
		endif;
	}
}

if ( !function_exists('listdo_comment_form') ) {
	function listdo_comment_form($arg, $class = 'btn-theme') {
		global $post;
		if ('open' == $post->comment_status) {
			ob_start();
	      	comment_form($arg);
	      	$form = ob_get_clean();
	      	?>
	      	<div class="commentform reset-button-default">
		    	<?php
		      	$form = str_replace('id="submit"','class="btn '.$class.'"', $form);
		      	echo str_replace('id="commentform"','enctype="multipart/form-data"', $form);
		      	?>
	      	</div>
	      	<?php
	      }
	}
}
if (!function_exists('listdo_list_comment') ) {
	function listdo_list_comment($comment, $args, $depth) {
		if ( is_file(get_template_directory().'/list_comments.php') ) {
	        require get_template_directory().'/list_comments.php';
      	}
	}
}

function listdo_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'listdo_comment_field_to_bottom' );

function listdo_get_blogs_layout_type() {
	$layout = listdo_get_config( 'blog_display_mode', 'list' );
	$layout = !empty($layout) ? $layout : 'list';
	return $layout;
}

function listdo_get_blog_thumbsize() {
	$thumbsize = listdo_get_config( 'blog_item_thumbsize', '' );
	return $thumbsize;
}

/*
 * create placeholder
 * var size: array( width, height )
 */
function listdo_create_placeholder($size) {
	return "data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 ".$size[0]." ".$size[1]."'%2F%3E";
}

function listdo_display_sidebar_left( $sidebar_configs ) {
	if ( isset($sidebar_configs['left']) ) : ?>
		<div class="<?php echo esc_attr($sidebar_configs['left']['class']) ;?>">
		  	<aside class="sidebar sidebar-left" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
		  		<div class="close-sidebar-btn hidden-lg hidden-md"> <i class="fas fa-times"></i> <span><?php esc_html_e('Close', 'listdo'); ?></span></div>
		   		<?php if ( is_active_sidebar( $sidebar_configs['left']['sidebar'] ) ): ?>
		   			<?php dynamic_sidebar( $sidebar_configs['left']['sidebar'] ); ?>
		   		<?php endif; ?>
		  	</aside>
		</div>
	<?php endif;
}

function listdo_display_sidebar_right( $sidebar_configs ) {
	if ( isset($sidebar_configs['right']) ) : ?>
		<div class="<?php echo esc_attr($sidebar_configs['right']['class']) ;?>">
		  	<aside class="sidebar sidebar-right" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
		  		<div class="close-sidebar-btn hidden-lg hidden-md"><i class="fas fa-times"></i> <span><?php esc_html_e('Close', 'listdo'); ?></span></div>
		   		<?php if ( is_active_sidebar( $sidebar_configs['right']['sidebar'] ) ): ?>
			   		<?php dynamic_sidebar( $sidebar_configs['right']['sidebar'] ); ?>
			   	<?php endif; ?>
		  	</aside>
		</div>
	<?php endif;
}

function listdo_before_content( $sidebar_configs ) {
	if ( isset($sidebar_configs['left']) || isset($sidebar_configs['right']) ) : ?>
		<a href="javascript:void(0)" class="mobile-sidebar-btn hidden-lg hidden-md"> <i class="fas fa-bars"></i> <?php echo esc_html__('Show Sidebar', 'listdo'); ?></a>
		<div class="mobile-sidebar-panel-overlay"></div>
	<?php endif;
}