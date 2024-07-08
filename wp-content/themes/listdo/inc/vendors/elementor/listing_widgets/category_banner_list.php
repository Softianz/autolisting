<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Category_List_Banner extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_category_list_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings Category List Banner', 'listdo' );
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

        $repeater = new Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Title here', 'listdo' ),
            ]
        );

        $repeater->add_control(
            'slug',
            [
                'label' => esc_html__( 'Category Slug', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Type Slug here', 'listdo' ),
            ]
        );

        $repeater->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Category Image', 'listdo' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'listdo' ),
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'listdo' ),
                'type' => Elementor\Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'bg_color',
            [
                'label' => esc_html__( 'Icon Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR
            ]
        );

        $repeater->add_control(
            'url',
            [
                'label' => esc_html__( 'Custom URL', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your custom category url here', 'listdo' ),
            ]
        );

        
        $this->add_control(
            'categories',
            [
                'label' => esc_html__( 'Categories Banner', 'listdo' ),
                'type' => Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
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
            'style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'listdo'),
                ),
                'default' => 'style1'
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'carousel' => esc_html__('Carousel', 'listdo'),
                    'grid' => esc_html__('Grid', 'listdo'),
                ),
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => '4'
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'listdo' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'listdo' ),
                'label_off' => esc_html__( 'Show', 'listdo' ),
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'listdo' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'listdo' ),
                'label_off'     => esc_html__( 'No', 'listdo' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'listdo' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'listdo' ),
                'label_off'     => esc_html__( 'No', 'listdo' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
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
            'section_style',
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
                    '{{WRAPPER}} .category-banner-list, {{WRAPPER}} .banner-image' => 'height: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .banner-image::before',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'hv_background',
                'label' => esc_html__( 'Background Hover', 'listdo' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .category-banner-list:hover .banner-image::before',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( empty($categories) ) {
            return;
        }

        ?>
        <div class="widget-listing-category-list-banner <?php echo esc_attr($style.' '.$el_class); ?>">
            <?php if( $layout_type == 'carousel') { ?>
                <div class="slick-carousel <?php echo esc_attr($columns < count($categories)?'':'hidden-dots'); ?>" data-items="<?php echo esc_attr($columns); ?>" data-medium="2" data-smallmedium="1" data-extrasmall="1" data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-autoplay="<?php echo esc_attr($autoplay ? 'true' : 'false'); ?>" data-loop="<?php echo esc_attr($infinite_loop ? 'true' : 'false'); ?>">
                    <?php foreach ($categories as $category):
                        if ( empty($category['slug']) ) {
                            continue;
                        }
                        $term = get_term_by( 'slug', $category['slug'], 'job_listing_category' );
                        if ($term) {
                            $icon_style = '';
                            if ( !empty($category['bg_color']) ) {
                                $icon_style = 'style=color:'.$category['bg_color'];
                            }
                            $url_cat = get_term_link( $term, 'job_listing_category' );
                            if ( !empty($category['url']) ) {
                                $url_cat = $category['url'];
                            }
                            $title = !empty($category['title']) ? $category['title'] : '';
                            if ( empty($title) ) {
                                $title = $term->name;
                            }
                            ?>
                            <div class="inner">
                                <a href="<?php echo esc_url($url_cat); ?>" >
                                    <div class="category-banner-list <?php echo esc_attr($style); ?>">
                                        
                                        <div class="banner-image">
                                            <?php if ( !empty($category['img_src']['id']) ) { ?>
                                                <?php echo listdo_get_attachment_thumbnail($category['img_src']['id'], 'full'); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="inner">
                                            <div class="left-inner">
                                                <?php 
                                                if ( ! empty( $category['selected_icon'] ) ) :
                                                    ?>
                                                    <div class="category-icon" <?php echo esc_attr($icon_style); ?>>
                                                        <i class="<?php echo esc_attr( $category['selected_icon'] ); ?>"></i>
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
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
            <?php }else{ ?>
                <div class="row">
                    <?php foreach ($categories as $category):
                        if ( empty($category['slug']) ) {
                            continue;
                        }
                        $term = get_term_by( 'slug', $category['slug'], 'job_listing_category' );
                        if ($term) {
                            $icon_style = '';
                            if ( !empty($category['bg_color']) ) {
                                $icon_style = 'style=color:'.$category['bg_color'];
                            }
                            $url_cat = get_term_link( $term, 'job_listing_category' );
                            if ( !empty($category['url']) ) {
                                $url_cat = $category['url'];
                            }
                            $title = !empty($category['title']) ? $category['title'] : '';
                            if ( empty($title) ) {
                                $title = $term->name;
                            }
                            ?>
                            <div class="inner col-xs-6 col-sm-<?php echo esc_attr(12/$columns); ?>">
                                <a href="<?php echo esc_url($url_cat); ?>" >
                                    <div class="category-banner-list <?php echo esc_attr($style); ?>">
                                        
                                        <div class="banner-image">
                                            <?php if ( !empty($category['img_src']['id']) ) { ?>
                                                <?php echo listdo_get_attachment_thumbnail($category['img_src']['id'], 'full'); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="inner">
                                            <div class="left-inner">
                                                <?php 
                                                if ( ! empty( $category['selected_icon'] ) ) :
                                                    ?>
                                                    <div class="category-icon" <?php echo esc_attr($icon_style); ?>>
                                                        <i class="<?php echo esc_attr( $category['selected_icon'] ); ?>"></i>
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
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
            <?php } ?>    
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Category_List_Banner );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Category_List_Banner );
}