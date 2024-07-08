<?php

function listdo_wp_job_manager_redux_config_general($sections, $sidebars, $columns) {
    $general_fields = array(
        array(
            'id' => 'listing_general_region_settings',
            'icon' => true,
            'type' => 'info',
            'raw' => '<h3> '.esc_html__('Region Settings', 'listdo').'</h3>',
        ),
        array(
            'id' => 'submit_listing_region_nb_fields',
            'type' => 'select',
            'title' => esc_html__('Number Fields', 'listdo'),
            'options' => array(
                '1' => esc_html__('1 Field', 'listdo'),
                '2' => esc_html__('2 Fields', 'listdo'),
                '3' => esc_html__('3 Fields', 'listdo'),
                '4' => esc_html__('4 Fields', 'listdo'),
            ),
            'description' => esc_html__('You can set 4 fields for regions like: Country, State, City, District', 'listdo'),
            'default' => 1
        ),
        array(
            'id' => 'submit_listing_region_1_field_label',
            'type' => 'text',
            'title' => esc_html__('First Field Label', 'listdo'),
            'default' => '',
            'description' => esc_html__('First region field label', 'listdo'),
            'required' => array('submit_listing_region_nb_fields', '=', array('1', '2', '3', '4')),
        ),
        array(
            'id' => 'submit_listing_region_2_field_label',
            'type' => 'text',
            'title' => esc_html__('Second Field Label', 'listdo'),
            'default' => '',
            'description' => esc_html__('Second region field label', 'listdo'),
            'required' => array('submit_listing_region_nb_fields', '=', array('2', '3', '4')),
        ),
        array(
            'id' => 'submit_listing_region_3_field_label',
            'type' => 'text',
            'title' => esc_html__('Third Field Label', 'listdo'),
            'default' => '',
            'description' => esc_html__('Third region field label', 'listdo'),
            'required' => array('submit_listing_region_nb_fields', '=', array('3', '4')),
        ),
        array(
            'id' => 'submit_listing_region_4_field_label',
            'type' => 'text',
            'title' => esc_html__('Fourth Field Label', 'listdo'),
            'default' => '',
            'description' => esc_html__('Fourth region field label', 'listdo'),
            'required' => array('submit_listing_region_nb_fields', '=', array('4')),
        ),
        array(
            'id' => 'listing_general_measurement_settings',
            'icon' => true,
            'type' => 'info',
            'raw' => '<h3> '.esc_html__('Measurement Settings', 'listdo').'</h3>',
        ),
        array(
            'id' => 'listing_distance_unit',
            'type' => 'text',
            'title' => esc_html__('Distance Unit', 'listdo'),
            'default' => 'ft',
        ),
        array(
            'id' => 'listing_general_hour_settings',
            'icon' => true,
            'type' => 'info',
            'raw' => '<h3> '.esc_html__('Other Settings', 'listdo').'</h3>',
        ),
        array(
            'id' => 'listing_show_hour_status',
            'type' => 'switch',
            'title' => esc_html__('Show Hour Status', 'listdo'),
            'default' => 1,
        ),
        array(
            'id' => 'listing_show_full_phone',
            'type' => 'switch',
            'title' => esc_html__('Show Full Phone Number', 'listdo'),
            'default' => 0,
        )
    );

    $general_fields = apply_filters('listdo_wp_job_manager_redux_config_general', $general_fields, $sections, $sidebars, $columns);
    $sections[] = array(
        'title' => esc_html__('Listing General', 'listdo'),
        'fields' => $general_fields
    );

    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_wp_job_manager_redux_config_general', 2, 3 );

function listdo_wp_job_manager_redux_config_archive($sections, $sidebars, $columns) {
    
    // Archive Listings settings
    $sections[] = array(
        'title' => esc_html__('Listing Archives', 'listdo'),
        'fields' => array(
            array(
                'id' => 'listing_archive_layout_version',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Layout Style', 'listdo'),
                'options' => array(
                    'half-map' => array(
                        'title' => esc_html__('Half Map 1', 'listdo'),
                        'img' => get_template_directory_uri() . '/images/archive-layouts/half-map-v1.png'
                    ),
                    'half-map-v2' => array(
                        'title' => esc_html__('Half Map 2', 'listdo'),
                        'img' => get_template_directory_uri() . '/images/archive-layouts/half-map-v1.png'
                    ),
                    'half-map-v3' => array(
                        'title' => esc_html__('Half Map 3', 'listdo'),
                        'img' => get_template_directory_uri() . '/images/archive-layouts/half-map-v1.png'
                    ),
                    'half-map-v4' => array(
                        'title' => esc_html__('Half Map 4', 'listdo'),
                        'img' => get_template_directory_uri() . '/images/archive-layouts/half-map-v1.png'
                    ),
                    'default' => array(
                        'title' => esc_html__('Default', 'listdo'),
                        'img' => get_template_directory_uri() . '/images/archive-layouts/default.png'
                    ),
                ),
                'default' => 'half-map',
            ),
            array(
                'id' => 'listing_archive_display_mode',
                'type' => 'select',
                'title' => esc_html__('Default Display Mode', 'listdo'),
                'options' => array(
                    'grid' => esc_html__('Grid', 'listdo'),
                    'list' => esc_html__('List 1', 'listdo'),
                    'list-v2' => esc_html__('List 2', 'listdo'),
                    'list-v3' => esc_html__('List 3', 'listdo'),
                ),
                'default' => 'grid',
            ),
            array(
                'id' => 'listing_columns',
                'type' => 'select',
                'title' => esc_html__('Grid Listing Columns', 'listdo'),
                'options' => $columns,
                'default' => 2,
                'required' => array('listing_archive_display_mode', '=', 'grid'),
            ),
            array(
                'id' => 'listing_filter_sortby_default',
                'type' => 'select',
                'title' => esc_html__('Default Sortby', 'listdo'),
                'options' => array(
                    'default' => esc_html__( 'Default Order', 'listdo' ),
                    'date-desc' => esc_html__( 'Newest First', 'listdo' ),
                    'date-asc' => esc_html__( 'Oldest First', 'listdo' ),
                    'rating-desc' => esc_html__( 'Highest Rating', 'listdo' ),
                    'rating-asc' => esc_html__( 'Lowest Rating', 'listdo' ),
                    'random' => esc_html__( 'Random', 'listdo' ),
                ),
                'default' => 'default'
            ),
            array(
                'id' => 'listing_show_loadmore',
                'type' => 'switch',
                'title' => esc_html__('Show Load More Button ?', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_show_cat_title',
                'type' => 'switch',
                'title' => esc_html__('Show Category Title ?', 'listdo'),
                'default' => 0,
            ),
            array(
                'id' => 'listing_show_cat_description',
                'type' => 'switch',
                'title' => esc_html__('Show Category Description ?', 'listdo'),
                'default' => 0,
            ),
            array(
                'id' => 'sidebar_position',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Sidebar Position', 'listdo').'</h3>',
                'required' => array('listing_archive_layout_version', '=', array('default')),
            ),
            array(
                'id' => 'listing_archive_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Sidebar Layout', 'listdo'),
                'subtitle' => esc_html__('Select a sidebar layout', 'listdo'),
                'options' => array(
                    'main' => array(
                        'title' => esc_html__('Main Content', 'listdo'),
                        'alt' => esc_html__('Main Content', 'listdo'),
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
                'default' => 'left-main',
                'required' => array('listing_archive_layout_version', '=', array('default')),
            ),
            array(
                'id' => 'listing_archive_sidebar',
                'type' => 'select',
                'title' => esc_html__('Listings Sidebar', 'listdo'),
                'subtitle' => esc_html__('Choose a sidebar for listings sidebar.', 'listdo'),
                'options' => $sidebars,
                'default' => 'listing-archive-sidebar',
                'required' => array('listing_archive_layout_version', '=', array('default')),
            ),
            array(
                'id' => 'archive_breadcrumbs_settings',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Breadcrumbs Settings', 'listdo').'</h3>',
            ),
            array(
                'id' => 'show_listing_breadcrumbs',
                'type' => 'switch',
                'title' => esc_html__('Breadcrumbs', 'listdo'),
                'default' => 1,
                'description' => esc_html__('Breadcrumb is only apply for Listing Archives version (List, Grid)', 'listdo'),
            ),
            array(
                'title' => esc_html__('Breadcrumbs Background Color', 'listdo'),
                'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'listdo').'</em>',
                'id' => 'listing_breadcrumb_color',
                'type' => 'color',
                'transparent' => false,
            ),
            array(
                'id' => 'listing_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumbs Background', 'listdo'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'listdo'),
            ),
            
        )
    );
    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_wp_job_manager_redux_config_archive', 3, 3 );

function listdo_wp_job_manager_redux_config_detail($sections, $sidebars, $columns) {
    
    $sections[] = array(
        'title' => esc_html__('Listing Detail', 'listdo'),
        'fields' => array(
            array(
                'id' => 'listing_single_layout_version',
                'type' => 'select',
                'compiler' => true,
                'title' => esc_html__('Layout Style', 'listdo'),
                'options' => array( 'v1' => esc_html__('Version 1', 'listdo'),
                    'v2' => esc_html__('Version 2', 'listdo'),
                ),
                'default' => 'v1',
            ),
            array(
                'id' => 'listing_single_transparent_header',
                'type' => 'switch',
                'title' => esc_html__('Transparent header ?', 'listdo'),
                'default' => 1,
            ),
            array(
                'id'        => 'listing_single_sort_content',
                'type'      => 'sorter',
                'title'     => esc_html__( 'Listing Content', 'listdo' ),
                'subtitle'  => esc_html__( 'Please drag and arrange the block', 'listdo' ),
                'options'   => array(
                    'enabled' => listdo_get_default_blocks_content(),
                    'disabled' => array()
                )
            ),
            array(
                'id'        => 'listing_single_sort_sidebar',
                'type'      => 'sorter',
                'title'     => esc_html__( 'Listing Sidebar', 'listdo' ),
                'subtitle'  => esc_html__( 'Please drag and arrange the block', 'listdo' ),
                'options'   => array(
                    'enabled' => listdo_get_default_blocks_sidebar_content(),
                    'disabled' => array()
                )
            ),
            array(
                'id' => 'show_listing_social_share',
                'type' => 'switch',
                'title' => esc_html__('Show Social Share', 'listdo'),
                'default' => 1
            )

        )
    );
    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_wp_job_manager_redux_config_detail', 4, 3 );


function listdo_wp_job_manager_redux_config_filter($sections, $sidebars, $columns) {
    
    $sections[] = array(
        'title' => esc_html__('Listing Filter Settings', 'listdo'),
        'fields' => array(
            
            array(
                'id' => 'listing_filter_show_categories',
                'type' => 'switch',
                'title' => esc_html__('Show categories field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_show_types',
                'type' => 'switch',
                'title' => esc_html__('Show types field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_show_regions',
                'type' => 'switch',
                'title' => esc_html__('Show regions field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_show_location',
                'type' => 'switch',
                'title' => esc_html__('Show location field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_show_distance',
                'type' => 'switch',
                'title' => esc_html__('Show distance field', 'listdo'),
                'default' => 1,
                'required' => array('listing_filter_show_location', '=', 1),
            ),
            array(
                'id' => 'listing_filter_distance_default',
                'type' => 'text',
                'title' => esc_html__('Distance default', 'listdo'),
                'default' => 50,
                'required' => array('listing_filter_show_location', '=', 1),
            ),
            array(
                'id' => 'listing_filter_distance_unit',
                'type' => 'select',
                'title' => esc_html__('Distance Unit', 'listdo'),
                'options' => array(
                    'km' => esc_html__('Kilometre', 'listdo'),
                    'miles' => esc_html__('Miles', 'listdo'),
                ),
                'default' => 'km',
            ),
            array(
                'id' => 'listing_filter_show_price_range',
                'type' => 'switch',
                'title' => esc_html__('Show price range field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_show_price_slider',
                'type' => 'switch',
                'title' => esc_html__('Show price slider field', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_filter_price_min_default',
                'type' => 'text',
                'title' => esc_html__('Price Min default', 'listdo'),
                'default' => 0,
                'required' => array('listing_filter_show_price_slider', '=', 1),
            ),
            array(
                'id' => 'listing_filter_price_max_default',
                'type' => 'text',
                'title' => esc_html__('Price Max default', 'listdo'),
                'default' => 1000000,
                'required' => array('listing_filter_show_price_slider', '=', 1),
            ),
            array(
                'id' => 'listing_filter_show_amenities',
                'type' => 'switch',
                'title' => esc_html__('Show amenities field', 'listdo'),
                'default' => 1,
            ),
        )
    );
    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_wp_job_manager_redux_config_filter', 5, 3 );

function listdo_wp_job_manager_redux_config($sections, $sidebars, $columns) {
    
    // review
    $sections[] = array(
        'title' => esc_html__('Listing Review Settings', 'listdo'),
        'fields' => array(
            array(
                'id' => 'listing_review_enable',
                'type' => 'switch',
                'title' => esc_html__('Enable Review', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_enable_upload_image',
                'type' => 'switch',
                'title' => esc_html__('Enable Upload Image', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_enable_rating',
                'type' => 'switch',
                'title' => esc_html__('Enable Rating', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_mode',
                'type' => 'select',
                'title' => esc_html__('Review Mode', 'listdo'),
                'options' => array(
                    '5' => esc_html__('5 Stars', 'listdo'),
                    '10' => esc_html__('10 Stars', 'listdo')
                ),
                'default' => 10,
            ),
            array(
                'id'         => 'listing_review_categories',
                'type'       => 'repeater',
                'title'      => esc_html__( 'Review Categories', 'listdo' ),
                'fields'     => array(
                    array(
                        'id' => 'listing_review_category_title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'listdo'),
                    ),
                    array(
                        'id' => 'listing_review_category_key',
                        'type' => 'text',
                        'title' => esc_html__('Key', 'listdo'),
                    )
                ),
            ),
            array(
                'id' => 'listing_review_enable_like_review',
                'type' => 'switch',
                'title' => esc_html__('Enable Like Review', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_enable_dislike_review',
                'type' => 'switch',
                'title' => esc_html__('Enable DisLike Review', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_enable_love_review',
                'type' => 'switch',
                'title' => esc_html__('Enable Love Review', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_enable_reply_review',
                'type' => 'switch',
                'title' => esc_html__('Enable Reply Review', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'listing_review_edit_review_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Edit Review Settings', 'listdo').'</h3>',
            ),
            array(
                'id' => 'listing_review_enable_user_edit_his_review',
                'type' => 'switch',
                'title' => esc_html__('Allow Registered Users to Edit Comments Indefinitely', 'listdo'),
                'default' => 1,
            )
        )
    );
    // Price Settings
    $sections[] = array(
        'title' => esc_html__('Listing Price Settings', 'listdo'),
        'fields' => array(
            array(
                'id' => 'listing_currency_symbol',
                'type' => 'text',
                'title' => esc_html__('Currency Symbol', 'listdo'),
                'default' => ''
            ),
            array(
                'id' => 'listing_currency_code',
                'type' => 'text',
                'title' => esc_html__('Currency Code', 'listdo'),
                'default' => ''
            ),
            array(
                'id' => 'listing_currency_symbol_after_amount',
                'type' => 'switch',
                'title' => esc_html__('Show Symbol After Amount', 'listdo'),
                'default' => 0,
            ),
            array(
                'id' => 'listing_currency_decimal_places',
                'type' => 'text',
                'title' => esc_html__('Decimal places', 'listdo'),
                'default' => '',
            ),

            array(
                'id' => 'listing_currency_decimal_separator',
                'type' => 'text',
                'title' => esc_html__('Decimal Separator', 'listdo'),
                'default' => ''
            ),
            array(
                'id' => 'listing_currency_thousands_separator',
                'type' => 'text',
                'title' => esc_html__('Thousands Separator', 'listdo'),
                'default' => '',
                'subtitle' => esc_html__('If you need space, enter &nbsp;', 'listdo')
            ),
        )
    );

    

    // sections after listings
    $lsections = apply_filters( 'listdo_redux_config_sections_after_listing', array() );
    if ( !empty($lsections) ) {
        foreach ($lsections as $section) {
            $sections[] = $section;
        }
    }

    // Listing Map Settings
    $sections[] = array(
        'title' => esc_html__('Listing Map Settings', 'listdo'),
        'fields' => array(
            // google map style
            array(
                'id' => 'listing_map_style_type',
                'type' => 'select',
                'title' => esc_html__('Maps Service', 'listdo'),
                'options' => array(
                    'default' => esc_html__('Google Maps', 'listdo'),
                    'mapbox' => esc_html__('MapBox', 'listdo'),
                    'openstreetmap' => esc_html__('OpenStreetMap', 'listdo'),
                ),
                'default' => 'default'
            ),
            array(
                'id' => 'listing_map_custom_style',
                'type' => 'textarea',
                'title' => esc_html__('Custom Style', 'listdo'),
                'description' => wp_kses(__('<a href="//snazzymaps.com/">Get custom style</a> and paste it below. If there is nothing added, we will fallback to the Google Maps service.', 'listdo'), array('a' => array('href' => array()))),
                'required' => array('listing_map_style_type', '=', 'default'),
            ),
            array(
                'id' => 'listing_mapbox_token',
                'type' => 'text',
                'title' => esc_html__('Mapbox Token', 'listdo'),
                'description' => wp_kses(__('<a href="//www.mapbox.com/help/create-api-access-token/">Get a FREE token</a> and paste it below. If there is nothing added, we will fallback to the Google Maps service.', 'listdo'), array('a' => array('href' => array()))),
                'required' => array('listing_map_style_type', '=', 'mapbox'),
            ),
            array(
                'id' => 'listing_mapbox_style',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('MapBox Style', 'listdo'),
                'description' => esc_html__('Custom map styles works only if you have set a valid Mapbox API token in the field above.', 'listdo'),
                'options' => array(
                    'streets-v11' => array(
                        'alt' => esc_html__('streets', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/streets.png'
                    ),
                    'light-v10' => array(
                        'alt' => esc_html__('light', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/light.png'
                    ),
                    'dark-v10' => array(
                        'alt' => esc_html__('dark', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/dark.png'
                    ),
                    'outdoors-v11' => array(
                        'alt' => esc_html__('outdoors', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/outdoors.png'
                    ),
                    'satellite-v9' => array(
                        'alt' => esc_html__('satellite', 'listdo'),
                        'img' => get_template_directory_uri() . '/inc/assets/images/satellite.png'
                    ),
                ),
                'default' => 'streets-v11',
                'required' => array('listing_map_style_type', '=', 'mapbox'),
            ),
            array(
                'id' => 'listing_map_latitude',
                'type' => 'text',
                'title' => esc_html__('Default Latitude', 'listdo'),
                'default' => '54.800685'
            ),
            array(
                'id' => 'listing_map_longitude',
                'type' => 'text',
                'title' => esc_html__('Default Longitude', 'listdo'),
                'default' => '-4.130859'
            ),
            array(
                'id' => 'listing_map_geocoder_country',
                'type' => 'select',
                'title' => esc_html__('Geocoder Country', 'listdo'),
                'options' => listdo_all_countries(),
                'default' => ''
            ),
        )
    );

    $sections[] = array(
        'title' => esc_html__('User Profile', 'listdo'),
        'fields' => array(
            array(
                'id' => 'profile_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('General Settings', 'listdo').'</h3>',
            ),
            array(
                'id' => 'profile_background_image',
                'type' => 'media',
                'title' => esc_html__('Profile Background', 'listdo'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'listdo'),
            ),
            array(
                'id' => 'user_profile_show_contact_form',
                'type' => 'switch',
                'title' => esc_html__('Show Contact Form', 'listdo'),
                'default' => 1,
            ),
            array(
                'id' => 'user_profile_listing_number',
                'type' => 'text',
                'title' => esc_html__('Number of Listings Per Page', 'listdo'),
                'default' => 25,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'user_profile_bookmark_number',
                'type' => 'text',
                'title' => esc_html__('Number of Bookmarks Per Page', 'listdo'),
                'default' => 25,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'user_profile_reviews_number',
                'type' => 'text',
                'title' => esc_html__('Number of Reviews Per Page', 'listdo'),
                'default' => 25,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
            array(
                'id' => 'user_profile_follow_number',
                'type' => 'text',
                'title' => esc_html__('Number of Following/Follower Per Page', 'listdo'),
                'default' => 25,
                'min' => '1',
                'step' => '1',
                'max' => '100',
                'type' => 'slider'
            ),
        )
    );

    $pages = array();
    $posts = get_posts( array(
        'post_type'   => 'page',
        'numberposts' => - 1
    ) );
    if ( $posts ) {
        foreach ( $posts as $post ) {
            $pages[ $post->ID ] = $post->post_title;
        }
    }
    $sections[] = array(
        'title' => esc_html__('User Register Settings', 'listdo'),
        'fields' => array(
            array(
                'id' => 'user_register_setting',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('General Settings', 'listdo').'</h3>',
            ),
            array(
                'id' => 'user_register_requires_approval',
                'type' => 'select',
                'title' => esc_html__('Moderate New User', 'listdo'),
                'options' => array(
                    'auto'  => esc_html__( 'Auto Approve', 'listdo' ),
                    'email_approve' => esc_html__( 'Email Approve', 'listdo' ),
                    'admin_approve' => esc_html__( 'Administrator Approve', 'listdo' ),
                ),
                'description' => esc_html__('Require admin approval of all new user', 'listdo'),
                'default' => 'auto'
            ),

            array(
                'id' => 'user_register_approve_page',
                'type' => 'select',
                'title' => esc_html__('Approve User Page', 'listdo'),
                'options' => $pages,
                'description' => esc_html__('This lets the plugin know the location of the approve page. The [listdo_approve_user] shortcode should be on this page.', 'listdo'),
                'default' => ''
            ),

            // Approve new user register
            array(
                'id' => 'user_register_setting_new_user',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Email Setting: New user register (auto approve)', 'listdo').'</h3>',
            ),
            array(
                'title'    => esc_html__( 'New user register Subject', 'listdo' ),
                'desc'    => esc_html__( 'Enter email subject. You can add variables: {user_name}', 'listdo' ),
                'id'      => 'user_register_auto_approve_subject',
                'type'    => 'text',
                'default' => 'New user register: {user_name}',
            ),
            array(
                'title'    => esc_html__( 'New user register Content', 'listdo' ),
                'desc'    => esc_html__( 'Enter email content. You can add variables: {user_name}, {user_email}, {website_url}, {website_name}', 'listdo' ),
                'id'      => 'user_register_auto_approve_content',
                'type'    => 'editor',
                'default' => '',
            ),
            // Approve new user register
            array(
                'id' => 'user_register_setting_approve',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Email Setting: Approve new user register', 'listdo').'</h3>',
            ),
            array(
                'title'    => esc_html__( 'Approve new user register Subject', 'listdo' ),
                'desc'    => esc_html__( 'Enter email subject. You can add variables: {user_name}', 'listdo' ),
                'id'      => 'user_register_need_approve_subject',
                'type'    => 'text',
                'default' => 'Approve new user register: {user_name}',
            ),
            array(
                'title'    => esc_html__( 'Approve new user register Content', 'listdo' ),
                'desc'    => sprintf(esc_html__( 'Enter email content. You can add variables: %s', 'listdo' ), '{user_name}, {user_email}, {approve_url}, {website_url}, {website_name}' ),
                'id'      => 'user_register_need_approve_content',
                'type'    => 'editor',
                'default' => '',
            ),
            // Approved user register
            array(
                'id' => 'user_register_setting_approved',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Approved user', 'listdo').'</h3>',
            ),
            array(
                'title'    => esc_html__( 'Approved user Subject', 'listdo' ),
                'desc'    => sprintf(esc_html__( 'Enter email subject. You can add variables: %s', 'listdo' ), '{user_name}' ),
                'id'      => 'user_register_approved_subject',
                'type'    => 'text',
                'default' => 'Approve new user register: {user_name}',
            ),
            array(
                'title'    => esc_html__( 'Approved user Content', 'listdo' ),
                'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'listdo' ), '{user_name}, {user_email}, {website_url}, {website_name}' ),
                'id'      => 'user_register_approved_content',
                'type'    => 'editor',
                'default' => '',
            ),
            // Denied user register
            array(
                'id' => 'user_register_setting_denied',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Denied user', 'listdo').'</h3>',
            ),
            array(
                'title'    => esc_html__( 'Denied user Subject', 'listdo' ),
                'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'listdo' ), '{user_name}' ),
                'id'      => 'user_register_denied_subject',
                'type'    => 'text',
                'default' => 'Approve new user register: {user_name}',
            ),
            array(
                'title'    => esc_html__( 'Denied user Content', 'listdo' ),
                'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'listdo' ), '{user_name}, {user_email}, {website_url}, {website_name}' ),
                'id'      => 'user_register_denied_content',
                'type'    => 'editor',
                'default' => '',
            ),
            // Reset Password
            array(
                'id' => 'user_register_setting_reset_password',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Reset Password', 'listdo').'</h3>',
            ),
            array(
                'title'    => esc_html__( 'Reset Password Subject', 'listdo' ),
                'desc'    => sprintf(__( 'Enter email subject. You can add variables: %s', 'listdo' ), '{user_name}' ),
                'id'      => 'user_reset_password_subject',
                'type'    => 'text',
                'default' => 'Your new password',
            ),
            array(
                'title'    => esc_html__( 'Reset Password Content', 'listdo' ),
                'desc'    => sprintf(__( 'Enter email content. You can add variables: %s', 'listdo' ), '{user_name}, {user_email}, {new_password}, {website_url}, {website_name}' ),
                'id'      => 'user_reset_password_content',
                'type'    => 'editor',
                'default' => 'Your new password is: {new_password}',
            ),
        )
    );
    // Email Template
    $email_template_fields = apply_filters( 'listdo_email_template_fields', array());
    if ( !empty($email_template_fields) ) {
        $sections[] = array(
            'title' => esc_html__('Email Templates', 'listdo'),
            'fields' => $email_template_fields
        );
    }
    
    $sections[] = array(
        'title' => esc_html__('Image Sizes', 'listdo'),
        'fields' => array(
            array(
                'id' => 'card_grid_image_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Card grid image', 'listdo').'</h3>',
            ),
            array(
                'id' => 'image_size_card_grid_width',
                'type' => 'text',
                'title' => esc_html__('Card grid image width', 'listdo'),
                'default' => '350',
            ),
            array(
                'id' => 'image_size_card_grid_height',
                'type' => 'text',
                'title' => esc_html__('Card grid image height', 'listdo'),
                'default' => '200',
            ),
            array(
                'id' => 'card_list_image_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Card list image', 'listdo').'</h3>',
            ),
            array(
                'id' => 'image_size_card_list_width',
                'type' => 'text',
                'title' => esc_html__('Card list image width', 'listdo'),
                'default' => '340',
            ),
            array(
                'id' => 'image_size_card_list_height',
                'type' => 'text',
                'title' => esc_html__('Card list image height', 'listdo'),
                'default' => '260',
            ),
            array(
                'id' => 'thumb_small_image_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Thumb small image', 'listdo').'</h3>',
            ),
            array(
                'id' => 'image_size_thumb_small_width',
                'type' => 'text',
                'title' => esc_html__('Thumb small image width', 'listdo'),
                'default' => '100',
            ),
            array(
                'id' => 'image_size_thumb_small_height',
                'type' => 'text',
                'title' => esc_html__('Thumb small image height', 'listdo'),
                'default' => '100',
            ),
            array(
                'id' => 'gallery_image_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Listing Gallery image', 'listdo').'</h3>',
            ),
            array(
                'id' => 'image_size_gallery_width',
                'type' => 'text',
                'title' => esc_html__('Listing gallery image width', 'listdo'),
                'default' => '480',
            ),
            array(
                'id' => 'image_size_gallery_height',
                'type' => 'text',
                'title' => esc_html__('Listing gallery image height', 'listdo'),
                'default' => '550',
            ),

            array(
                'id' => 'full_image_title',
                'icon' => true,
                'type' => 'info',
                'raw' => '<h3> '.esc_html__('Listing Full image', 'listdo').'</h3>',
            ),
            array(
                'id' => 'image_size_full_width',
                'type' => 'text',
                'title' => esc_html__('Listing full image width', 'listdo'),
                'default' => '1920',
            ),
            array(
                'id' => 'image_size_full_height',
                'type' => 'text',
                'title' => esc_html__('Listing full image height', 'listdo'),
                'default' => '550',
            ),
        )
    );

    return $sections;
}
add_filter( 'listdo_redux_framwork_configs', 'listdo_wp_job_manager_redux_config', 10, 3 );