<?php

// Shop Archive settings
function listdo_woo_redux_config($sections, $sidebars, $columns) {
    
    // Woocommerce
    $sections[] = array(
        'icon' => 'el el-shopping-cart',
        'title' => esc_html__('Woocommerce', 'listdo'),
        'fields' => array(
            array(
                'id' => 'show_product_breadcrumbs',
                'type' => 'switch',
                'title' => esc_html__('Breadcrumbs', 'listdo'),
                'default' => 1
            ),
            array (
                'title' => esc_html__('Breadcrumbs Background Color', 'listdo'),
                'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'listdo').'</em>',
                'id' => 'woo_breadcrumb_color',
                'type' => 'color',
                'transparent' => false,
            ),
            array(
                'id' => 'woo_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumbs Background', 'listdo'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'listdo'),
            ),
        )
    );
    // Archive settings
    $sections[] = array(
        'subsection' => true,
        'title' => esc_html__('Product Archives', 'listdo'),
        'fields' => array(
            array(
                'id' => 'product_archive_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Archive Product Layout', 'listdo'),
                'subtitle' => esc_html__('Select the layout you want to apply on your archive product page.', 'listdo'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Content', 'listdo'),
                        'alt' => esc_html__('Main Content', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left Sidebar - Main Content', 'listdo'),
                        'alt' => esc_html__('Left Sidebar - Main Content', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main Content - Right Sidebar', 'listdo'),
                        'alt' => esc_html__('Main Content - Right Sidebar', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                    ),
                ),
                'default' => 'main'
            ),
            array(
                'id' => 'product_archive_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'listdo'),
                'default' => false
            ),
            array(
                'id' => 'product_archive_filter_sidebar',
                'type' => 'select',
                'title' => esc_html__('Filter Sidebar', 'listdo'),
                'subtitle' => esc_html__('Show filter sidebar when shop only show Main Content.', 'listdo'),
                'options' => array(
                    'none' => esc_html__('Do not show', 'listdo'),
                    'right' => esc_html__('Right', 'listdo'),
                    'top' => esc_html__('Categories + Sidebar in Top', 'listdo'),
                ),
                'required' => array('product_archive_layout', '=', 'main'),
                'default' => 'none'
            ),
            array(
                'id' => 'product_archive_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Left Sidebar', 'listdo'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'listdo'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_archive_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Right Sidebar', 'listdo'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'listdo'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_display_mode',
                'type' => 'select',
                'title' => esc_html__('Display Mode', 'listdo'),
                'subtitle' => esc_html__('Choose a default layout archive product.', 'listdo'),
                'options' => array('grid' => esc_html__('Grid', 'listdo'), 'list' => esc_html__('List', 'listdo')),
                'default' => 'grid'
            ),
            array(
                'id' => 'number_products_per_page',
                'type' => 'text',
                'title' => esc_html__('Number of Products Per Page', 'listdo'),
                'default' => 8,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'product_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'listdo'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id' => 'show_swap_image',
                'type' => 'switch',
                'title' => esc_html__('Show Second Image (Hover)', 'listdo'),
                'default' => 1
            ),
        )
    );
    // Product Page
    $sections[] = array(
        'subsection' => true,
        'title' => esc_html__('Single Product', 'listdo'),
        'fields' => array(
            array(
                'id' => 'product_single_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Single Product Layout', 'listdo'),
                'subtitle' => esc_html__('Select the layout you want to apply on your Single Product Page.', 'listdo'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Only', 'listdo'),
                        'alt' => esc_html__('Main Only', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left - Main Sidebar', 'listdo'),
                        'alt' => esc_html__('Left - Main Sidebar', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main - Right Sidebar', 'listdo'),
                        'alt' => esc_html__('Main - Right Sidebar', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                    ),
                ),
                'default' => 'main'
            ),
            array(
                'id' => 'product_single_fullwidth',
                'type' => 'switch',
                'title' => esc_html__('Is Full Width?', 'listdo'),
                'default' => false
            ),
            array(
                'id' => 'product_single_left_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Left Sidebar', 'listdo'),
                'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'listdo'),
                'options' => $sidebars
            ),
            array(
                'id' => 'product_single_right_sidebar',
                'type' => 'select',
                'title' => esc_html__('Single Product Right Sidebar', 'listdo'),
                'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'listdo'),
                'options' => $sidebars
            ),
            array(
                'id' => 'show_product_social_share',
                'type' => 'switch',
                'title' => esc_html__('Show Social Share', 'listdo'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_review_tab',
                'type' => 'switch',
                'title' => esc_html__('Show Product Review Tab', 'listdo'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_releated',
                'type' => 'switch',
                'title' => esc_html__('Show Products Releated', 'listdo'),
                'default' => 1
            ),
            array(
                'id' => 'show_product_upsells',
                'type' => 'switch',
                'title' => esc_html__('Show Products upsells', 'listdo'),
                'default' => 1
            ),
            array(
                'id' => 'number_product_releated',
                'title' => esc_html__('Number of related/upsells products to show', 'listdo'),
                'default' => 4,
                'min' => '1',
                'step' => '1',
                'max' => '20',
                'type' => 'slider'
            ),
            array(
                'id' => 'releated_product_columns',
                'type' => 'select',
                'title' => esc_html__('Releated Products Columns', 'listdo'),
                'options' => $columns,
                'default' => 4
            ),

        )
    );
    
    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_woo_redux_config', 1, 3 );