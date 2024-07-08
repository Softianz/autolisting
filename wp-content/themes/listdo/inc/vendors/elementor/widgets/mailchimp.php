<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Mailchimp extends Widget_Base {

	public function get_name() {
        return 'apus_mailchimp';
    }

	public function get_title() {
        return esc_html__( 'Apus MailChimp Sign-Up Form', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'MailChimp Sign-Up Form', 'listdo' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'default' => esc_html__('Default', 'listdo'),
                ),
                'default' => ''
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'listdo' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'listdo' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Button Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => esc_html__( 'Button Background', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn' => 'background: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__( 'Input Background', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} input' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'input_color',
            [
                'label' => esc_html__( 'Input Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} input' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'input_placeholder',
            [
                'label' => esc_html__( 'Input Placeholder ', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__( 'Input Border Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} form .mc4wp-form-fields' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style_hover',
            [
                'label' => esc_html__( 'Hover', 'listdo' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Button Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .btn:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => esc_html__( 'Button Background', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .btn:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .btn:focus' => 'background: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-mailchimp <?php echo esc_attr($el_class.' '.$style); ?>">
            <?php mc4wp_show_form(''); ?>
        </div>
        <?php
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Mailchimp );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Mailchimp );
}