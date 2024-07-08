<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Listdo
 * @since Listdo 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<link rel="profile" href="//gmpg.org/xfn/11">

	<?php
	if ( !function_exists( 'wp_site_icon' ) ) {
		$favicon = listdo_get_config('media-favicon');
		if ( (isset($favicon['url'])) && (trim($favicon['url']) != "" ) ) {
	        if (is_ssl()) {
	            $favicon_image_img = str_replace("http://", "https://", $favicon['url']);		
	        } else {
	            $favicon_image_img = $favicon['url'];
	        }
		?>
	    	<link rel="shortcut icon" href="<?php echo esc_url($favicon_image_img); ?>" />
	    <?php } ?>
    <?php } ?>
                  
            <script defer="defer" type="text/javascript" src="https://softianz3.odoo.com/im_livechat/loader/1"></script>
            
            <script defer="defer" type="text/javascript" src="https://softianz3.odoo.com/im_livechat/assets_embed.js"></script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( listdo_get_config('preload', true) ) {
	$preload_icon = listdo_get_config('media-preload-icon');
	$preload_icon_image_img = $style = '';
	if ( (isset($preload_icon['url'])) && (trim($preload_icon['url']) != "" ) ) {
        if (is_ssl()) {
            $preload_icon_image_img = str_replace("http://", "https://", $preload_icon['url']);		
        } else {
            $preload_icon_image_img = $preload_icon['url'];
        }
        $style = 'background-image: url(\''.esc_url($preload_icon_image_img).'\');';
    }
?>
	<div class="apus-page-loading">
        <div class="apus-loader-inner" style="<?php echo esc_attr($style); ?>"></div>
    </div>
<?php } ?>

<div id="wrapper-container" class="wrapper-container">

	<?php
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    }
    ?>
    
	<?php
		if ( listdo_get_config('separate_header_mobile', true) ) {
			get_template_part( 'headers/mobile/offcanvas-menu' );
			get_template_part( 'headers/mobile/header-mobile' );
		}
	?>

	<?php
		$header = apply_filters( 'listdo_get_header_layout', listdo_get_config('header_type') );
		if ( !empty($header) ) {
			listdo_display_header_builder($header);
		} else {
			get_template_part( 'headers/default' );
		}
	?>
	<div id="apus-main-content">