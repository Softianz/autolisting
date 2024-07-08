<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_listings';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings', 'listdo' );
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
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => 3,
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout Type', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'listdo'),
                    'carousel' => esc_html__('Carousel', 'listdo'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Item Type', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid V1', 'listdo'),
                    'grid-v2' => esc_html__('Grid V2', 'listdo'),
                ),
                'default' => 'grid'
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
            'stretch',
            [
                'label' => esc_html__( 'Stretch Nav', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('None', 'listdo'),
                    'stretch' => esc_html__('Stretch', 'listdo'),
                ),
                'default' => '',
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

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        $category_slugs = !empty($category_slugs) ? array_map('trim', explode(',', $category_slugs)) : array();
        $region_slugs = !empty($region_slugs) ? array_map('trim', explode(',', $region_slugs)) : array();

        $args = array(
            'get_by' => $get_by,
            'post_per_page' => $number,
            'categories' => $category_slugs,
            'regions' => $region_slugs,
        );
        $loop = listdo_get_listings($args);
        if($columns >= 4){
            $smalldesktop = 'data-smalldesktop=3';
            $desktop = 'data-desktop=4';
        }else{
            $smalldesktop = 'data-smalldesktop='.$columns;
            $desktop = 'data-desktop='.$columns;
        }
        ?>
        <div class="widget-listing style-<?php echo esc_attr($layout_type) ?> <?php echo esc_attr($el_class.' '.$stretch); ?> <?php echo esc_attr($show_nav ? 'show-nav' : ''); ?>">
            <?php if ( !empty($title) ): ?>
                <h2 class="widget-title">
                    <?php echo wp_kses_post($title); ?>
                </h2>
            <?php endif; ?>
            <div class="widget-content ">
                <?php if ($layout_type == 'carousel') { ?>
                    <?php if ($loop->have_posts()): ?>
                        <div class="slick-carousel <?php echo esc_attr($columns < $loop->post_count?'':'hidden-dots'); ?>" data-items="<?php echo esc_attr($columns); ?>" <?php echo esc_attr($desktop.' '.$smalldesktop); ?> data-medium="2" data-smallmedium="2" data-extrasmall="1" data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-autoplay="<?php echo esc_attr($autoplay ? 'true' : 'false'); ?>" data-loop="<?php echo esc_attr($infinite_loop ? 'true' : 'false'); ?>">
                            <?php while ( $loop->have_posts() ): $loop->the_post(); global $post; ?>
                                <div class="item">
                                    <?php get_template_part( 'job_manager/loop/'.$item_type); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                <?php } elseif ($layout_type == 'grid') { ?>
                    <div class="row">
                        <?php $count = 1; while ( $loop->have_posts() ): $loop->the_post(); global $post; ?>
                            <div class="full-smallest col-xs-12 col-sm-6 col-md-<?php echo esc_attr(12/$columns);?> <?php echo esc_attr($count%$columns == 1 ? ' md-clearfix ':''); echo esc_attr($count%2 == 1 ? ' sm-clearfix xs-clearfix':''); ?>">
                                <?php get_template_part( 'job_manager/loop/'.$item_type); ?>
                            </div>
                        <?php $count++; endwhile; ?>
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php wp_reset_postdata();
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings );
}