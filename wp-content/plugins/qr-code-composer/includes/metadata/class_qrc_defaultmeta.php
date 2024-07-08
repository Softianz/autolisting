<?php 


/**
 * summary
 */
class QRCDefaultmeta
{
    /**
     * summary
     */
    public function __construct()
    {

        add_action('admin_init', array($this ,'qcr_metabox_page'));
        add_action('save_post', array($this ,'qrc_save_post_meta'));
        
    }

    /**
     *  metabox function
     */

    public function qcr_metabox_page()
    {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page');

        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);

        add_meta_box('qrccompoer_metabox', esc_html__('QR Code Composer', 'qr-code-composer') , array(
            $this,
            'qrc_metabox_func'
        ) , array(
            $post_types
        ));

    }

    /**
     * This is call back function of add_meta_box
     */

    public function qrc_metabox_func($post)
    {

          wp_enqueue_script('kjua');
          wp_enqueue_script('qrccreateqr');

        $qrc_meta_check_info = get_post_meta($post->ID, 'qrc_metabox', true) ? get_post_meta($post->ID, 'qrc_metabox', true) : 1;
        $options = get_option('qrc_composer_settings');
        $qrc_size = isset($options['qr_code_picture_size_width']) ? $options['qr_code_picture_size_width'] : 200;

            $download_qr = isset($options['qr_download_text']) ? $options['qr_download_text'] : 'Download QR';
        
    ?>
        <ul class="qrcomposeroutput_wrap">
        <li class="qrcmetalintitle">
            <h3><?php esc_html_e('Hide QR Code', 'qrc_composer') ?></h3>
            <p><?php esc_html_e('Remove QR code from front end. When you select yes the QR code will be removed from the frontend of this page but will remain here', 'qrc_composer') ?></p>
            </li><li class="qrcmetalinkchekc">
                <select name="qrc_select_field" class=" qrc_metaoutput">
                    
                <option value="1" <?php echo esc_attr($qrc_meta_check_info) == 1 ? 'selected' : '' ?>><?php esc_html_e('No', 'qrc_composer'); ?></option>
                <option value="2" <?php echo esc_attr($qrc_meta_check_info) == 2 ? 'selected' : '' ?>><?php esc_html_e('Yes', 'qrc_composer'); ?></option>

                </select>
            </li>
        <?php

        $current_title = get_the_title(get_the_id());
        $current_id_link = get_the_permalink(get_the_id());

        
             $qr_download_ = '<div><a class="qrcdownload" download="' . esc_attr($current_title) . '.png"><button type="button" style="min-width:' . esc_attr($qrc_size) . 'px;" class="button button-secondary is-button is-default is-large">' . esc_html($download_qr) . '</button>
                </a></div>';
                
        echo  '<li class="qrcmetaqrcode"><div class="qrcprowrapper"><div class="qrc_qrcode"  id="qrcwrausd_'.get_the_ID().'" style="width:'.esc_attr($qrc_size).'px;display:inline-block" data-text="'.esc_attr( $current_id_link) . '" data-title="' . $current_title . '"></div></div></li></ul>';



    }

    /**
     * This function save meta data
     */

    public function qrc_save_post_meta($post_id)
    {

        if (array_key_exists('qrc_select_field', $_POST))
        {

            update_post_meta($post_id, 'qrc_metabox', sanitize_text_field($_POST['qrc_select_field']));
        }

    }
}
if(class_exists('QRCDefaultmeta')){

	new QRCDefaultmeta();
}