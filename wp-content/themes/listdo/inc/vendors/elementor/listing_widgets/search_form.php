<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Place_Search_Form extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_search_form';
    }

	public function get_title() {
        return esc_html__( 'Apus Search Form', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-listings-elements' ];
    }

    public function get_fields() {
        $fields = [
            'keywords' => esc_html__( 'Search Keyword', 'listdo' )
        ];

        if ( listdo_get_config('listing_filter_show_categories') ) {
            $fields['categories'] = esc_html__( 'Search Category', 'listdo' );
        }

        if ( get_option( 'job_manager_enable_types' ) && listdo_get_config('listing_filter_show_types') ) {
            $fields['types'] = esc_html__( 'Search Type', 'listdo' );
        }

        if ( listdo_get_config('listing_filter_show_regions') ) {
            $fields['regions'] = esc_html__( 'Search Region', 'listdo' );
        }

        if ( listdo_get_config('listing_filter_show_location') ) {
            $fields['location'] = esc_html__( 'Search Location', 'listdo' );
        }

        return apply_filters('listdo-elements-get-fields', $fields);
    }

	protected function register_controls() {
        $fields = $this->get_fields();
        $search_fields = array( '' => esc_html__('Choose a field', 'listdo') );
        foreach ($fields as $key => $field) {
            $search_fields[$key] = $key;
        }
        $repeater = new Elementor\Repeater();

        $repeater->add_control(
            'filter_field',
            [
                'label' => esc_html__( 'Filter field', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $search_fields
            ]
        );
        
        $repeater->add_control(
            'placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
            ]
        );

        $repeater->add_control(
            'enable_autocompleate_search',
            [
                'label' => esc_html__( 'Enable autocompleate search', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'listdo' ),
                'label_off' => esc_html__( 'No', 'listdo' ),
                'condition' => [
                    'filter_field' => 'keywords',
                ],
            ]
        );

        $repeater->add_control(
            'show_search_suggestions',
            [
                'label' => esc_html__( 'Enable suggestions search', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'listdo' ),
                'label_off' => esc_html__( 'No', 'listdo' ),
                'condition' => [
                    'filter_field' => 'keywords',
                ],
            ]
        );

        $columns = array();
        for ($i=1; $i <= 12 ; $i++) { 
            $columns[$i] = sprintf(esc_html__('%d Columns', 'listdo'), $i);
        }
        $repeater->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'default' => 3
            ]
        );

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Search Form', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'search_fields',
            [
                'label' => esc_html__( 'Search Fields', 'listdo' ),
                'type' => Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'filter_btn_text',
            [
                'label' => esc_html__( 'Button Text', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Search',
            ]
        );

        $this->add_control(
            'btn_columns',
            [
                'label' => esc_html__( 'Button Columns', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'default' => 3
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'listdo' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'listdo' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'listdo' ),
                ]
            );

            $this->add_control(
                'button_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .search-submit' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'background_color',
                [
                    'label' => esc_html__( 'Background Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .search-submit' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'listdo' ),
                ]
            );

            $this->add_control(
                'hover_color',
                [
                    'label' => esc_html__( 'Text Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .search-submit:hover, {{WRAPPER}} .search-submit:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_background_hover_color',
                [
                    'label' => esc_html__( 'Background Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .search-submit:hover, {{WRAPPER}} .search-submit:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_hover_border_color',
                [
                    'label' => esc_html__( 'Border Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .search-submit:hover, {{WRAPPER}} .search-submit:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .search-submit',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-listingsearch listingsearch-horizontal <?php echo esc_attr($el_class); ?>">
            <?php get_job_manager_template( 'job-filters-simple.php', $settings ); ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Place_Search_Form );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Place_Search_Form );
}