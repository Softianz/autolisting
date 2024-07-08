<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Listdo_Elementor_Listings_Edit_Profile extends Widget_Base {

	public function get_name() {
        return 'apus_listings_edit_profile';
    }

	public function get_title() {
        return esc_html__( 'Apus Edit Profile', 'listdo' );
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
        
        wp_enqueue_style( 'datetimepicker', get_template_directory_uri() . '/css/jquery.datetimepicker.min.css' );
        wp_enqueue_script( 'datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.full.min.js', array('jquery'), false, true );
        wp_enqueue_script( 'wp-job-manager-ajax-file-upload' );

        ?>
        <div class="widget widget-edit-profile <?php echo esc_attr($el_class); ?>">
        <?php
        if ( ! is_user_logged_in() ) {
            ?>
            <div class="box-list-2">
                <div class="text-warning"><?php  esc_html_e( 'Please sign in before accessing this page.', 'listdo' ); ?></div>
            </div>
            <?php
            } else {
                $user = wp_get_current_user();
                $data = get_userdata( $user->ID );
                $avatar = get_the_author_meta( 'apus_user_avatar', $user->ID );
                $avatar_url = wp_get_attachment_image_src($avatar, 'full');
                $address = get_the_author_meta( 'apus_address', $user->ID );
                $birthday = get_the_author_meta( 'apus_birthday', $user->ID );
                $marital_status = get_the_author_meta( 'apus_marital_status', $user->ID );
                $sex = get_the_author_meta( 'apus_sex', $user->ID );
            ?>
                <h3 class="user-name hidden"><?php esc_html_e('Edit Profile', 'listdo'); ?></h3>

                <div class="clearfix space-bottom-30">
                    <form method="post" action="" class="change-profile-form">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                            <div class="box-list">
                                <?php wp_nonce_field('listdo-ajax-edit-profile-nonce', 'security_edit_profile'); ?>
                                <h3 class="title"><i class="flaticon-user"></i><?php echo esc_html__( 'Profile Details', 'listdo' ); ?></h3>
                                <div class="top-info">
                                    <div class="left-inner">
                                        <h3 class="sub"><?php echo esc_html__( 'Change Avatar', 'listdo' ); ?></h3>
                                        <div class="job-manager-uploaded-files">
                                            <?php if (  $avatar ) { ?>
                                                <div class="job-manager-uploaded-file">
                                                    <?php
                                                    $image_src = wp_get_attachment_image_src( absint( $avatar ) );
                                                    $image_src = $image_src ? $image_src[0] : '';

                                                    $extension = ! empty( $extension ) ? $extension : substr( strrchr( $image_src, '.' ), 1 );

                                                    if ( 3 !== strlen( $extension ) || in_array( $extension, array( 'jpg', 'gif', 'png', 'jpeg', 'jpe' ) ) ) : ?>
                                                        <span class="job-manager-uploaded-file-preview"><img src="<?php echo esc_url( $image_src ); ?>" /> <a class="job-manager-remove-uploaded-file" href="#"><i class="fas fa-times" aria-hidden="true"></i></a></span>
                                                    <?php else : ?>
                                                        <span class="job-manager-uploaded-file-name"><code><?php echo esc_html( basename( $image_src ) ); ?></code> <a class="job-manager-remove-uploaded-file" href="#"><i class="fas fa-times"></i></a></span>
                                                    <?php endif; ?>

                                                    <input type="hidden" class="input-text" name="current_user_avatar" value="<?php echo esc_attr( $avatar ); ?>" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <input id="upload-image-avarta" class="widefat wp-job-manager-file-upload input-text hidden" name="user_avatar" type="file" value="<?php echo esc_attr($avatar); ?>" data-file_types="jpg|jpeg|jpe|gif|png"/>
                                        <label for="upload-image-avarta" class="label-can-drag">
                                            <div class="form-group group-upload">
                                                <span class="title-upload"><?php esc_html_e('Drop files to upload', 'listdo'); ?></span>
                                                <span class="break"><?php esc_html_e('or', 'listdo'); ?></span>
                                                <div class="upload-file-btn">
                                                    <i class="flaticon-upload"></i>
                                                    <span><?php esc_html_e('Upload Photo', 'listdo'); ?></span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="right-inner">
                                        <div class="row">
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-first-name" type="text" name="first_name" placeholder="<?php  esc_attr_e( 'First name', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->first_name ) ? esc_attr( $data->first_name ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-last-name" type="text" name="last_name" placeholder="<?php esc_attr_e( 'Last name', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->last_name ) ? esc_attr( $data->last_name ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-nickname" type="text" name="nickname" placeholder="<?php esc_attr_e( 'Nickname', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->nickname ) ? esc_attr( $data->nickname ) : ''; ?>" required="required">
                                                </div>
                                            </div><!-- /.form-group -->
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-email" type="email" name="email" placeholder="<?php esc_attr_e( 'E-mail', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->user_email ) ? esc_attr( $data->user_email ) : ''; ?>"  required="required">
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-phone" type="text" name="phone" placeholder="<?php esc_attr_e( 'Phone', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->phone ) ? esc_attr( $data->phone ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-address" type="text" name="address" placeholder="<?php esc_attr_e( 'Address', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $address ) ? esc_attr( $address ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                    
                                 
                                        <div class="row">
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-url" type="text" name="url" placeholder="<?php esc_attr_e( 'Website', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $data->url ) ? esc_attr( $data->url ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                            <div class="form-group col-sm-6 col-xs-12">
                                                <div class="clearfix">
                                                    <input id="change-profile-form-birthday" type="text" name="birthday" placeholder="<?php esc_attr_e( 'Birthday', 'listdo' ); ?>" class="form-control style2" value="<?php echo ! empty( $birthday ) ? esc_attr( $birthday ) : ''; ?>">
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                        <div class="form-group">
                                            <div class="clearfix">
                                                <textarea id="change-profile-form-about" placeholder="<?php esc_attr_e( 'Biographical Info', 'listdo' ); ?>" class="form-control style2" name="description" cols="70" rows="5"><?php echo ! empty( $data->description ) ? esc_attr( $data->description ) : ''; ?></textarea>
                                            </div>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="box-list">
                                    <h3 class="title"><i class="flaticon-share"></i><?php esc_html_e('My Socials', 'listdo'); ?></h3>
                                    <div class="clearfix">
                                        <?php
                                            $options = listdo_user_social_defaults();
                                            $socials = get_user_meta( $user->ID, 'apus_socials', true );
                                            foreach ($options as $key => $label) {
                                                $value = isset($socials[$key]) ? $socials[$key] : '';
                                                ?>
                                                <div class="form-group">
                                                    <div class="clearfix">
                                                        <input id="change-profile-form-<?php echo esc_attr($key); ?>" type="text" name="socials[<?php echo esc_attr($key); ?>]" placeholder="<?php echo esc_attr($label); ?>" class="form-control style2" value="<?php echo esc_attr( $value ); ?>">
                                                    </div>
                                                </div><!-- /.form-group -->
                                                <?php
                                            }
                                        ?>
                                        <div class="clearfix wrapper-submit">
                                            <button type="submit" name="change_profile_form" class="button btn btn-theme"><?php echo esc_html__( 'Save Changes', 'listdo' ); ?></button>
                                            <div class="msg clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                    <div class="box-list">
                        <h3 class="title"><i class="flaticon-lock"></i><?php esc_html_e('Change password', 'listdo'); ?></h3>
                        <div class="change-password">
                            <form method="post" action="" class="change-password-form">
                                <?php wp_nonce_field('listdo-ajax-change-pass-nonce', 'security_change_pass'); ?>
                                <div class="form-group">
                                    <div class="clearfix">
                                        <input id="change-profile-form-old-pass" placeholder="<?php esc_attr_e( 'Old Password', 'listdo' ); ?>" type="password" name="old_password" class="form-control style2" value="">
                                    </div>
                                </div><!-- /.form-group -->
                                <div class="form-group">
                                    <div class="clearfix">
                                        <input id="change-profile-form-new-pass" placeholder="<?php esc_attr_e( 'New Password', 'listdo' ); ?>" type="password" name="new_password" class="form-control style2" value="">
                                    </div>
                                </div><!-- /.form-group -->
                                <div class="form-group">
                                    <div class="clearfix">
                                        <input id="change-profile-form-retype-pass" placeholder="<?php esc_attr_e( 'Retype Password', 'listdo' ); ?>" type="password" name="retype_password" class="form-control style2" value="">
                                    </div>
                                </div><!-- /.form-group -->
                                <div class="wrapper-submit">
                                    <button type="submit" name="change_password_form" class="button btn btn-theme "><?php echo esc_html__( 'Change Password', 'listdo' ); ?></button>
                                    <div class="msg clearfix"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Listings_Edit_Profile );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Listings_Edit_Profile );
}