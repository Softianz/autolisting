<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Add_Listing_Button extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_add_listing_btn';
    }

	public function get_title() {
        return esc_html__( 'Apus Add Listing Button', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-header-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Settings', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'default' => 'Add Listing',
            ]
        );

        $this->add_control(
            'show_add_listing',
            [
                'label' => esc_html__( 'Show Add Listing Button', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('No', 'listdo'),
                    'always' => esc_html__('Always', 'listdo'),
                    'show_logedin' => esc_html__('Loged in', 'listdo'),
                ],
                'default' => 'always',
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
                'label' => esc_html__( 'Style', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'listdo' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .btn',
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
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );
        
        if ( $show_add_listing == 'always' || ($show_add_listing == 'show_logedin' && is_user_logged_in()) ) {
            $page_id = get_option( 'job_manager_submit_job_form_page_id' );
            $page_id = apply_filters( 'wpjm_page_id', $page_id );
        ?>
            <div class="add-listing <?php echo esc_attr($el_class); ?>">
                <a class="btn btn-theme" href="<?php echo esc_url( get_permalink($page_id) );?>"><i class="ti-plus"></i><?php echo wp_kses_post($title); ?></a>   
            </div>
        <?php }

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Add_Listing_Button );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Add_Listing_Button );
}