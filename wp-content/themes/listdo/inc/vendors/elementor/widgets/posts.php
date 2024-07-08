<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Posts extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_posts';
    }

	public function get_title() {
        return esc_html__( 'Apus Posts', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Posts', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'listdo' ),
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
            'number',
            [
                'label' => esc_html__( 'Number', 'listdo' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'number',
                'description' => esc_html__( 'Number posts to display', 'listdo' ),
                'default' => 4
            ]
        );
        
        $this->add_control(
            'order_by',
            [
                'label' => esc_html__( 'Order by', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'listdo'),
                    'date' => esc_html__('Date', 'listdo'),
                    'ID' => esc_html__('ID', 'listdo'),
                    'author' => esc_html__('Author', 'listdo'),
                    'title' => esc_html__('Title', 'listdo'),
                    'modified' => esc_html__('Modified', 'listdo'),
                    'rand' => esc_html__('Random', 'listdo'),
                    'comment_count' => esc_html__('Comment count', 'listdo'),
                    'menu_order' => esc_html__('Menu order', 'listdo'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Sort order', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'listdo'),
                    'ASC' => esc_html__('Ascending', 'listdo'),
                    'DESC' => esc_html__('Descending', 'listdo'),
                ),
                'default' => ''
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'listdo'),
                    'carousel' => esc_html__('Carousel', 'listdo'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'condition' => [
                    'layout_type' => ['grid', 'carousel'],
                ]
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_special', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                'default' => 'large',
                'separator' => 'none',
                'condition' => [
                    'layout_type' => 'special',
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
                'label' => esc_html__( 'Tyles', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'listdo' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->add_control(
            'post_title_color',
            [
                'label' => esc_html__( 'Post Title Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Title Typography', 'listdo' ),
                'name' => 'post_title_typography',
                'selector' => '{{WRAPPER}} .post .title a',
            ]
        );

        $this->add_control(
            'post_excerpt_color',
            [
                'label' => esc_html__( 'Post Excerpt Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Excerpt Typography', 'listdo' ),
                'name' => 'post_excerpt_typography',
                'selector' => '{{WRAPPER}} .post .description',
            ]
        );

        $this->add_control(
            'post_tag_color',
            [
                'label' => esc_html__( 'Post Tag Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post .tags' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Tag Typography', 'listdo' ),
                'name' => 'post_tag_typography',
                'selector' => '{{WRAPPER}} .post .tags',
            ]
        );

        $this->add_control(
            'post_readmore_color',
            [
                'label' => esc_html__( 'Post Read More Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .post .readmore' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Post Read More Typography', 'listdo' ),
                'name' => 'post_readmore_typography',
                'selector' => '{{WRAPPER}} .post .readmore',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $category_slugs = !empty($category_slugs) ? array_map('trim', explode(',', $category_slugs)) : array();

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $number,
            'orderby' => $order_by,
            'order' => $order,
        );
        if ( !empty($category_slugs) && is_array($category_slugs) ) {
            $args['tax_query'] = array(
                array(
                'taxonomy'      => 'category',
                'field'         => 'slug',
                'terms'         => $category_slugs,
                'operator'      => 'IN'
            ));
        }
        $blog_style = !empty($blog_style) ? $blog_style : 'grid_item';
        $loop = new WP_Query($args);
        if ( $loop->have_posts() ) {
            if ( $image_size == 'custom' ) {
                
                if ( $image_custom_dimension['width'] && $image_custom_dimension['height'] ) {
                    $thumbsize = $image_custom_dimension['width'].'x'.$image_custom_dimension['height'];
                } else {
                    $thumbsize = 'full';
                }
            } else {
                $thumbsize = $image_size;
            }
            
            if ( $image_special_size == 'custom' ) {
                if ( $image_special_custom_dimension['width'] && $image_special_custom_dimension['height'] ) {
                    $special_thumbsize = $image_special_custom_dimension['width'].'x'.$image_special_custom_dimension['height'];
                } else {
                    $special_thumbsize = 'full';
                }
            } else {
                $special_thumbsize = $image_special_size;
            }

            set_query_var( 'thumbsize', $thumbsize );
            ?>
            <div class="widget-blogs <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($el_class); ?>">
                <?php if ( $title ) { ?>
                    <h2 class="title"><?php echo wp_kses_post($title); ?></h2>
                <?php } ?>
                <div class="widget-content">
                    <?php if ( $layout_type == 'carousel' ): ?>
                        <div class="slick-carousel <?php echo esc_attr( ( $loop->post_count <= $columns ) ? 'hidden-dots' : '' ); ?>" data-items="<?php echo esc_attr($columns); ?>" data-medium="2" data-smallmedium="2" data-extrasmall="1" data-pagination="true" data-nav="false">
                            <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                                <div class="item">
                                    <?php get_template_part( 'template-posts/loop/'.$blog_style); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <?php $bcol = 12/$columns; ?>
                        <div class="layout-blog style-grid">
                            <div class="row">
                                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                    <div class="col-md-<?php echo esc_attr($bcol); ?> col-sm-6 col-xs-12">
                                        <?php get_template_part( 'template-posts/loop/'.$blog_style ); ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Posts );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Posts );
}