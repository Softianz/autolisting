<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Packages extends Widget_Base {

	public function get_name() {
        return 'apus_listings_packages';
    }

	public function get_title() {
        return esc_html__( 'Apus Packages', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-listings-elements' ];
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
            'columns',
            [
                'label' => esc_html__( 'Columns', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => 3,
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

        $loop = listdo_get_products( array('product_type' => 'listing_package'));
        $bcol = 12/$columns;
        ?>
        <div class="widget woocommerce widget-subwoo style-slick <?php echo esc_attr($el_class); ?>">
            <?php if ($loop->have_posts()): ?>
                <div class="slick-carousel <?php echo esc_attr($columns < $loop->post_count ? '' : 'hidden-dots'); ?>" data-items="<?php echo esc_attr($columns); ?>" data-smallmedium="2" data-extrasmall="1" data-smallest="1" data-pagination="true" data-nav="false">
                    <?php while ( $loop->have_posts() ) : $loop->the_post(); global $product;
                    ?>
                        <div class="item">
                            <div class="subwoo-inner <?php echo esc_attr( $product->is_featured()?'highlight_product':'' ); ?>">
                                <div class="header-sub">
                                    <div class="inner-sub">
                                        <?php
                                        $icon_class = get_post_meta($product->get_id(), '_listings_icon_class', true);
                                        if ( $icon_class ) {
                                            ?>
                                            <div class="icon-wrapper">
                                                <span class="<?php echo esc_attr($icon_class); ?>"></span>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php if($product->is_featured()){ ?>
                                            <div class="featured">
                                                <span class="bg-theme featured-inner"><?php echo esc_html__('Most Popular','listdo') ?></span>
                                            </div>
                                        <?php } ?>
                                        <h3 class="title"><?php the_title(); ?></h3>
                                    </div>
                                </div>
                                <div class="price"><?php echo (!empty($product->get_price())) ? $product->get_price_html() : esc_html__('Free','listdo'); ?></div>
                                <div class="bottom-sub">
                                    <?php the_content(); ?>
                                    <?php if ( has_excerpt() ) { ?>
                                        <div class="short-des"><?php the_excerpt(); ?></div>
                                    <?php } ?>
                                    <div class="button-action"><?php do_action( 'woocommerce_after_shop_loop_item' ); ?></div>
                                </div>
                            </div>
                        </div>  
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Packages );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Packages );
}