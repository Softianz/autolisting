<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_City_Banner extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_city_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings City Banner', 'listdo' );
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
                'label' => esc_html__( 'City Slug', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your City Slug here', 'listdo' ),
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
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'City Image', 'listdo' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'listdo' ),
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'listdo'),
                ),
                'default' => ''
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
                    '{{WRAPPER}} .city-banner-inner' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

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
                'selector' => '{{WRAPPER}} .city-banner-inner .banner-image::before',
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
                'selector' => '{{WRAPPER}} .city-banner-inner .banner-image::after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Items', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Color Number', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color Title', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
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
        <div class="city-banner <?php echo esc_attr($el_class); ?>">
    
            <?php
            $term = get_term_by( 'slug', $slug, 'job_listing_region' );
            if ($term) {
            ?>
                <a class="city-banner-link <?php echo esc_attr($style); ?>" href="<?php echo esc_url(get_term_link( $term, 'job_listing_region' )); ?>" >
                    <div class="city-banner-inner">


                            <?php
                            if ( !empty($img_src['id']) ) {
                            ?>
                                <div class="banner-image">
                                    <?php echo listdo_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                                </div>
                            <?php } ?>

                            <div class="content">
                                <?php if ( !empty($title) ) { ?>
                                    <h4 class="title">
                                        <?php echo trim($title); ?>
                                    </h4>
                                <?php } ?>
                                
                                <?php if ( $show_nb_listings ) {
                                    $args = array(
                                        'tax_query' => array(array(
                                            'taxonomy'      => 'job_listing_region',
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
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_City_Banner );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_City_Banner );
}