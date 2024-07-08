<?php

class Listdo_User_Short_Profile extends Apus_Widget {
    public $link_options;
    public function __construct() {
        parent::__construct(
            'apus_user_short_profile',
            esc_html__('Apus User Short Profile', 'listdo'),
            array( 'description' => esc_html__( 'Show user short profile', 'listdo' ), )
        );
        $this->widgetName = 'user_short_profile';

        $this->link_options = array(
            'show_user_profile_link' => esc_html__('Show user profile link', 'listdo'),
            'show_user_listings_link' => esc_html__('Show user listings link', 'listdo'),
            'show_user_bookmarks_link' => esc_html__('Show user bookmarks link', 'listdo'),
            'show_user_reviews_link' => esc_html__('Show user reviews link', 'listdo'),
            'show_user_following_link' => esc_html__('Show user following link', 'listdo'),
            'show_user_follower_link' => esc_html__('Show user follower link', 'listdo'),
        );
    }

    public function getTemplate() {
        $this->template = 'user-short-profile.php';
    }

    public function widget( $args, $instance ) {
        $this->display($args, $instance);
    }
    
    public function form( $instance ) {
        $defaults = array(
            'show_user_profile_link' => 'on',
            'show_user_listings_link' => 'on',
            'show_user_bookmarks_link' => 'on',
            'show_user_reviews_link' => 'on',
            'show_user_following_link' => 'on',
            'show_user_follower_link' => 'on',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        foreach ($this->link_options as $key => $label) {
            ?>
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $instance[$key], 'on' ); ?> id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                    name="<?php echo esc_attr($this->get_field_name($key)); ?>" />
                    <label for="<?php echo esc_attr($this->get_field_id($key)); ?>">
                        <?php echo esc_attr($label); ?>
                    </label>
            </p>
            <?php
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        foreach ($this->link_options as $key => $label) {
            $instance[$key] = ( ! empty( $new_instance[$key] ) ) ? strip_tags( $new_instance[$key] ) : '';
        }
        return $instance;

    }
}
if ( function_exists('apus_framework_reg_widget') ) {
    apus_framework_reg_widget('Listdo_User_Short_Profile');
}