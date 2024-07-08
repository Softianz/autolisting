<?php
/**
 * The file that defines the bulk qrc_download admin area
 *
 * -facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      3.2.7
 *
 * @package    qrc_composer_pro
 * @subpackage qrc_composer_pro/admin
 */

class QRCLigt_CustomQr{

    public function __construct()
    {

    add_action('admin_init', array($this ,'qrc_custom_link_generator_page'));
}


public function qrc_custom_link_generator_page()
{
   register_setting("qrc_custom_link_generator", "qrc_custom_link_generator", array($this ,'qr_log_option_page_sanitize'));
        

        add_settings_section("logo_qrc_download_section", " ", array($this ,'settting_log_sec_func'), 'qrc_logo_admin_sec');

        add_settings_field("qr_code_custom_text", esc_html__("Custom Link/Text /email/number", "qrc_composer") , array($this ,"qr_code_custom_text"), 'qrc_logo_admin_sec', "logo_qrc_download_section", array(
            'class'  =>  'qr_code_custom_text', 
        'label_for' => 'qr_code_custom_text',

    ));

        add_settings_field("qr_code_phonenumber", esc_html__("QR code for phone number", "qrc_composer") , array($this ,"qr_code_phonenumber"), 'qrc_logo_admin_sec', "logo_qrc_download_section", array(
            'class'  =>  'qr_code_phonenumber', 
        'label_for' => 'qr_code_phonenumber',

    ));

        add_settings_field("qr_code_mail_text", esc_html__("QR for WhatsApp ", "qrc_composer") , array($this ,"qr_code_mail_text"), 'qrc_logo_admin_sec', "logo_qrc_download_section", array(
            'class'  =>  'qr_code_mail_text', 
        'label_for' => 'qr_code_mail_text',

    ));

        add_settings_field("qr_code_wifi_text", esc_html__("QR for Wifi (Premium)", "qrc_composer") , array($this ,"qr_code_wifi_text"), 'qrc_logo_admin_sec', "logo_qrc_download_section", array(
            'class'  =>  'qr_code_wifi_text'

    ));


        add_settings_field("qr_code_maps_text", esc_html__("QR for Maps (Premium)", "qrc_composer") , array($this ,"qr_code_maps_text"), 'qrc_logo_admin_sec', "logo_qrc_download_section" , array(
            'class'  =>  'qr_code_maps_text'

    ));

        add_settings_field("qr_code_event_text", esc_html__("QR for Event (Premium)", "qrc_composer") , array($this ,"qr_code_event_text"), 'qrc_logo_admin_sec', "logo_qrc_download_section", array(
            'class'  =>  'qr_code_event_text'

    ));


}

/**
 * This function is a callback function of  add seeting field
 */

public function qr_code_event_text()
{
     printf('<p><label class="mqrc_labelsum" for="mqrc_labelsum">Summary/ Title:</label> <input type="text" placeholder="Write Summary/ Title" id="mqrc_labelsum" >
           </p><p>
           <label class="mqrc_labelds" for="mqrc_labelds">Description:</label>   
           <textarea placeholder="Write Event Description" id="mqrc_labelds" ></textarea></p><p>

            <label class="mqrc_label" for="mqrc_label">Location:</label>    
            <input type="text" placeholder="Write Event Loaction" id="mqrc_label" ></p><p>

            <label class="mrc_jsdhshd" for="mrc_jsdhshd">Start Date & Time:</label>  

            <input type="text" class="qrcprodemo" id="mrc_jsdhshd">
            <input type="text" class="qrcprodemo1" id="qrcprodemo1"></p><p>
            <label class="mqrc_label" for="qrcprodemo2">End Date & Time:</label>    
            <input type="text" class="qrcprodemo2" id="qrcprodemo2">
            <input type="text" class="qrcprodemo3" id="qrcprodemo3"></p>
            <p class="qrcshortvar">
            <input id="qrevents" type="text" class="shortcodereadoly" value="[qrc_event]" readonly >
            <a class="videurls" id="qrcevent" video-url="https://www.youtube.com/watch?v=5L_XP4qXlj8"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>');



}






/**
 * This function is a callback function of  add seeting section
 */

public function settting_log_sec_func()
{ ?>

<div class="qrc-box-header" >
            <h3 class="sui-box-title"><?php echo esc_html__('QR codes for various components', 'qrc_composer') ?>
</h3>
<p class="vrcomponents"><?php echo esc_html__('Basically,it generates QR codes for the following elements. which are displayed on the frontend through shortcodes. The most interesting thing is that in the premium version you can also generate these QR codes using the', 'qrc_composer') ?> <a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_shortcode"><?php echo esc_html__('Shortcode Generator (Pro).', 'qrc_composer') ?></a></p>
        </div>
        <?php
}

/**
 * This function is a callback function of  add seeting field
 */

public function qr_code_maps_text()
{

    printf('<p><label for="qrc_latitit" class="qrc_label wifi">Latitude</label>
        <input type="text"placeholder="Enter map latitude" id="qrc_latitit" style="min-width:300px"></p><p>
        <label for="qrc_wifilog"  class="qrc_label wifi">Longitude</label> 
            <input type="text" placeholder="Enter map longitude" id="qrc_wifilog" style="min-width:300px"></p><p>  
            <label for="qrc_mapq"  class="qrc_label wifi">Query</label>   
            <input type="text" placeholder="Write Maps Query" id="qrc_mapq" style="min-width:300px"></p>
            <p class="qrcshortvar">
            <input id="qmaps" type="text" class="shortcodereadoly" value="[qr_maps_composer]" readonly >
            <a class="videurls" id="qrcmap" video-url="https://www.youtube.com/watch?v=hvyI2TjrGM4"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>');

}
/**
 * This function is a callback function of  add seeting field
 */

public function qr_code_wifi_text()
{
     printf('<p><label for="qrc_wifi" class="qrc_label wifi">Wifi Name</label>
        <input type="text" placeholder="Write wifi name" id="qrc_wifi" style="min-width:300px"></p><p>
        <label for="qrc_wifi_t"  class="qrc_label wifi">Wifi Type</label> 
            <input type="text" placeholder="Write wifi type" id="qrc_wifi_t" style="min-width:300px"> </p><p>
            <label for="qrc_wifi_p"  class="qrc_label wifi">Wifi Password   </label>    
            <input type="text" placeholder="Write wifi password" id="qrc_wifi_p" style="min-width:300px"></p> 
            <p class="qrcshortvar">
            <input id="wifiexre" type="text" class="shortcodereadoly" value="[qr_wifi_composer]" readonly >
            <a id="qcwifi" class="videurls" video-url="https://www.youtube.com/watch?v=jqvtNpNpPZc"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>');

}
/**
 * This function is a callback function of  add seeting field
 */

public function qr_code_mail_text()
{

    $options = get_option('qrc_custom_link_generator');
    $options_value = isset($options['qr_code_mail_text']) ? $options['qr_code_mail_text'] : '';
    $placeholder = esc_html('Input WhatsApp numer with Country Code ', 'qrc_composer');

    printf('<p><input type="text" id="qr_code_mail_text" name="qrc_custom_link_generator[qr_code_mail_text]"   value="%s" placeholder="'.esc_attr($placeholder).'" style="min-width:300px"></p><p class="qrcshortvar">
            <input id="qr_whaappxre" type="text" class="shortcodereadoly" value="[qr_whatsapp_composer]" readonly >
            <button type="button" class="qrcclipbtns" data-clipboard-demo data-clipboard-target="#qr_whaappxre" title="copy shortcode"><span class="dashicons dashicons-admin-page"></span></button>
            <a class="videurls" id="qcwhatapp" video-url="https://www.youtube.com/watch?v=NHPDHzGQsoo"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>
            ', esc_attr($options_value));

}

/**
 * This function is a callback function of  add seeting field
 */

public function qr_code_custom_text()
{

    $options = get_option('qrc_custom_link_generator');
    $options_value = isset($options['qr_code_custom_text']) ? $options['qr_code_custom_text'] : '';
    $placeholder = esc_html('Write Text, Number or Link ', 'qrc_composer');

    printf('<p><input style="width:300px" type="text" id="qr_code_custom_text" name="qrc_custom_link_generator[qr_code_custom_text]"   value="%s" placeholder="'.esc_attr($placeholder).'"></p><p class="qrcshortvar">
            <input id="qr_linkexre" type="text" class="shortcodereadoly" value="[qr_link_composer]" readonly >
            <button type="button" class="qrcclipbtns" data-clipboard-demo data-clipboard-target="#qr_linkexre" title="copy shortcode"><span class="dashicons dashicons-admin-page"></span></button>
            <a class="videurls" id="qclinks" video-url="https://www.youtube.com/watch?v=z3iEHvdcIO0"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>',esc_attr($options_value));

}
public function qr_code_phonenumber()
{

    $options = get_option('qrc_custom_link_generator');
    $options_value = isset($options['qr_code_phonenumber']) ? $options['qr_code_phonenumber'] : '';
    $placeholder = esc_html('Input phone or mobile number', 'qrc_composer');

    printf('<p><input type="text" id="qr_code_phonenumber" name="qrc_custom_link_generator[qr_code_phonenumber]"   value="%s" placeholder="'.esc_attr($placeholder).'" style="width:300px"></p><p class="qrcshortvar">
            <input id="qr_phnexre" type="text" class="shortcodereadoly" value="[qrc_phonenumber]" readonly >
            <button type="button" class="qrcclipbtns" data-clipboard-demo data-clipboard-target="#qr_phnexre" title="copy shortcode"><span class="dashicons dashicons-admin-page"></span></button>
            <a class="videurls" id="qcphone" video-url="https://www.youtube.com/watch?v=AeNknsIkGnU"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>', esc_attr($options_value));

}

/**
 * admin form field validation
 */

public function qr_log_option_page_sanitize($input)
{
    $sanitary_values = array();

    if (isset($input['qr_code_custom_text']))
    {
        $sanitary_values['qr_code_custom_text'] = sanitize_text_field($input['qr_code_custom_text']);
    }
    if (isset($input['qr_code_mail_text']))
    {
        $sanitary_values['qr_code_mail_text'] = sanitize_text_field($input['qr_code_mail_text']);
    }

    if (isset($input['qr_code_phonenumber']))
    {
        $sanitary_values['qr_code_phonenumber'] = sanitize_text_field($input['qr_code_phonenumber']);
    }

    return $sanitary_values;
}

}