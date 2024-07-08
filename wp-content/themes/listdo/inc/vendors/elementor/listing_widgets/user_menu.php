<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_User_Menu extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_user_menu';
    }

	public function get_title() {
        return esc_html__( 'Apus User Profile Menu', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $custom_menus = array();
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
        if ( is_array( $menus ) && ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( is_object( $menu ) && isset( $menu->name, $menu->slug ) ) {
                    $custom_menus[ $menu->slug ] = $menu->name;
                }
            }
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Navigation Menu', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'nav_menu',
            [
                'label' => esc_html__( 'Menu', 'listdo' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $custom_menus,
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
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'listdo' ),
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
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_menu_style',
            [
                'label' => esc_html__( 'Menu Item', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_color',
            [
                'label' => esc_html__( 'Menu Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-content a' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'menu_color_hover',
            [
                'label' => esc_html__( 'Menu Color Hover', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .widget-content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Menu Typography', 'listdo' ),
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .widget-content a',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $menu_id = 0;

        if ( !is_user_logged_in() ) {
            return;
        }
        
        if ($nav_menu) {
            $term = get_term_by( 'slug', $nav_menu, 'nav_menu' );
            if ( !empty($term) ) {
                $menu_id = $term->term_id;
            }
        }
        $user_id = get_current_user_id();
        $data = get_userdata( $user_id );
        $address = get_the_author_meta( 'apus_address', $user_id );
        $registered = $data->user_registered;

        $url = listdo_get_user_url($user_id, $data->user_nicename);
        ?>
        <div class="widget-user-menu widget <?php echo esc_attr($el_class); ?>">
            <div class="wrapper-profile">
                <div class="top-info">
                    <div class="user-avatar">
                        <a href="<?php echo esc_url($url); ?>">
                            <?php echo get_avatar($user_id,180); ?>
                        </a>
                    </div>
                    <div class="wrapper-title">
                        <h3 class="author"><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($data->display_name); ?></a></h3>
                        <?php if ( !empty($address) ) { ?>
                            <div class="address"><?php echo esc_attr($address); ?></div>
                        <?php } ?>
                        <div class="date-created">
                            <?php 
                                printf(esc_html__('Member Since : %s', 'listdo'), date_i18n( "M Y", strtotime( $registered ) )  );
                            ?>
                        </div>
                    </div>
                </div>

                <?php if ( !empty($menu_id) ) { ?>
                    <?php
                        $nav_menu_args = array(
                            'fallback_cb' => '',
                            'menu'        => $menu_id,
                            'walker' => new Listdo_Nav_Menu()
                        );

                        wp_nav_menu( $nav_menu_args, $menu_id );
                    ?>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_User_Menu );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_User_Menu );
}