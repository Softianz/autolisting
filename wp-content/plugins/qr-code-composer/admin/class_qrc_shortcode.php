<?php
/**
 * The file that defines the bulk print admin area
 *
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      3.1.0
 *
 * @package    qrc_composer_pro
 * @subpackage qrc_composer_pro/admin
 */
class QRC_Shortcode_lite {
    public function __construct() {
        add_action("init", [$this, "qrc_link_shortcode"]);
        add_action("show_user_profile", [$this, "qrc_user_profile_fields"]);
        add_action("edit_user_profile", [$this, "qrc_user_profile_fields"]);
        add_action("woocommerce_account_dashboard", [$this, "qrc_woo_accountdash", ]);
    }
    function qrc_link_shortcode() {
        add_shortcode("qrc_vcard_single", [$this, "qrc_vcard_single_atts"]);
    }
    function qrc_vcard_single_atts($atts) {
            $options1 = get_option("qrc_composer_settings");
            $size = isset($options1["qr_code_picture_size_width"]) ? $options1["qr_code_picture_size_width"] : 200;
            $download_qr = isset($options1["qr_download_text"]) ? $options1["qr_download_text"] : esc_html__("Download QR", "qr-code-composer");

            $qr_dwnbtn_color  = isset($options1["qr_dwnbtn_color"]) ? $options1["qr_dwnbtn_color"] : "#000";

            $qr_dwnbtnbg_color = isset($options1["qr_dwnbtnbg_color"]) ? $options1["qr_dwnbtnbg_color"] : "#c8fd8c";
            $qrcvcrad_wnload_hide = isset($options1["qr_download_hide"]) ? $options1["qr_download_hide"] : "no";
$options = get_option('qrc_vcard_generator');

        $name = isset($options['qrcvcardsingle_name']) ? $options['qrcvcardsingle_name'] : '';    
        $company = isset($options['qrcvcardsingle_company']) ? $options['qrcvcardsingle_company'] : '';
        $subtitle = isset($options['qrcvcardsingle_subtitle']) ? $options['qrcvcardsingle_subtitle'] : '';
        $mobile = isset($options['qrcvcardsingle_mbunber']) ? $options['qrcvcardsingle_mbunber'] : '';
        $phone = isset($options['qrcvcardsingle_pbunber']) ? $options['qrcvcardsingle_pbunber'] : '';
        $email = isset($options['qrcvcardsingle_email']) ? $options['qrcvcardsingle_email'] : '';
        $address = isset($options['qrcvcardsingle_address']) ? $options['qrcvcardsingle_address'] : '';
        $note = isset($options['qrcvcardsingle_note']) ? $options['qrcvcardsingle_note'] : '';
        $website = isset($options['qrcvcardsingle_website']) ? $options['qrcvcardsingle_website'] : '';

        wp_enqueue_script('qrcvcardcontent');
        ob_start();
        if ($qrcvcrad_wnload_hide == "no") {
            $qr_download_ = '<div><a id="QRCdownloads" download="' . $name . '.png">
           <button type="button" style="min-width:' . $size . "px;background:" . $qr_dwnbtnbg_color . ";color:" . $qr_dwnbtn_color . ';font-weight: 600;border: 1px solid transparent;padding: 6px 0;margin-top: 12px;" class="uqr_code_btn">' .$download_qr. '</button>
           </a></div>';
        } else {
            $qr_download_ = "";
        }

    

        $mastervcard = "BEGIN:VCARD\nVERSION:3.0\nN:" . $name . "\nORG:" . $company . "\nTITLE:" . $subtitle . "\nTEL:" . $phone . "\nTEL:" . $mobile . "\nURL:" . $website . "\nEMAIL:" . $email . "\nADR:" . $address . "\nNOTE:" . $note . "\nEND:VCARD";
        return '<div class="qrcprowrapper"><div class="qrc_vcardcontent" data-text="' . $mastervcard . '" id="qrcodeimages"></div>' . $qr_download_ . "</div>" . ob_get_clean();
    }
    function qrc_user_profile_fields($user) {
    ?>
    <h3><?php esc_html_e("Profile QR Code", "qr-code-composer"); ?> <small><?php esc_html_e("(QR code composer)", "qr-code-composer"); ?></small></h3> 
    <?php
        static $i = 1;
        $i++;
        $user_id = $user->ID;
        $options = get_option("qrc_composer_settings");
        $size = isset($options["qr_code_picture_size_width"]) ? $options["qr_code_picture_size_width"] : 200;
        $user_info = get_userdata($user_id);
        $name = $user_info->first_name . " " . $user_info->last_name;
        $user_email = $user_info->user_email;
        $user_url = $user_info->user_url;
        $description = $user_info->description;
        $qr_download_ = '<div><a class="qrcdownload" download="' . $name . '.png"><button type="button" class="button button-secondary is-button is-default is-large" style="width:' . $size . 'px;">' . esc_html__("Download QR", "qr-code-composer") . '</button></a></div>';
        $mastervcard = "BEGIN:VCARD\nVERSION:3.0\nN:" . $name . "\nEMAIL:" . $user_email . "\nNOTE:" . $description . "\nEND:VCARD";
        return printf('<div class="qrcprowrapper"><div class="qrc_qrcode"  id="qrcwrausd_'.get_the_ID().'" style="width:'.esc_attr($size).'px;display:inline-block" data-text="'. esc_attr($mastervcard). '"></div>'.esc_html($qr_download_).'</div>');
    }
    function qrc_woo_accountdash() {
        if (class_exists("WooCommerce")) {

    $options = get_option('qrc_admin_integrate');
    static $i = 1;
    $i++; 
    $checked = isset($options['qrc_vcard_myacdash']) ? 'checked' : '';
    if($checked != 'checked'){
     wp_enqueue_script('qrcvcardcontent');

            $options = get_option("qrc_composer_settings");
            $size = isset($options["qr_code_picture_size_width"]) ? $options["qr_code_picture_size_width"] : 200;
            $download_qr = isset($options["qr_download_text"]) ? $options["qr_download_text"] : esc_html__("Download QR", "qr-code-composer");

            $qr_dwnbtn_color  = isset($options["qr_dwnbtn_color"]) ? $options["qr_dwnbtn_color"] : "#000";

            $qr_dwnbtnbg_color = isset($options["qr_dwnbtnbg_color"]) ? $options["qr_dwnbtnbg_color"] : "#c8fd8c";
            $qrcvcrad_wnload_hide = isset($options["qr_download_hide"]) ? $options["qr_download_hide"] : "no";
            $user_meta = get_current_user_id();
            $user_info = get_userdata($user_meta);
            $name = $user_info->first_name . " " . $user_info->last_name;
            $user_email = $user_info->user_email;
            $user_url = $user_info->user_url;
            $description = $user_info->description;
            if ($qrcvcrad_wnload_hide == "no") {
                $qr_download_ = '<div><a id="QRCdownloads1" download="' . $name . '.png">
           <button type="button" style="min-width:' . $size . "px;background:" . $qr_dwnbtnbg_color . ";color:" . $qr_dwnbtn_color . ';font-weight: 600;border: 1px solid transparent;padding: 6px 0;margin-top: 12px;" class="uqr_code_btn">' .$download_qr. '</button>
           </a></div>';
            } else {
                $qr_download_ = "";
            }
            $mastervcard = "BEGIN:VCARD\nVERSION:3.0\nN:" . $name . "\nEMAIL:" . $user_email . "\nNOTE:" . $description . "\nEND:VCARD";
         echo '<div class="qrcprowrapper"><div class="qrc_vcardcontent" id="qrcwrawoodas_'.esc_attr($i).'"  data-text="'.esc_attr($mastervcard).'"></div>'.esc_html($qr_download_).'</div>';
        }
        }

    }
    
}
if (class_exists("QRC_Shortcode_lite")) {
    new QRC_Shortcode_lite();
}
