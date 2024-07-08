<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Category_Banner extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_category_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings Category Banner', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-listings-elements' ];
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
            'title',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Title here', 'listdo' ),
            ]
        );

        $this->add_control(
            'slug',
            [
                'label' => esc_html__( 'Category Slug', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Category Slug here', 'listdo' ),
            ]
        );

        $this->add_control(
            'show_nb_listings',
            [
                'label' => esc_html__( 'Show Number Listings', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
            ]
        );
        
        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'listdo' ),
                'type' => Elementor\Controls_Manager::ICON,
                'fa4compatibility' => 'icon',
                'default' => '',
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Category Image', 'listdo' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'listdo' ),
                'condition' => [
                    'style_box' => 'style1',
                ],
            ]
        );

        $this->add_control(
            'style_box',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'listdo'),
                    'style2' => esc_html__('Style 2', 'listdo'),
                ),
                'default' => 'style1'
            ]
        );

        $this->add_responsive_control(
            'box_align',
            [
                'label' => esc_html__( 'Image Alignment', 'listdo' ),
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'listdo' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'style_box' => 'style2',
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

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'listdo' ),
                'type' => Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__( 'Width', 'listdo' ),
                'type' => Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => esc_html__( 'Margin', 'listdo' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'listdo' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tabs_box_style' );
        
        $this->start_controls_tab(
            'tab_box_normal',
            [
                'label' => esc_html__( 'Normal', 'listdo' ),
            ]
        );

        $this->add_group_control(
        Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'label' => esc_html__( 'Background Overlay', 'listdo' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .category-banner-inner .banner-image::before',
                'condition' => [
                    'style_box' => 'style1',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_box_hover',
            [
                'label' => esc_html__( 'Hover', 'listdo' ),
            ]
        );

        $this->add_group_control(
        Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'overlay_hv_background',
                'label' => esc_html__( 'Background Overlay', 'listdo' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .category-banner-inner .banner-image::after',
                'condition' => [
                    'style_box' => 'style1',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'listdo' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .category-banner-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Items', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Title Typography', 'listdo' ),
                'scheme' => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__( 'Number Typography', 'listdo' ),
                'scheme' => Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .number',
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
                'icon_color',
                [
                    'label' => esc_html__( 'Icon Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .category-icon' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Title Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'number_color',
                [
                    'label' => esc_html__( 'Number Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .number' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
            Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background',
                    'label' => esc_html__( 'Background', 'listdo' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .category-banner-inner',
                    'condition' => [
                        'style_box' => 'style2',
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
                'icon_hv_color',
                [
                    'label' => esc_html__( 'Icon Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .category-banner-inner:hover .category-icon' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'title_hv_color',
                [
                    'label' => esc_html__( 'Title Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .category-banner-inner:hover .title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'number_hv_color',
                [
                    'label' => esc_html__( 'Number Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    
                    'selectors' => [
                        '{{WRAPPER}} .category-banner-inner:hover .number' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
            Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_hover',
                    'label' => esc_html__( 'Background', 'listdo' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .category-banner-inner:hover',
                    'condition' => [
                        'style_box' => 'style2',
                    ],
                ]
            );

            $this->add_control(
                'hover_border_color',
                [
                    'label' => esc_html__( 'Border Color', 'listdo' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .category-banner-inner:hover .category-icon' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .category-icon',
                'separator' => 'before',
                'condition' => [
                    'style_box' => 'style1',
                ],
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( empty($slug) ) {
            return;
        }
        ?>
        <div class="widget-listing-category-banner <?php echo esc_attr($el_class); ?>">
    
            <?php
            $term = get_term_by( 'slug', $slug, 'job_listing_category' );
            if ($term) {
            ?>
                <a href="<?php echo esc_url(get_term_link( $term, 'job_listing_category' )); ?>" >
                    <div class="category-banner-inner <?php echo esc_attr($style_box); ?>">

                            <?php
                            if ( !empty($img_src['id']) ) {
                            ?>
                                <div class="banner-image">
                                    <?php echo listdo_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                                </div>
                            <?php } ?>
                            <div class="content">
                                <?php if ( ! empty( $selected_icon ) ) : ?>
                                    <div class="category-icon">
                                        <i class="<?php echo esc_attr( $selected_icon ); ?>"></i>
                                    </div>
                                <?php endif; ?>

                                <?php if ( !empty($title) ) { ?>
                                    <h4 class="title">
                                        <?php echo trim($title); ?>
                                    </h4>
                                <?php } ?>

                                <?php if ( $show_nb_listings ) {
                                        $args = array(
                                            'tax_query' => array(array(
                                                'taxonomy'      => 'job_listing_category',
                                                'field'         => 'slug',
                                                'terms'         => $term->slug,
                                                'operator'      => 'IN'
                                            )),
                                            'posts_per_page' => 1,
                                            'post_status' => 'publish',
                                            'fields' => 'ids'
                                        );
                                        $query = new WP_Query( $args );
                                        $count = $query->found_posts;
                                ?>
                                    <span class="number"><?php echo sprintf(_n('%d Listing', '%d Listings', $count, 'listdo'), $count); ?></span>
                                <?php } ?>
                            </div>
                    </div>
                </a>
            <?php } ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Category_Banner );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Category_Banner );
}