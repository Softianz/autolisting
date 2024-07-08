<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Maps extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings_maps';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings Maps', 'listdo' );
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
            'number',
            [
                'label' => esc_html__( 'Number listings to show', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => 4
            ]
        );

        $this->add_control(
            'get_by',
            [
                'label' => esc_html__( 'Get Listings By', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'recent' => esc_html__('Recent Listing', 'listdo'),
                    'popular' => esc_html__('Popular Listing', 'listdo'),
                    'featured' => esc_html__('Featured Listing', 'listdo'),
                    'rand' => esc_html__('Random', 'listdo'),
                ),
                'default' => 'recent'
            ]
        );

        $this->add_control(
            'category_slugs',
            [
                'label' => esc_html__( 'Categories Slug', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter id spearate by comma(,)', 'listdo' ),
            ]
        );

        $this->add_control(
            'region_slugs',
            [
                'label' => esc_html__( 'Regions Slug', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => '',
                'placeholder' => esc_html__( 'Enter id spearate by comma(,)', 'listdo' ),
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
                        'min' => 100,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #apus-listing-map' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


    }

	protected function render() {
        $settings = $this->get_settings();
        extract( $settings );
        ?>
        <div class="widget-listing-maps <?php echo esc_attr($el_class); ?>">
            <div id="apus-listing-map" class="apus-listing-map" data-settings="<?php echo esc_attr(json_encode($settings)); ?>"></div>
            <div class="job_listings_cards hidden"></div>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Maps );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Maps );
}