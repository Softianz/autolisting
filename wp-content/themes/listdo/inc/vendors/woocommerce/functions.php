<?php

if ( !function_exists('listdo_get_products') ) {
    function listdo_get_products( $args = array() ) {
        global $woocommerce, $wp_query;

        $args = wp_parse_args( $args, array(
            'categories' => array(),
            'product_type' => 'recent_product',
            'paged' => 1,
            'post_per_page' => -1,
            'orderby' => '',
            'order' => '',
            'includes' => array(),
            'excludes' => array(),
            'author' => '',
            'search' => '',
        ));
        extract($args);
        
        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );

        if ( isset( $query_args['orderby'] ) ) {
            if ( 'price' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_price',
                    'orderby'   => 'meta_value_num'
                ) );
            }
            if ( 'featured' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_featured',
                    'orderby'   => 'meta_value'
                ) );
            }
            if ( 'sku' == $query_args['orderby'] ) {
                $query_args = array_merge( $query_args, array(
                    'meta_key'  => '_sku',
                    'orderby'   => 'meta_value'
                ) );
            }
        }
        switch ($product_type) {
            case 'listing_package':
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array( 'listing_package' )
                );
            break;
            case 'job_package':
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array( 'job_package', 'job_package_subscription' )
                );
            break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $query_args['tax_query'][] = array(
                'taxonomy'      => 'product_cat',
                'field'         => 'slug',
                'terms'         => implode(",", $categories ),
                'operator'      => 'IN'
            );
        }

        if (!empty($includes) && is_array($includes)) {
            $query_args['post__in'] = $includes;
        }
        
        if ( !empty($excludes) && is_array($excludes) ) {
            $query_args['post__not_in'] = $excludes;
        }

        if ( !empty($author) ) {
            $query_args['author'] = $author;
        }

        if ( !empty($search) ) {
            $query_args['search'] = "*{$search}*";
        }

        return new WP_Query($query_args);
    }
}

function listdo_woocommerce_pre_get_posts( $q ) {
    if ( ! $q->is_main_query() ) {
        return;
    }
    if ( $q->is_archive && ((isset($q->query_vars['post_type']) && $q->query_vars['post_type'] == 'product') || isset($q->query_vars['product_cat'])) && !$q->is_admin ) {
        $tax_query = $q->get( 'tax_query' );
        $tax_query[] = array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array( 'job_package', 'job_package_subscription' ),
                    'operator' => 'not in',
                );
        
        $q->set( 'tax_query', $tax_query );
    }
}
//add_action( 'pre_get_posts', 'listdo_woocommerce_pre_get_posts', 10 );

// hooks
function listdo_woocommerce_enqueue_styles() {
    wp_enqueue_style( 'listdo-woocommerce', get_template_directory_uri() .'/css/woocommerce.css' , 'listdo-woocommerce-front' , '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'listdo_woocommerce_enqueue_styles', 99 );

function listdo_woocommerce_enqueue_scripts() {
    wp_register_script( 'listdo-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array( 'jquery', 'jquery-unveil', 'slick' ), '20150330', true );

    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : site_url();
    $options = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'view_more_text' => esc_html__('View More', 'listdo'),
        'view_less_text' => esc_html__('View Less', 'listdo'),
    );
    wp_localize_script( 'listdo-woocommerce', 'listdo_woo_options', $options );
    wp_enqueue_script( 'listdo-woocommerce' );
    
    wp_enqueue_script( 'wc-add-to-cart-variation' );
}
add_action( 'wp_enqueue_scripts', 'listdo_woocommerce_enqueue_scripts', 10 );

// cart
if ( !function_exists('listdo_woocommerce_header_add_to_cart_fragment') ) {
    function listdo_woocommerce_header_add_to_cart_fragment( $fragments ){
        global $woocommerce;
        $fragments['.cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        $fragments['.footer-mini-cart .count'] =  ' <span class="count"> '. $woocommerce->cart->cart_contents_count .' </span> ';
        //$fragments['.cart .total-minicart'] = '<div class="total-minicart">'. $woocommerce->cart->get_cart_total(). '</div>';
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'listdo_woocommerce_header_add_to_cart_fragment' );

// breadcrumb for woocommerce page
if ( !function_exists('listdo_woocommerce_breadcrumb_defaults') ) {
    function listdo_woocommerce_breadcrumb_defaults( $args ) {
        $breadcrumb_img = listdo_get_config('woo_breadcrumb_image');
        $breadcrumb_color = listdo_get_config('woo_breadcrumb_color');
        $style = array();
        $show_breadcrumbs = listdo_get_config('show_product_breadcrumbs',1);
        $style[] = 'display:none';
        if ( !$show_breadcrumbs ) {
            $style[] = 'display:none';
        }
        if( $breadcrumb_color  ){
            $style[] = 'background-color:'.$breadcrumb_color;
        }
        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
        }
        $estyle = !empty($style)? ' style="'.implode(";", $style).'"':"";

        $full_width = apply_filters('listdo_woocommerce_content_class', 'container');

        // check woo
        if(is_product()) {
            $title = get_the_title();
        } elseif ( is_product_taxonomy() ) {
            global $wp_query;
            $term = $wp_query->queried_object;
            $title = esc_html__( 'Shop', 'listdo' );
            if ( isset( $term->name) ) {
                $title = $term->name;
            }
        } else {
            $title = esc_html__( 'Shop', 'listdo' );
        }

        $title = '<h2 class="bread-title">'.$title.'</h2>';

        $args['wrap_before'] = '<section id="apus-breadscrumb" class="apus-breadscrumb"'.$estyle.'><div class="apus-inner-bread"><div class="wrapper-breads"><div class="'.$full_width.'"><div class="breadscrumb-inner text-center">'.$title.'<ol class="apus-woocommerce-breadcrumb breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
        $args['wrap_after'] = '</ol></div></div></div></div></section>';

        return $args;
    }
}
add_filter( 'woocommerce_breadcrumb_defaults', 'listdo_woocommerce_breadcrumb_defaults' );
add_action( 'listdo_woo_template_main_before', 'woocommerce_breadcrumb', 30, 0 );

// display woocommerce modes
if ( !function_exists('listdo_woocommerce_display_modes') ) {
    function listdo_woocommerce_display_modes(){
        global $wp;
        $current_url = listdo_shop_page_link(true);

        $url_grid = add_query_arg( 'display_mode', 'grid', remove_query_arg( 'display_mode', $current_url ) );
        $url_list = add_query_arg( 'display_mode', 'list', remove_query_arg( 'display_mode', $current_url ) );

        $woo_mode = listdo_woocommerce_get_display_mode();

        echo '<div class="display-mode pull-right">';
        echo '<a href="'.  $url_grid  .'" class=" change-view '.($woo_mode == 'grid' ? 'active' : '').'"><i class="ti-layout-grid3"></i></a>';
        echo '<a href="'.  $url_list  .'" class=" change-view '.($woo_mode == 'list' ? 'active' : '').'"><i class="ti-view-list-alt"></i></a>';
        echo '</div>'; 
    }
}

if ( !function_exists('listdo_woocommerce_get_display_mode') ) {
    function listdo_woocommerce_get_display_mode() {
        $woo_mode = listdo_get_config('product_display_mode', 'grid');
        $args = array( 'grid', 'list' );
        if ( isset($_COOKIE['listdo_woo_mode']) && in_array($_COOKIE['listdo_woo_mode'], $args) ) {
            $woo_mode = $_COOKIE['listdo_woo_mode'];
        }
        return $woo_mode;
    }
}

if(!function_exists('listdo_shop_page_link')) {
    function listdo_shop_page_link($keep_query = false ) {
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url('/');
        } elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
            $link = get_post_type_archive_link( 'product' );
        } else {
            $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
        }

        if( $keep_query ) {
            // Keep query string vars intact
            foreach ( $_GET as $key => $val ) {
                if ( 'orderby' === $key || 'submit' === $key ) {
                    continue;
                }
                $link = add_query_arg( $key, $val, $link );

            }
        }
        return $link;
    }
}


if(!function_exists('listdo_filter_before')){
    function listdo_filter_before(){
        echo '<div class="wrapper-fillter"><div class="apus-filter clearfix">';
    }
}
if(!function_exists('listdo_filter_after')){
    function listdo_filter_after(){
        echo '</div></div>';
    }
}
add_action( 'woocommerce_before_shop_loop', 'listdo_filter_before' , 1 );
add_action( 'woocommerce_before_shop_loop', 'listdo_filter_after' , 40 );


// set display mode to cookie
if ( !function_exists('listdo_before_woocommerce_init') ) {
    function listdo_before_woocommerce_init() {
        if( isset($_GET['display_mode']) && ($_GET['display_mode']=='list' || $_GET['display_mode']=='grid') ){  
            setcookie( 'listdo_woo_mode', trim($_GET['display_mode']) , time()+3600*24*100,'/' );
            $_COOKIE['listdo_woo_mode'] = trim($_GET['display_mode']);
        }
    }
}
add_action( 'init', 'listdo_before_woocommerce_init' );

// Number of products per page
if ( !function_exists('listdo_woocommerce_shop_per_page') ) {
    function listdo_woocommerce_shop_per_page($number) {
        if (!is_admin()) {
            if ( isset( $_REQUEST['wppp_ppp'] ) ) :
                $number = intval( $_REQUEST['wppp_ppp'] );
                WC()->session->set( 'products_per_page', intval( $_REQUEST['wppp_ppp'] ) );
            elseif ( isset( $_REQUEST['ppp'] ) ) :
                $number = intval( $_REQUEST['ppp'] );
                WC()->session->set( 'products_per_page', intval( $_REQUEST['ppp'] ) );
            elseif ( WC()->session->__isset( 'products_per_page' ) ) :
                $number = intval( WC()->session->__get( 'products_per_page' ) );
            else :
                $value = listdo_get_config('number_products_per_page', 12);
                $number = intval( $value );
            endif;
        }
        return $number;

    }
}
add_filter( 'loop_shop_per_page', 'listdo_woocommerce_shop_per_page', 30 );

// Number of products per row
if ( !function_exists('listdo_woocommerce_shop_columns') ) {
    function listdo_woocommerce_shop_columns($number) {
        $value = listdo_get_config('product_columns');
        if ( in_array( $value, array(1, 2, 3, 4, 5, 6) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_shop_columns', 'listdo_woocommerce_shop_columns' );

// share box
if ( !function_exists('listdo_woocommerce_share_box') ) {
    function listdo_woocommerce_share_box() {
        if ( listdo_get_config('show_product_social_share') ) {
            get_template_part( 'template-parts/sharebox' );
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'listdo_woocommerce_share_box', 100 );

// swap effect
if ( !function_exists('listdo_swap_images') ) {
    function listdo_swap_images() {
        global $post, $product, $woocommerce;
        
        $thumb = 'woocommerce_thumbnail';
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            $swap_image = listdo_get_config('enable_swap_image', true);
            if ( $swap_image ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= listdo_get_attachment_thumbnail( $attachment_ids[0], $thumb, false, array('class' => $swap_class), false);
                }
            }
            $output .= listdo_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.esc_url(wc_placeholder_img_src()).'" alt="'.esc_attr__('Placeholder' , 'listdo').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'listdo_swap_images', 10);

if ( !function_exists('listdo_product_image') ) {
    function listdo_product_image($thumb = 'shop_thumbnail') {
        $swap_image = (bool)listdo_get_config('enable_swap_image', true);
        ?>
        <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="product-image">
            <?php listdo_product_get_image($thumb, $swap_image); ?>
        </a>
        <?php
    }
}
// get image
if ( !function_exists('listdo_product_get_image') ) {
    function listdo_product_get_image($thumb = 'woocommerce_thumbnail', $swap = true) {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = "attachment-$thumb size-$thumb image-no-effect";
        if (has_post_thumbnail()) {
            if ( $swap ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = "attachment-$thumb size-$thumb image-hover";
                    $swap_class = "attachment-$thumb size-$thumb image-effect";
                    $output .= listdo_get_attachment_thumbnail( $attachment_ids[0], $thumb , false, array('class' => $swap_class), false);
                }
            }
            $output .= listdo_get_attachment_thumbnail( get_post_thumbnail_id(), $thumb , false, array('class' => $class), false);
        } else {
            $image_sizes = get_option('shop_catalog_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.esc_url(wc_placeholder_img_src()).'" alt="'.esc_attr__('Placeholder' , 'listdo').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}

// layout class for woo page
if ( !function_exists('listdo_woocommerce_content_class') ) {
    function listdo_woocommerce_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        if( listdo_get_config('product_'.$page.'_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'listdo_woocommerce_content_class', 'listdo_woocommerce_content_class' );

// get layout configs
if ( !function_exists('listdo_get_woocommerce_layout_configs') ) {
    function listdo_get_woocommerce_layout_configs() {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        // lg and md for fullwidth
        if( listdo_get_config('product_'.$page.'_fullwidth') ) {
            $sidebar_width = 'col-md-4 col-sm-12 ';
            $main_width = 'col-md-8 col-sm-12 ';
        }else{
            $sidebar_width = 'col-md-4 col-xs-12 ';
            $main_width = 'col-md-8 col-xs-12 ';
        }
        $left = listdo_get_config('product_'.$page.'_left_sidebar');
        $right = listdo_get_config('product_'.$page.'_right_sidebar');

        switch ( listdo_get_config('product_'.$page.'_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => $sidebar_width.'col-sm-12 col-xs-12 '  );
                $configs['main'] = array( 'class' => $main_width.'col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => $sidebar_width.'col-sm-12 col-xs-12 ' ); 
                $configs['main'] = array( 'class' => $main_width.'col-sm-12 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
                break;
            default:
                
                $configs['main'] = array( 'class' => 'col-sm-12 col-xs-12' );
                break;
        }

        return $configs; 
    }
}

if ( !function_exists( 'listdo_product_review_tab' ) ) {
    function listdo_product_review_tab($tabs) {
        global $post;
        if ( !listdo_get_config('show_product_review_tab') && isset($tabs['reviews']) ) {
            unset( $tabs['reviews'] ); 
        }
        return $tabs;
    }
}
add_filter( 'woocommerce_product_tabs', 'listdo_product_review_tab', 90 );

// Wishlist
add_filter( 'yith_wcwl_button_label', 'listdo_woocomerce_icon_wishlist'  );
add_filter( 'yith-wcwl-browse-wishlist-label', 'listdo_woocomerce_icon_wishlist_add' );
function listdo_woocomerce_icon_wishlist( $value='' ){
    return '<i class="ti-heart"></i>'.'<span class="sub-title">'.esc_html__('Add to Wishlist','listdo').'</span>';
}

function listdo_woocomerce_icon_wishlist_add(){
    return '<i class="ti-heart"></i>'.'<span class="sub-title">'.esc_html__('Wishlisted','listdo').'</span>';
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


if ( ! function_exists( 'listdo_wc_products_per_page' ) ) {
    function listdo_wc_products_per_page() {
        global $wp_query;

        $action = '';
        $cat                = $wp_query->get_queried_object();
        $return_to_first    = apply_filters( 'listdo_wc_ppp_return_to_first', false );
        $total              = $wp_query->found_posts;
        $per_page           = $wp_query->get( 'posts_per_page' );
        $_per_page          = listdo_get_config('number_products_per_page', 12);

        // Generate per page options
        $products_per_page_options = array();
        while ( $_per_page < $total ) {
            $products_per_page_options[] = $_per_page;
            $_per_page = $_per_page * 2;
        }

        if ( empty( $products_per_page_options ) ) {
            return;
        }

        $products_per_page_options[] = -1;

        $query_string = ! empty( $_GET['QUERY_STRING'] ) ? '?' . add_query_arg( array( 'ppp' => false ), $_GET['QUERY_STRING'] ) : null;

        if ( isset( $cat->term_id ) && isset( $cat->taxonomy ) && $return_to_first ) {
            $action = get_term_link( $cat->term_id, $cat->taxonomy ) . $query_string;
        } elseif ( $return_to_first ) {
            $action = get_permalink( wc_get_page_id( 'shop' ) ) . $query_string;
        }

        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        ?>
        <form method="POST" action="<?php echo esc_url( $action ); ?>" class="form-listdo-ppp">
            <?php
            foreach ( $_GET as $key => $value ) {
                if ( 'ppp' === $key || 'submit' === $key ) {
                    continue;
                }
                if ( is_array( $value ) ) {
                    foreach( $value as $i_value ) {
                        ?>
                        <input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $i_value ); ?>" />
                        <?php
                    }
                } else {
                    ?><input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" /><?php
                }
            }
            ?>

            <select name="ppp" onchange="this.form.submit()" class="listdo-wc-wppp-select">
                <?php foreach( $products_per_page_options as $key => $value ) { ?>
                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $per_page ); ?>><?php
                        $ppp_text = apply_filters( 'listdo_wc_ppp_text', esc_html__( 'Show: %s', 'listdo' ), $value );
                        esc_html( printf( $ppp_text, $value == -1 ? esc_html__( 'All', 'listdo' ) : $value ) );
                    ?></option>
                <?php } ?>
            </select>
        </form>
        <?php
    }
}

function listdo_woo_after_shop_loop_before() {
    ?>
    <div class="apus-after-loop-shop clearfix">
    <?php
}
function listdo_woo_after_shop_loop_after() {
    ?>
    </div>
    <?php
}
add_action( 'woocommerce_after_shop_loop', 'listdo_woo_after_shop_loop_before', 1 );
add_action( 'woocommerce_after_shop_loop', 'listdo_woo_after_shop_loop_after', 99999 );
//add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 30 );
//add_action( 'woocommerce_after_shop_loop', 'listdo_wc_products_per_page', 20 );


function listdo_woo_display_product_cat($product_id) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    if ( !empty($terms) ) { ?>
        <div class="product-cats">
        <?php foreach ( $terms as $term ) {
            echo '<a href="' . get_term_link( $term->term_id ) . '">' . $term->name . '</a>';
            break;
        } ?>
        </div>
    <?php
    }
}

// catalog mode
add_action( 'wp', 'listdo_catalog_mode_init' );
add_action( 'wp', 'listdo_pages_redirect' );


function listdo_catalog_mode_init() {

    if( ! listdo_get_config( 'enable_shop_catalog' ) ) return false;

    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

}

function listdo_pages_redirect() {
    if( ! listdo_get_config( 'enable_shop_catalog' ) ) return false;

    $cart     = is_page( wc_get_page_id( 'cart' ) );
    $checkout = is_page( wc_get_page_id( 'checkout' ) );

    wp_reset_postdata();

    if ( $cart || $checkout ) {
        wp_redirect( home_url('/') );
        exit;
    }
}
