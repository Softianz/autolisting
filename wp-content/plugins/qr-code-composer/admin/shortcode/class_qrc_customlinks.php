<?php
/**

 *
 * -facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 *
 * @package    qrc_composer_pro
 * @subpackage qrc_composer_pro/admin
 */
class QRCoserpsgortcode{

    public $atts;

    /**
     * @return array
     */
    public function getAtts() {
        return $this->atts;
    }

    /**
     * @param $atts
     */
    public function setAtts( $atts ) {
        $this->atts = $atts;
    }

    public function __construct() {
        add_shortcode( 'qrc_code_composer', [ $this, 'currentoutput' ] );

        add_shortcode( 'qr_link_composer', [ $this, 'output' ] );

        add_shortcode( 'qrc_phonenumber', [ $this, 'phonenumber' ] );
        add_shortcode( 'qr_whatsapp_composer', [ $this, 'whatsapp' ] );      
    }

    public function enqueueDelayScripts($atts) { 

        wp_enqueue_script('kjua');
        wp_enqueue_script('qrccreateqr');

    }


    /**
     * @param $atts
     * @return string
     * @throws Exception
     */
    public function phonenumber( $atts ) {
    static $i = 1;
    $i++;
 
    $options = get_option('qrc_custom_link_generator');
    $number = isset($options['qr_code_phonenumber']) ? $options['qr_code_phonenumber'] : '+98732382';
        $this->enqueueDelayScripts($atts);
        $this->setAtts($atts);
        $numbers = 'tel:'.$number;

        return '<div class="qrcprowrapper" id="qrcwraaer'.$i.'ds"><div class="qrc_qrcode" id="qrcwraurit_'.$i.'" style="display:inline-block" data-text="' . $numbers . '"  data-title="' . $number . '"></div></div>';
    }

   /**
     * @param $atts
     * @return string
     * @throws Exception
     */
    public function currentoutput( $atts ) {
    static $i = 1;
    $i++;
        global $wp;
    $current_id_link = home_url(add_query_arg(array() , $wp->request));

    $current_title = get_the_title(get_the_id());
        $this->enqueueDelayScripts($atts);
    $options1 = get_option('qrc_composer_settings');

        $qrc_alignment = isset($options1['qrc_select_alignment']) ? $options1['qrc_select_alignment'] : 'left';
        return '<div class="qrcprowrapper" style="text-align:'.$qrc_alignment.';" id="qrcwraa'.$i.'leds"><div class="qrc_qrcode" id="qrcwracrtlink_'.$i.'" style="display:inline-block" data-text="' . $current_id_link . '" data-title="' . $current_title . '"></div></div>';

   
    } 
    /**
     * @param $atts
     * @return string
     * @throws Exception
     */
    public function output( $atts ) {
    static $i = 1;
    $i++;
    $options = get_option('qrc_custom_link_generator');
    $text = isset($options['qr_code_custom_text']) ? $options['qr_code_custom_text'] : 'https://sharabindu.com';
        $this->enqueueDelayScripts($atts);
        return '<div class="qrcprowrapper" id="qrcwr'.$i.'xt_"><div class="qrc_qrcode" id="qrcwratxt_'.$i.'" style="display:inline-block" data-text="' . $text . '"   data-title="' . $text . '"></div></div>';

    }    
    /**
     * @param $atts
     * @return string
     * @throws Exception
     */
    public function whatsapp( $atts ) {

    static $i = 1;
    $i++;     
    $options = get_option('qrc_custom_link_generator');

    $whatsapp = isset($options['qr_code_mail_text']) ? $options['qr_code_mail_text'] : '+1895767567';
        $this->enqueueDelayScripts($atts);

    $whatsapps = 'https://wa.me/'.$whatsapp;
            
        return '<div class="qrcprowrapper" id="qrcwrawhat'.$i.'pp_"><div class="qrc_qrcode" id="qrcwrawhatsapp_'.$i.'" style="display:inline-block" data-text="' . $whatsapps . '"  data-title="' . $whatsapp . '"></div></div>';

    }
    

}
new QRCoserpsgortcode();