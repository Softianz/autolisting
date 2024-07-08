<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Woo_Header_Info extends Elementor\Widget_Base {

    public function get_name() {
        return 'apus_woo_header';
    }

    public function get_title() {
        return esc_html__( 'Apus Header Woo Button', 'listdo' );
    }
    
    public function get_categories() {
        return [ 'listdo-header-elements' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hide_cart',
            [
                'label' => esc_html__( 'Hide Cart', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'listdo' ),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'listdo' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'listdo' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'listdo' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
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
            
        //--------------------------------------

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color Icon', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .mini-cart' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Color Icon Hover ', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .mini-cart:hover, {{WRAPPER}} .mini-cart:focus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_counter_color',
            [
                'label' => esc_html__( 'Background Color Counter ', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .mini-cart .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Icon Typography', 'listdo' ),
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .mini-cart',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        <div class="header-button-woo <?php echo esc_attr($el_class); ?>">
            <?php
            global $woocommerce;
            if ( $hide_cart && is_object($woocommerce) && is_object($woocommerce->cart) ) {
            ?>
                <div class="apus-top-cart cart">
                    <a class="dropdown-toggle mini-cart" href="javascript:void(0)" title="<?php esc_attr_e('View your shopping cart', 'listdo'); ?>">
                        <i class="flaticon-shopping-bag"></i>
                        <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <span class="total-minicart hidden"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="widget_shopping_cart_content">
                            <?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Woo_Header_Info );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Woo_Header_Info );
}