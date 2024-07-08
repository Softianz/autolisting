<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Call_To_Action extends Widget_Base {

	public function get_name() {
        return 'apus_call_to_action';
    }

	public function get_title() {
        return esc_html__( 'Apus Call To Action', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'listdo' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'listdo' ),
            ]
        );
        $this->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'listdo' ),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your description here', 'listdo' ),
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your button text here', 'listdo' ),
            ]
        );

        $this->add_control(
            'btn_link',
            [
                'label' => esc_html__( 'Button Link', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'listdo' ),
            ]
        );
        
        $this->add_control(
            'btn_style',
            [
                'label' => esc_html__( 'Button Style', 'listdo' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'btn-default' => esc_html__('Default ', 'listdo'),
                    'btn-primary' => esc_html__('Primary ', 'listdo'),
                    'btn-success' => esc_html__('Success ', 'listdo'),
                    'btn-info' => esc_html__('Info ', 'listdo'),
                    'btn-warning' => esc_html__('Warning ', 'listdo'),
                    'btn-danger' => esc_html__('Danger ', 'listdo'),
                    'btn-pink' => esc_html__('Pink ', 'listdo'),
                    'btn-white' => esc_html__('White ', 'listdo'),
                    'btn-dark' => esc_html__('Dark ', 'listdo'),
                    'btn-darkred' => esc_html__('Dark Red', 'listdo'),
                ),
                'default' => 'btn-default'
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
                'label' => esc_html__( 'Tyles', 'listdo' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'listdo' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'listdo' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .description',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Button Typography', 'listdo' ),
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .btn',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-action <?php echo esc_attr($el_class); ?> flex-middle-sm">
            <div class="item-left">
                <?php if( !empty($title) ) { ?>
                    <h2 class="title" >
                       <span><?php echo esc_attr( $title ); ?></span>
                    </h2>
                <?php } ?>
                <?php if ( !empty($description) ) { ?>
                    <div class="description">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php } ?>
            </div>
            <?php if( !empty($btn_link) && !empty($btn_text) ) { ?>
                <div class="action">
                    <a class="btn <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>" href="<?php echo esc_url( $btn_link ); ?>"><?php echo wp_kses_post( $btn_text ); ?><i class="fas fa-long-arrow-alt-right"></i> </a>
                </div>
            <?php } ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Call_To_Action );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Call_To_Action );
}