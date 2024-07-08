<div id="apus-header-mobile" class="header-mobile hidden-lg clearfix">
    <div class="container">
        <div class="row flex-middle">
            <div class="col-xs-6 left-inner">
                <div class="flex-middle">
                    <?php
                        $logo = listdo_get_config('media-mobile-logo');
                    ?>
                    <?php if( isset($logo['url']) && !empty($logo['url']) ): ?>
                        <div class="logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="logo logo-theme">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
                                <img src="<?php echo esc_url( get_template_directory_uri().'/images/logo.png'); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                </div> 
            </div>
            <div class="col-xs-6">
                <div class="flex-middle">
                    <div class="ali-right">
                        <?php
                        if ( listdo_get_config('show_mini_cart', true) && listdo_is_woocommerce_activated() ) {
                            
                            ?>
                                <div class="apus-top-cart cart">
                                    <a class="dropdown-toggle mini-cart" href="#" title="<?php esc_attr_e('View your shopping cart', 'listdo'); ?>">
                                        <i class="flaticon-shopping-bag"></i>
                                        <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                        <span class="total-minicart hidden"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <div class="widget_shopping_cart_content">
                                            <?php woocommerce_mini_cart(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        ?>

                        <?php
                        if ( listdo_get_config('show_login_register', true) ) {
                            if ( is_user_logged_in() ) {
                            $user_id = get_current_user_id();
                            ?>
                                <div class="setting-account">
                                    <div class="profile-menus">
                                        <div class="profile-avarta"><?php echo get_avatar($user_id, 30); ?></div>
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
                                        <li class="icon-log"><a href="#apus_login_forgot_tab" class="apus-user-login wel-user"><i class="ti-user"></i></a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="active-mobile">
                            <button data-toggle="offcanvas" class="btn btn-sm btn-offcanvas offcanvas" type="button">
                               <i class="flaticon-menu" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>