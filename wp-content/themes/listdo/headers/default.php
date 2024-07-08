<header id="apus-header" class="site-header header-v1 visible-lg" role="banner">
    <div class="<?php echo (listdo_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
        <div class="<?php echo (listdo_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
            <div class="container-fluid">
                <div class="header-inner visible-table">
                    <div class="pull-left">
                        <div class="logo-in-theme">
                            <?php get_template_part( 'template-parts/logo/logo' ); ?>
                        </div>
                    </div>
                    <div class="right-header">
                        <div class="flex-middle">
                            
                            <?php if ( has_nav_menu( 'primary' ) ) : ?>
                                <div class="site-header-mainmenu">
                                    <nav data-duration="400" class="apus-megamenu slide animate navbar" role="navigation">
                                    <?php
                                        $args = array(
                                            'theme_location' => 'primary',
                                            'container_class' => 'collapse navbar-collapse no-padding',
                                            'menu_class' => 'apus-nav navbar-nav megamenu effect1',
                                            'fallback_cb' => '',
                                            'menu_id' => 'primary-menu',
                                            'walker' => new Listdo_Nav_Menu()
                                        );
                                        wp_nav_menu($args);
                                    ?>
                                    </nav>
                                </div>
                            <?php endif; ?>
                            <div class="ali-right">
                                <?php if ( listdo_get_config('show_login_register', true) ) { ?>
                                    <div class="account-buttons">
                                        <?php do_action( 'apus-account-buttons' ); ?>
                                    </div>
                                <?php } ?>
                                <?php if ( listdo_is_wp_job_manager_activated() ): 
                                    $show = listdo_get_config('show_add_listing', 'always');
                                    if ( $show == 'always' || ($show == 'show_logedin' && is_user_logged_in()) ) {
                                ?>
                                        <div class="add-listing">
                                            <a class="btn btn-white" href="<?php echo esc_url( get_permalink(get_option( 'job_manager_submit_job_form_page_id' )) );?>"><i class="ap-add" aria-hidden="true"></i> <?php esc_html_e('Add Listing', 'listdo'); ?></a>   
                                        </div>
                                    <?php } ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>