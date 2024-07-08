<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Testimonials extends Widget_Base {

	public function get_name() {
        return 'apus_testimonials';
    }

	public function get_title() {
        return esc_html__( 'Apus Testimonials', 'listdo' );
    }

	public function get_icon() {
        return 'eicon-testimonial';
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

        $repeater = new Repeater();

        $repeater->add_control(
            'content', [
                'label' => esc_html__( 'Content', 'listdo' ),
                'type' => Controls_Manager::TEXTAREA
            ]
        );

        $repeater->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Choose Image', 'listdo' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Brand Image', 'listdo' ),
            ]
        );
        $repeater->add_control(
            'name',
            [
                'label' => esc_html__( 'Name', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'John Doe',
            ]
        );

        $repeater->add_control(
            'job',
            [
                'label' => esc_html__( 'Job', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Designer',
            ]
        );

        $repeater->add_control(
            'reviews',
            [
                'label' => esc_html__( 'Reviews', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link To', 'listdo' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Enter your social link here', 'listdo' ),
                'placeholder' => esc_html__( 'https://your-link.com', 'listdo' ),
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__( 'Testimonials', 'listdo' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'listdo' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'on',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'listdo' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'listdo' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'listdo' ),
                'label_off'     => esc_html__( 'No', 'listdo' ),
                'return_value'  => true,
                'default'       => true,
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'listdo' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'listdo' ),
                'label_off'     => esc_html__( 'No', 'listdo' ),
                'return_value'  => true,
                'default'       => true,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
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
                'label' => esc_html__( 'Name Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .name-client' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Name Typography', 'listdo' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .name-client',
            ]
        );

        $this->add_control(
            'test_title_color',
            [
                'label' => esc_html__( 'Testimonial Title Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .name-client a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Testimonial Title Typography', 'listdo' ),
                'name' => 'test_title_typography',
                'selector' => '{{WRAPPER}} .name-client a',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'listdo' ),
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
                'label' => esc_html__( 'Content Typography', 'listdo' ),
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label' => esc_html__( 'Job Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Job Typography', 'listdo' ),
                'name' => 'job_typography',
                'selector' => '{{WRAPPER}} .job',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($testimonials) ) {
            ?>
            <div class="widget-testimonials <?php echo esc_attr($el_class); ?>">
                <div class="wrapper-testimonial-thumbnail">
                    <div class="slick-carousel testimonial-thumbnail" data-centermode="true" data-items="5" data-smallmedium="3" data-extrasmall="3" data-pagination="false" data-nav="false" data-asnavfor=".testimonial-main" data-slidestoscroll="1" data-focusonselect="true" data-infinite="<?php echo esc_attr($infinite_loop ? 'true' : 'false'); ?>">
                        <?php foreach ($testimonials as $item) { ?>
                            <div class="item">
                                <?php $img_src = ( isset( $item['img_src']['id'] ) && $item['img_src']['id'] != 0 ) ? wp_get_attachment_url( $item['img_src']['id'] ) : ''; ?>
                                <?php if ( $img_src ) { ?>
                                    <div class="wrapper-avarta">
                                        <div class="avarta flex-middle">
                                            <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr(!empty($item['name']) ? $item['name'] : ''); ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="slick-carousel testimonial-main" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="false" data-asnavfor=".testimonial-thumbnail" data-slickparent="true">
                    <?php foreach ($testimonials as $item) { ?>
                        <?php $img_src = ( isset( $item['img_src']['id'] ) && $item['img_src']['id'] != 0 ) ? wp_get_attachment_url( $item['img_src']['id'] ) : ''; ?>
                        
                        <div class="testimonials-item">
                            <div class="info-testimonials">
                                <?php if ( !empty($item['name']) ) {

                                    $title = '<h3 class="name-client">'.$item['name'].'</h3>';
                                    if ( ! empty( $item['link']['url'] ) ) {
                                        $title = sprintf( '<h3 class="name-client"><a href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>%1$s</a></h3>', $item['name'] );
                                    }
                                    echo trim($title);
                                ?>
                                <?php } ?>
                                <?php if ( !empty($item['job']) ) { ?>
                                    <div class="job"><?php echo esc_html($item['job']); ?></div>
                                <?php } ?> 
                            </div>
                            <?php if ( !empty($item['content']) ) { ?>
                                <div class="description"><?php echo trim($item['content']); ?></div>
                            <?php } ?>
                            <?php if ( !empty($item['reviews']) ) { ?>
                                <div class="reviews"><?php echo esc_html($item['reviews']); ?></div>
                            <?php } ?> 
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Testimonials );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Testimonials );
}