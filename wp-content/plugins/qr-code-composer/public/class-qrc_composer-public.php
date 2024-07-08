<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://sharabindu.com
 * @since      2.0.5
 *
 * @package    Qrc_composer
 * @subpackage Qrc_composer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Qrc_composer
 * @subpackage Qrc_composer/public
 * @author     Sharabindu Bakshi <sharabindu86@gmail.com>
 */
class Qrc_composer_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    2.0.5
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    2.0.5
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.5
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = plugin_basename(__FILE__);
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    2.0.5
     */
    public function enqueue_styles()
    {


    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    2.0.5
     */
    public function enqueue_scripts()
    {


         wp_register_script('kjua', QRC_COMPOSER_URL . 'admin/js/kjua.js', array(
            'jquery'
        ) , $this->version, true);

        wp_register_script('qrccreateqr', QRC_COMPOSER_URL . 'public/js/createqr.js', array(
        'jquery','kjua'
        ) , $this->version, true);

        wp_register_script('qrcvcardcontent', QRC_COMPOSER_URL . 'public/js/ajaxqr.js', array(
        'jquery','kjua'
        ) , $this->version, true);
        global $wp;
        $current_id_link = home_url(add_query_arg(array() , $wp->request));
    $options1 = get_option('qrc_composer_settings');

    $qrc_size = isset($options1['qr_code_picture_size_width']) ? $options1['qr_code_picture_size_width'] : 200;

        $quiet = isset($options1['quiet']) ? $options1['quiet'] : '0';
        $ecLevel = isset($options1['ecLevel']) ? $options1['ecLevel'] : 'L';
    $cuttenttitlr = get_the_title();

    $background = (isset($options1['background'])) ? $options1['background'] : 'transparent';
        $qr_color = (isset($options1['qr_color'])) ? $options1['qr_color'] : '#000';

        $download_qr = isset($options1['qr_download_text']) ? $options1['qr_download_text'] : esc_html__('Download QR Code','qr-code-composer');
        $qr_download_hide = isset($options1['qr_download_hide']) ? $options1['qr_download_hide'] : 'no';
        $qr_dwnbtn_color = isset($options1['qr_dwnbtn_color']) ? $options1['qr_dwnbtn_color'] : '#000';
        $qr_dwnbtnbg_color = isset($options1['qr_dwnbtnbg_color']) ? $options1['qr_dwnbtnbg_color'] : '#c8fd8c';

        $qrcomspoer_options = array(
            "background"=> $background,
            "color"=> $qr_color,
            "quiet"=>$quiet,
            "ecLevel"=>$ecLevel,
            'titles' => $cuttenttitlr,
            'size' => $qrc_size,
            'downloadhide' => $qr_download_hide,
            'btnlabel' => $download_qr,
            'btnclr' => $qr_dwnbtn_color,
            'brnbg' => $qr_dwnbtnbg_color,
        );
        wp_localize_script( 'qrccreateqr', 'qrcomspoer_options', $qrcomspoer_options ); 

        wp_localize_script( 'qrcvcardcontent', 'datas', $qrcomspoer_options );


    }

    /**
     * This function is display Qr code on frontend.
     */

    public function qcr_code_element($content)
    {

        $current_id = get_the_ID();
        $current_title = get_the_title($current_id);
        $current_id_link = get_the_permalink($current_id);
        $qrc_meta_display = get_post_meta($current_id, 'qrc_metabox', true) ? get_post_meta($current_id, 'qrc_metabox', true) : 1;

        $qrc_qr_image = '';
        $post_types = get_post_types();
        $options = get_option('qrc_composer_settings');

    $qrc_size = isset($options['qr_code_picture_size_width']) ? $options['qr_code_picture_size_width'] : 200;


        if (!empty($options))
        {
            $singlular_exclude = is_singular($options);
            $single_exclude = is_page($options);
        }
        else
        {
            $singlular_exclude = '';
            $single_exclude = '';  
        }

        $qrc_alignment = isset($options['qrc_select_alignment']) ? $options['qrc_select_alignment'] : 'left';

        if (($qrc_meta_display == '2') or ($singlular_exclude) or is_singular('product') or ($single_exclude))
        {     
            $content .= '';
        }
        else
        {

            $content .= '<div class="qrcprowrapper" style="text-align:'.$qrc_alignment.';" id="qrcwraaerfileds"><div class="qrc_qrcode"  id="qrcwraaerfileds_'.get_the_ID().'" style="display:inline-block" data-text="' . $current_id_link . '"  data-title="' . $current_title . '"></div></div>';
        wp_enqueue_script('kjua');
        wp_enqueue_script('qrccreateqr');
            }


            return $content;



    }

    /**
     * This function is Provide for Createing Woocomerce custom product tab for Qr Code
     */

    public function woo_custom_product_tabs($tabs)
    {

        $options = get_option('qrc_composer_settings');

        $qrc_wc_ptab_name = isset($options['qrc_wc_ptab_name']) ? $options['qrc_wc_ptab_name'] : esc_html__('QR Code','qr-code-composer');

        $tabs['qty_pricing_tab'] = array(
            'title' => $qrc_wc_ptab_name ,
            'priority' => 100,
            'callback' => array(
                $this,
                'woo_qrc_tab_content'
            )
        );

        $qrc_meta_display = get_post_meta(get_the_ID() , 'qrc_metabox', true);
      
        if (!empty($options))
        {
            $singlular_wc_exclude = is_singular($options);
        }
        else
        {
            $singlular_wc_exclude = '';
        }

        if (($qrc_meta_display == '2') or ($singlular_wc_exclude))
        {
            return false;
        }
        else
        {
            return $tabs;

        }

    }

    /**
     *  Woocomerce custom product tab Call Back function
     */

    public function woo_qrc_tab_content()
    {

        $current_title = get_the_title(get_the_id());
        $current_id_link = get_the_permalink(get_the_id());

        $options = get_option('qrc_composer_settings');

        $qrc_wc_alignment = isset($options['qrc_wc_select_alignment']) ? $options['qrc_wc_select_alignment'] : 'left';
        $qrc_size = isset($options['qr_code_picture_size_width']) ? $options['qr_code_picture_size_width'] : 200;
        $qrc_qr_image = '<div class="qrcprowrapper" style="text-align:'.$qrc_wc_alignment.';"><div id="qrc_qrcode_' . get_the_ID() . '" class="qrc_qrcode" style="display:inline-block" data-text="' . $current_id_link . '" data-title="' . $current_title . '"></div></div>';
        wp_enqueue_script('kjua');
        wp_enqueue_script('qrccreateqr');
        return printf('%s', wp_kses_post($qrc_qr_image));
    }

}

