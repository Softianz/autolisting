<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_User_Info extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_user_info';
    }

	public function get_title() {
        return esc_html__( 'Apus Header User Info', 'listdo' );
    }
    
	public function get_categories() {
        return [ 'listdo-header-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'listdo' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'listdo' ),
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
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
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .login-account, {{WRAPPER}} .profile-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__( 'Link Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .login-account a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Link Hover Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .login-account a:hover, {{WRAPPER}} .login-account a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'link_color_drop',
            [
                'label' => esc_html__( 'Link Drop Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .user-account a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'link_hover_color_drop',
            [
                'label' => esc_html__( 'Link Drop Hover Color', 'listdo' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .user-account a:hover, {{WRAPPER}} .user-account a:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'listdo' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .login-account',
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $user = get_userdata( $user_id );

            ?>
            <div class="setting-account">
                <div class="profile-menus flex-middle clearfix">
                    <div class="profile-avarta"><?php echo get_avatar($user_id, 40); ?></div>
                    <div class="profile-info">
                        <span><?php echo esc_html($user->data->display_name); ?></span>
                        <span class="fas fa-angle-down show-down"></span>
                    </div>
                </div>
                <div class="user-account">
                    <ul class="user-log">
                        <?php
                            $menu_nav = 'myaccount-menu';
                            if ( has_nav_menu( $menu_nav ) ) {
                                ?>
                                <li>
                                    <?php
                                        $args = array(
                                            'theme_location'  => $menu_nav,
                                            'menu_class'      => 'list-line',
                                            'fallback_cb'     => '',
                                            'walker' => new Listdo_Nav_Menu()
                                        );
                                        wp_nav_menu($args);
                                    ?>
                                </li>
                                <?php
                            } 
                        ?>
                        <li class="last"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><i class="ti-power-off"></i><?php esc_html_e('Log out ','listdo'); ?></a></li>
                    </ul>
                </div>
            </div>
        <?php } else { ?>
            <div class="account-login">
                <ul class="login-account">
                    <li class="icon-log"><i class="ti-user"></i></li>
                    <li><a href="#apus_login_forgot_tab" class="apus-user-login wel-user"><?php esc_html_e( 'Sign in','listdo' ); ?></a></li>
                    <li class="space"><?php echo esc_html__('or','listdo') ?></li>
                    <li><a href="#apus_register_tab" class="apus-user-register wel-user"><?php esc_html_e( 'Register','listdo' ); ?></a></li>
                </ul>
            </div>
        <?php }
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_User_Info );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Listdo_Elementor_User_Info );
}