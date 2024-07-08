<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Banner extends Widget_Base {

	public function get_name() {
        return 'apus_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Banner', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Banner', 'listdo' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'img_bg_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image Background', 'listdo' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Background Here', 'listdo' ),
            ]
        );
        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'listdo' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'listdo' ),
            ]
        );

        $this->add_responsive_control(
            'img_align',
            [
                'label' => esc_html__( 'Image Alignment', 'listdo' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'listdo' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .banner-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'URL', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'listdo' ),
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => esc_html__( 'Content', 'listdo' ),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your content here', 'listdo' ),
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text here', 'listdo' ),
            ]
        );

        $this->add_control(
            'btn_style',
            [
                'label' => esc_html__( 'Button Style', 'listdo' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'btn-theme' => esc_html__('Theme Color', 'listdo'),
                    'btn-theme btn-outline' => esc_html__('Theme Outline Color', 'listdo'),
                    'btn-default' => esc_html__('Default ', 'listdo'),
                    'btn-primary' => esc_html__('Primary ', 'listdo'),
                    'btn-success' => esc_html__('Success ', 'listdo'),
                    'btn-info' => esc_html__('Info ', 'listdo'),
                    'btn-warning' => esc_html__('Warning ', 'listdo'),
                    'btn-danger' => esc_html__('Danger ', 'listdo'),
                    'btn-pink' => esc_html__('Pink ', 'listdo'),
                    'btn-white' => esc_html__('White ', 'listdo'),
                ),
                'default' => 'btn-default'
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__( 'Content Alignment', 'listdo' ),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'listdo' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .banner-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'listdo'),
                    'style2' => esc_html__('Style 2', 'listdo'),
                    'style3' => esc_html__('Style 3', 'listdo'),
                    'style4' => esc_html__('Style 4', 'listdo'),
                ),
                'default' => 'style1'
            ]
        );
        $this->add_control(
            'vertical',
            [
                'label' => esc_html__( 'Vertical Content', 'listdo' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'flex-top' => esc_html__('Top', 'listdo'),
                    'flex-middle' => esc_html__('Middle', 'listdo'),
                    'flex-bottom' => esc_html__('Bottom', 'listdo'),
                ),
                'default' => 'flex-middle'
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

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $img_bg_src = ( isset( $img_bg_src['id'] ) && $img_bg_src['id'] != 0 ) ? wp_get_attachment_url( $img_bg_src['id'] ) : '';
        $style_bg = '';
        if ( !empty($img_bg_src) ) {
            $style_bg = 'style="background-image:url('.esc_url($img_bg_src).')"';
        }
        ?>
        <div class="widget-banner updow <?php echo esc_attr($el_class.' '.$style); ?>" <?php echo trim($style_bg); ?>>
            <?php if ( !empty($link) ) { ?>
                <a href="<?php echo esc_url($link); ?>">
            <?php } ?>
                <div class="inner <?php echo esc_attr($vertical); ?>">
                    <?php
                    if ( !empty($img_src['id']) ) {
                    ?>
                        <div class="p-static col-xs-6 col-sm-<?php echo esc_attr(!empty($content) ? '6':'12' ); ?>">
                            <div class="banner-image">
                                <?php echo listdo_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( (!empty($content) && !empty($btn_text)) || !empty($content) ) { ?>
                        <div class="p-static col-xs-6 col-sm-<?php echo esc_attr( (!empty($img_src['id']))? '6':'12' ); ?>">
                            <div class="banner-content">
                                <?php if ( !empty($content) ) { ?>
                                    <?php echo wp_kses_post($content); ?>
                                <?php } ?>
                                <?php if ( !empty($btn_text) ) { ?>
                                    <div class="link-bottom">
                                        <button type="button" class="btn radius-50 <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo wp_kses_post($btn_text); ?></button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ( !empty($btn_text) && empty($content) ) { ?>
                        <button type="button" class="btn radius-50 <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo wp_kses_post($btn_text); ?></button>
                    <?php } ?>
                </div>
            <?php if ( !empty($link) ) { ?>
                </a>
            <?php } ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Banner );
}