<?php
    $logo = listdo_get_config('media-logo');
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
            <img class="logo-main" src="<?php echo esc_url( get_template_directory_uri().'/images/logo.png'); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
        </a>
    </div>
<?php endif; ?>