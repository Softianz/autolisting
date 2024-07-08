<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Header_Search_Form extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_header_search_form';
    }

	public function get_title() {
        return esc_html__( 'Apus Header Search Form', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-header-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Listings', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        
        $this->add_control(
            'show_search_suggestions',
            [
                'label' => esc_html__( 'Show Search Suggestions', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
            ]
        );

        $this->add_control(
            'enable_autocompleate_search',
            [
                'label' => esc_html__( 'Enable autocompleate search', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '1',
                'label_on' => esc_html__( 'Yes', 'listdo' ),
                'label_off' => esc_html__( 'No', 'listdo' ),
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
                'label' => esc_html__( 'Style Button', 'listdo' ),
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
                        '{{WRAPPER}} .btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'background_color',
                [
                    'label' => esc_html__( 'Background Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn' => 'background-color: {{VALUE}};',
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
                        '{{WRAPPER}} .btn:hover, {{WRAPPER}} .btn:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_background_hover_color',
                [
                    'label' => esc_html__( 'Background Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn:hover, {{WRAPPER}} .btn:focus' => 'background-color: {{VALUE}};',
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
                        '{{WRAPPER}} .btn:hover, {{WRAPPER}} .btn:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .btn',
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_input',
            [
                'label' => esc_html__( 'Style Input', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__( 'Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border_input',
                'selector' => '{{WRAPPER}} input',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );
        $classes = '';
        if ( isset($enable_autocompleate_search) && $enable_autocompleate_search ) {
            wp_enqueue_script( 'handlebars', get_template_directory_uri() . '/js/handlebars.min.js', array(), null, true);
            wp_enqueue_script( 'typeahead-jquery', get_template_directory_uri() . '/js/typeahead.jquery.js', array('jquery', 'handlebars'), null, true);
            $classes = 'apus-autocompleate-input';
        }
        ?>
        <div class="widget-header-listingsearch <?php echo esc_attr($el_class); ?>">
            <form class="job_search_form js-search-form" action="<?php echo listdo_get_listings_page_url(); ?>" method="get" role="search">
                <?php
                    $has_search_menu = false;
                    if ( has_nav_menu( 'suggestions_search' ) && !empty($show_search_suggestions) && $show_search_suggestions )  {
                        $has_search_menu = true;
                    }
                ?>
                <div class="search-field-wrapper  search-filter-wrapper <?php echo esc_attr($has_search_menu ? 'has-suggestion' : ''); ?>">
                    <input class="search-field form-control radius-0 <?php echo esc_attr($classes); ?>" autocomplete="off" type="text" name="search_keywords" placeholder="<?php esc_attr_e( 'What are you looking for?', 'listdo' ); ?>" value="<?php the_search_query(); ?>"/>
                    <?php
                    if ( $has_search_menu ) {
                        $args = array(
                            'theme_location' => 'suggestions_search',
                            'container_class' => 'navbar-collapse navbar-collapse-suggestions',
                            'menu_class' => 'nav search-suggestions-menu',
                            'fallback_cb' => '',
                            'walker' => new Listdo_Nav_Menu()
                        );
                        wp_nav_menu($args);
                    }
                    ?>
                </div>
                <button class="btn btn-search-header radius-0" name="submit">
                    <i class="flaticon-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Header_Search_Form );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Header_Search_Form );
}