<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Team extends Widget_Base {

	public function get_name() {
        return 'apus_team';
    }

	public function get_title() {
        return esc_html__( 'Apus Teams', 'listdo' );
    }

    public function get_icon() {
        return 'fa fa-users';
    }

	public function get_categories() {
        return [ 'listdo-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Team', 'listdo' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Social Title', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Social Title' , 'listdo' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'social_icon',
            [
                'label' => esc_html__( 'Icon', 'listdo' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'social',
                'label_block' => true,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
                'recommended' => [
                    'fa-brands' => [
                        'android',
                        'apple',
                        'behance',
                        'bitbucket',
                        'codepen',
                        'delicious',
                        'deviantart',
                        'digg',
                        'dribbble',
                        'elementor',
                        'facebook',
                        'flickr',
                        'foursquare',
                        'free-code-camp',
                        'github',
                        'gitlab',
                        'globe',
                        'google-plus',
                        'houzz',
                        'instagram',
                        'jsfiddle',
                        'linkedin',
                        'medium',
                        'meetup',
                        'mixcloud',
                        'odnoklassniki',
                        'pinterest',
                        'product-hunt',
                        'reddit',
                        'shopping-cart',
                        'skype',
                        'slideshare',
                        'snapchat',
                        'soundcloud',
                        'spotify',
                        'stack-overflow',
                        'steam',
                        'stumbleupon',
                        'telegram',
                        'thumb-tack',
                        'tripadvisor',
                        'tumblr',
                        'twitch',
                        'twitter',
                        'viber',
                        'vimeo',
                        'vk',
                        'weibo',
                        'weixin',
                        'whatsapp',
                        'wordpress',
                        'xing',
                        'yelp',
                        'youtube',
                        '500px',
                    ],
                    'fa-solid' => [
                        'envelope',
                        'link',
                        'rss',
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'listdo' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'is_external' => 'true',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'listdo' ),
            ]
        );

        $this->add_control(
            'name', [
                'label' => esc_html__( 'Member Name', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Member Name' , 'listdo' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'job', [
                'label' => esc_html__( 'Member Job', 'listdo' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Member Job' , 'listdo' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'listdo' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'listdo' ),
            ]
        );

        $this->add_control(
            'description', [
                'label' => esc_html__( 'Member Description', 'listdo' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Member Description' , 'listdo' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'social_icon_list',
            [
                'label' => esc_html__( 'Social Icons', 'listdo' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'social_icon' => [
                            'value' => 'fab fa-facebook',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'social_icon' => [
                            'value' => 'fab fa-google-plus',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
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
                'label' => esc_html__( 'Title', 'listdo' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Background Hover Color', 'listdo' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Core\Schemes\Color::get_type(),
                    'value' => Core\Schemes\Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .social a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );
        $migration_allowed = Icons_Manager::is_migration_allowed();

        ?>
        <div class="widget widget-team <?php echo esc_attr($el_class); ?>">
            <div class="team-item">
                <div class="top-image">
                    <?php
                    if ( !empty($settings['img_src']['id']) ) {
                    ?>
                        <div class="team-image">
                            <?php echo listdo_get_attachment_thumbnail($settings['img_src']['id'], 'full'); ?>
                        </div>
                    <?php } ?>
                </div>
                <?php if ( !empty($socials) ) { ?>
                    <ul class="social-team">
                        <?php
                        foreach ( $settings['social_icon_list'] as $index => $item ) {
                            $migrated = isset( $item['__fa4_migrated']['social_icon'] );
                            $is_new = empty( $item['social'] ) && $migration_allowed;
                            $social = '';

                            // add old default
                            if ( empty( $item['social'] ) && ! $migration_allowed ) {
                                $item['social'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
                            }

                            if ( ! empty( $item['social'] ) ) {
                                $social = str_replace( 'fa fa-', '', $item['social'] );
                            }

                            if ( ( $is_new || $migrated ) && 'svg' !== $item['social_icon']['library'] ) {
                                $social = explode( ' ', $item['social_icon']['value'], 2 );
                                if ( empty( $social[1] ) ) {
                                    $social = '';
                                } else {
                                    $social = str_replace( 'fa-', '', $social[1] );
                                }
                            }
                            if ( 'svg' === $item['social_icon']['library'] ) {
                                $social = '';
                            }

                            $link_key = 'link_' . $index;

                            $this->add_render_attribute( $link_key, 'href', $item['link']['url'] );

                            if ( $item['link']['is_external'] ) {
                                $this->add_render_attribute( $link_key, 'target', '_blank' );
                            }

                            if ( $item['link']['nofollow'] ) {
                                $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                            }
                            ?>
                            <li>
                                <a <?php echo trim($this->get_render_attribute_string( $link_key )); ?>>
                                    <?php
                                    if ( $is_new || $migrated ) {
                                        Icons_Manager::render_icon( $item['social_icon'] );
                                    } else { ?>
                                        <i class="<?php echo esc_attr( $item['social'] ); ?>"></i>
                                    <?php } ?>
                                </a>
                            </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
                <div class="content">
                    
                    <?php if ( !empty($name) ) { ?>
                        <h3 class="name-team"><?php echo wp_kses_post($name); ?></h3>
                    <?php } ?>
                    <?php if ( !empty($job) ) { ?>
                        <div class="job"><?php echo wp_kses_post($job); ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Team );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Team );
}