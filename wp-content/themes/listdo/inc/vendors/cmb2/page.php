<?php

if ( !function_exists( 'listdo_page_metaboxes' ) ) {
	function listdo_page_metaboxes(array $metaboxes) {
		global $wp_registered_sidebars;
        $sidebars = array( '' => esc_html__('Choose a sidebar to display', 'listdo') );

        if ( !empty($wp_registered_sidebars) ) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'listdo' )), listdo_get_header_layouts() );
        $footers = array_merge( array('global' => esc_html__( 'Global Setting', 'listdo' )), listdo_get_footer_layouts() );

        $columns = array(
            '' => esc_html__( 'Global Setting', 'listdo' ),
            '1' => esc_html__('1 Column', 'listdo'),
            '2' => esc_html__('2 Columns', 'listdo'),
            '3' => esc_html__('3 Columns', 'listdo'),
            '4' => esc_html__('4 Columns', 'listdo'),
            '6' => esc_html__('6 Columns', 'listdo')
        );

		$prefix = 'apus_page_';

        // Listing Page
        $fields = array(
            array(
                'name' => esc_html__( 'Layout Style', 'listdo' ),
                'id'   => $prefix.'layout_version',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'listdo' ),
                    'half-map' => esc_html__('Half Map 1', 'listdo'),
                    'half-map-v2' => esc_html__('Half Map 2', 'listdo'),
                    'half-map-v3' => esc_html__('Half Map 3', 'listdo'),
                    'half-map-v4' => esc_html__('Half Map 4', 'listdo'),
                    'default' => esc_html__('Default', 'listdo')
                )
            ),
            array(
                'id' => $prefix.'display_mode',
                'type' => 'select',
                'name' => esc_html__('Default Display Mode', 'listdo'),
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'listdo' ),
                    'grid' => esc_html__('Grid', 'listdo'),
                    'list' => esc_html__('List 1', 'listdo'),
                    'list-v2' => esc_html__('List 2', 'listdo'),
                    'list-v3' => esc_html__('List 3', 'listdo'),
                )
            ),
            array(
                'id' => $prefix.'listing_columns',
                'type' => 'select',
                'name' => esc_html__('Grid Listing Columns', 'listdo'),
                'options' => $columns,
            ),
            array(
                'id' => $prefix.'sortby_default',
                'type' => 'select',
                'name' => esc_html__('Default Sortby', 'listdo'),
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'listdo' ),
                    'default' => esc_html__( 'Default Order', 'listdo' ),
                    'date-desc' => esc_html__( 'Newest First', 'listdo' ),
                    'date-asc' => esc_html__( 'Oldest First', 'listdo' ),
                    'rating-desc' => esc_html__( 'Highest Rating', 'listdo' ),
                    'rating-asc' => esc_html__( 'Lowest Rating', 'listdo' ),
                    'random' => esc_html__( 'Random', 'listdo' ),
                ),
            ),
        );
        
        $metaboxes[$prefix . 'listing_setting'] = array(
            'id'                        => $prefix . 'listing_setting',
            'title'                     => esc_html__( 'Listings Settings', 'listdo' ),
            'object_types'              => array( 'page' ),
            'context'                   => 'normal',
            'priority'                  => 'high',
            'show_names'                => true,
            'fields'                    => $fields
        );

	    $fields = array(
			array(
				'name' => esc_html__( 'Select Layout', 'listdo' ),
				'id'   => $prefix.'layout',
				'type' => 'select',
				'options' => array(
					'main' => esc_html__('Main Content Only', 'listdo'),
					'left-main' => esc_html__('Left Sidebar - Main Content', 'listdo'),
					'main-right' => esc_html__('Main Content - Right Sidebar', 'listdo'),
				)
			),
			array(
                'id' => $prefix.'fullwidth',
                'type' => 'select',
                'name' => esc_html__('Is Full Width?', 'listdo'),
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__('No', 'listdo'),
                    'yes' => esc_html__('Yes', 'listdo')
                )
            ),
            array(
                'id' => $prefix.'sidebar',
                'type' => 'select',
                'name' => esc_html__('Sidebar', 'listdo'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'show_breadcrumb',
                'type' => 'select',
                'name' => esc_html__('Show Breadcrumb?', 'listdo'),
                'options' => array(
                    'no' => esc_html__('No', 'listdo'),
                    'yes' => esc_html__('Yes', 'listdo')
                ),
                'default' => 'yes',
            ),
            array(
                'id' => $prefix.'description',
                'type' => 'text',
                'name' => esc_html__('Description for Breadcrumb', 'listdo'),
            ),
            array(
                'id' => $prefix.'breadcrumb_color',
                'type' => 'colorpicker',
                'name' => esc_html__('Breadcrumb Background Color', 'listdo')
            ),
            array(
                'id' => $prefix.'breadcrumb_image',
                'type' => 'file',
                'name' => esc_html__('Breadcrumb Background Image', 'listdo')
            ),
            array(
                'id' => $prefix.'header_type',
                'type' => 'select',
                'name' => esc_html__('Header Layout Type', 'listdo'),
                'description' => esc_html__('Choose a header for your website.', 'listdo'),
                'options' => $headers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_transparent',
                'type' => 'select',
                'name' => esc_html__('Header Transparent?', 'listdo'),
                'options' => array(
                    'no' => esc_html__('No', 'listdo'),
                    'yes' => esc_html__('Yes', 'listdo')
                ),
                'default' => 'no',
            ),
            array(
                'id' => $prefix.'footer_type',
                'type' => 'select',
                'name' => esc_html__('Footer Layout Type', 'listdo'),
                'description' => esc_html__('Choose a footer for your website.', 'listdo'),
                'options' => $footers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'extra_class',
                'type' => 'text',
                'name' => esc_html__('Extra Class', 'listdo'),
                'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'listdo')
            )
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'Display Settings', 'listdo' ),
			'object_types'              => array( 'page' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

        
        
	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'listdo_page_metaboxes' );

if ( !function_exists( 'listdo_cmb2_style' ) ) {
	function listdo_cmb2_style() {
		wp_enqueue_style( 'listdo-cmb2-style', get_template_directory_uri() . '/inc/vendors/cmb2/assets/style.css', array(), '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'listdo_cmb2_style' );