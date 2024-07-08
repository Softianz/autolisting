<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_My_Reviews extends Widget_Base {

	public function get_name() {
        return 'apus_listings_my_reviews';
    }

	public function get_title() {
        return esc_html__( 'Apus My Reviews', 'listdo' );
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

        ?>
        <div class="widget-my-reviews <?php echo esc_attr($el_class); ?>">
            <div class="box-list">
            <h3 class="title"><i class="flaticon-chat-comment-oval-speech-bubble-with-text-lines"></i><?php esc_html_e( 'My Reviews', 'listdo' ); ?></h3>
            <?php
            if ( ! is_user_logged_in() ) {
                ?>
                <div class="text-warning"><?php  esc_html_e( 'Please sign in before accessing this page.', 'listdo' ); ?></div>
                <?php
            } else {
                $user = wp_get_current_user();
                $args = array(
                    'user_id' => $user->ID,
                    'post_type' => 'job_listing',
                    'status' => 'approve',
                    'meta_query' => array(
                        array(
                           'key' => '_apus_rating',
                           'value' => 0,
                           'compare' => '>',
                        )
                    )
                );
                $comments = get_comments( $args );
                if ( !empty($comments) ) {
                    echo '<div class="box-list-2">';
                        $number = listdo_get_config('user_profile_reviews_number', 25);
                        $max_page = ceil(count($comments)/$number);
                        $page = !empty($_GET['cpage']) ? $_GET['cpage'] : 1;
                        echo '<ul class="list-reviews">';
                            wp_list_comments(array(
                                'per_page' => $number,
                                'page' => $page,
                                'reverse_top_level' => false,
                                'callback' => 'listdo_my_review'
                            ), $comments);
                        echo '</ul>';
                        $pargs = array(
                            'base' => add_query_arg( 'cpage', '%#%' ),
                            'format' => '',
                            'total' => $max_page,
                            'current' => $page,
                            'echo' => true,
                            'add_fragment' => ''
                        );
                        if ( $max_page <= 1 ) {
                            return;
                        }
                        listdo_paginate_links( $pargs );
                    echo '</div>';
                } else {
                    ?>
                    <div class="text-warning"><?php esc_html_e('No reviews found.', 'listdo'); ?></div>
                    <?php
                }
            } ?>
            </div>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_My_Reviews );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_My_Reviews );
}