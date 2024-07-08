<?php

class QRC_Lite_redirect{


	public function __construct()
	{
		add_action('admin_init', array(
		    $this,'qerc_composer_plugin_redirect'));
	     define( 'QRC_COMPOSER_PLUGIN_ID', 'qr_composer' ); 
	     define( 'QRC_COMPOSER_ORDER_MAIL', 'qrc_ordermail' ); 
	     define( 'QRC_COMPOSER_SHORTCODE', 'qrc_shortcode' ); 
	     define( 'QRC_COMPOSER_VCARDLIST', 'qrc_vcardlist' ); 
	     define( 'QRC_COMPOSER_PRINT_ID', 'qrc_print_pdf' ); 
	     define( 'QRC_COMPOSER_DOWNLOAD_ID', 'qrc_list_view' );

		add_action('admin_enqueue_scripts', array($this,'qrc_admin_theme_style'));

		add_action('login_enqueue_scripts', array($this,'qrc_admin_theme_style'));

	}
	public function qrc_admin_theme_style() {
	        if (isset($_GET['page']) && strpos($_GET['page'], QRC_COMPOSER_PLUGIN_ID) !== false){
	            echo '<style>.update-nag, .updated,.notice.notice-info,#update-nag, .update-nag,.settings-error { display: none !important; }</style>';
	            }
	        if (isset($_GET['page']) && strpos($_GET['page'], QRC_COMPOSER_PRINT_ID) !== false){
	            echo '<style>.update-nag, .updated,.notice.notice-info,.notice-success.settings-error{ display: none !important; }</style>';
	            }
	        if (isset($_GET['page']) && strpos($_GET['page'], QRC_COMPOSER_DOWNLOAD_ID) !== false){

	            echo '<style>.update-nag, .updated,.notice.notice-info,.notice-success.settings-error{ display: none !important; }</style>';
	        
	    }
	 }

	 
	public function qerc_composer_plugin_redirect() {

	if (get_option('qrc_composer_do_activation_edirect', false)) {
	    delete_option('qrc_composer_do_activation_edirect');
	    if(!isset($_GET['activate-multi']))
	    {
	        wp_redirect("admin.php?page=qr_composer");
	    }
	 }
	}

}

if(class_exists('QRC_Lite_redirect')){

	new QRC_Lite_redirect();
}
