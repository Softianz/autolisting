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

class QRC_VcardLight{

    public function __construct()
    {

    add_action('admin_init', array($this ,'qrc_vcard_generator_page'));

}



public function qrc_vcard_generator_page()
{
    register_setting("qrc_vcard_generator", "qrc_vcard_generator", array($this ,'qr_log_option_page_sanitize'));
        

        add_settings_section("qrc_vacrd_admin__section", " ", array($this ,'settting_log_sec_func'), 'qrc_vacrd_admin_sec');

  
        add_settings_field("qrcvacr_vcard", esc_html__("A Simple vCard (Free & Pro)", "qrc_composer") , array($this ,"qr_code_vcard"), 'qrc_vacrd_admin_sec', "qrc_vacrd_admin__section",array(
            'class'  =>  'qr_code_vcard',));
        add_settings_field("qr_checkbox_vcrad", esc_html__("Enable vCard Meta Field (Pro)", "qrc_composer") ,array($this , "qr_checkbox"), 'qrc_vacrd_admin_sec', "qrc_vacrd_admin__section" ,array(
            'class'  =>  'qr_checkbox_vcrad',

    ));

        add_settings_field("qrc_metavcard_display", esc_html__("Enable Auto Display (Pro)", "qrc_composer") ,array($this , "qrc_metavcard_display"), 'qrc_vacrd_admin_sec', "qrc_vacrd_admin__section",array(
            'class'  =>  'qrc_metavcard_display',));

        add_settings_field("qrc_vacrdtempe", esc_html__("vCard Template Shortcode (Pro)", "qrc_composer") ,array($this , "qrc_vacrdtempe"), 'qrc_vacrd_admin_sec', "qrc_vacrd_admin__section",array(
            'class'  =>  'qrc_vacrdtempe',));



}

/**
 * This function is a callback function of  add seeting field
 */

public function qrc_vacrdtempe()
{

    echo '<input id="qrcvacrdtmple" type="text" class="shortcodereadoly" value="[qrc_vcard_template]" readonly ><a href="https://qrcode-composer.dipashi.com/docs/vcard-templete/"> Docs</a>';
}
/**
 * This function is a callback function of  add seeting field
 */
public function qr_code_vcard()
{

    $options = get_option('qrc_vcard_generator');
    $qrcvcardsingle_name = isset($options['qrcvcardsingle_name']) ? $options['qrcvcardsingle_name'] : '';    
    $qrcvcardsingle_company = isset($options['qrcvcardsingle_company']) ? $options['qrcvcardsingle_company'] : '';
    $qrcvcardsingle_subtitle = isset($options['qrcvcardsingle_subtitle']) ? $options['qrcvcardsingle_subtitle'] : '';
    $qrcvcardsingle_mbunber = isset($options['qrcvcardsingle_mbunber']) ? $options['qrcvcardsingle_mbunber'] : '';
    $qrcvcardsingle_pbunber = isset($options['qrcvcardsingle_pbunber']) ? $options['qrcvcardsingle_pbunber'] : '';
    $qrcvcardsingle_email = isset($options['qrcvcardsingle_email']) ? $options['qrcvcardsingle_email'] : '';
    $qrcvcardsingle_address = isset($options['qrcvcardsingle_address']) ? $options['qrcvcardsingle_address'] : '';
    $qrcvcardsingle_note = isset($options['qrcvcardsingle_note']) ? $options['qrcvcardsingle_note'] : '';
    $qrcvcardsingle_website = isset($options['qrcvcardsingle_website']) ? $options['qrcvcardsingle_website'] : '';


    printf('<p class="vacrdsinflwrap"><label class="mqrc_label" for="qrcvcardsingle_name">Name:</label>
        <input type="text" name="qrc_vcard_generator[qrcvcardsingle_name]"   value="%s" placeholder="Enter Name" id="qrcvcardsingle_name"></p><p class="vacrdsinflwrap">
        <label  class="mqrc_label" for="qrcvcardsingle_company">Company / Title:</label> 
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_company]"   value="%s" placeholder="Enter Title" id="qrcvcardsingle_company" > </p><p class="vacrdsinflwrap">
            <label  class="mqrc_label" for="qrcvcardsingle_subtitle">Sub Title:</label>    
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_subtitle]"   value="%s" placeholder="Enter Subtitle" id="qrcvcardsingle_subtitle"> </p><p class="vacrdsinflwrap">
            <label class="mqrc_label" for="qrcvcardsingle_mbunber">Mobile Number:</label>    
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_mbunber]" id="qrcvcardsingle_mbunber"  value="%s" placeholder="Enter Mobile Number"  > </p><p class="vacrdsinflwrap">
            <label  class="mqrc_label" for="qrcvcardsingle_pbunber">Phone Number:</label>    
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_pbunber]" id="qrcvcardsingle_pbunber"  value="%s" placeholder="Enter Phone Number" ></p><p class="vacrdsinflwrap">
            <label class="mqrc_label" for="qrcvcardsingle_email">Email:</label>    
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_email]" id="qrcvcardsingle_email"  value="%s" placeholder="Enter email" ></p><p class="vacrdsinflwrap">
            <label class="mqrc_label" for="qrcvcardsingle_website">Website:</label>    
            <input type="text"  name="qrc_vcard_generator[qrcvcardsingle_website]" id="qrcvcardsingle_website" value="%s" placeholder="Enter website"  ></p><p class="vacrdsinflwrap">
            <label class="mqrc_label" for="qrcvcardsingle_address">Address:</label>   
           <textarea name="qrc_vcard_generator[qrcvcardsingle_address]" placeholder="Enter Addess" id="qrcvcardsingle_address">%s</textarea></p><p class="vacrdsinflwrap">
            <label class="mqrc_label" for="qrcvcardsingle_note">Note:</label>   
           <textarea name="qrc_vcard_generator[qrcvcardsingle_note]" id="qrcvcardsingle_note" placeholder="Enter Note" >%s</textarea></p><p class="qrcshortvar">
            <input id="qrcvacrdsingle" type="text" class="shortcodereadoly" value="[qrc_vcard_single]" readonly >
            <button type="button" class="qrcclipbtns" data-clipboard-demo data-clipboard-target="#qrcvacrdsingle" title="copy shortcode"><span class="dashicons dashicons-admin-page"></span></button>
            <a href="https://qrcode-composer.dipashi.com/docs/vcard-qr-code/">Docs</a></p>', esc_attr($qrcvcardsingle_name), esc_attr($qrcvcardsingle_company), esc_attr($qrcvcardsingle_subtitle),esc_attr($qrcvcardsingle_mbunber),esc_attr($qrcvcardsingle_pbunber),esc_attr($qrcvcardsingle_email), esc_attr($qrcvcardsingle_website),esc_textarea($qrcvcardsingle_address),esc_textarea($qrcvcardsingle_note));



}



function qr_checkbox()
{

    $args = array(
        'public' => true,
    );

        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page','wp_template','wp_template_part','wp_navigation','wp_global_styles');

    $types = get_post_types( $args);
    $post_types = array_diff($types, $excluded_posttypes);

    foreach ($post_types as $post_type)
    {
        $post_type_title = get_post_type_object($post_type);

        $options = get_option('qrc_vcard_generator');

        $checked = isset($options[$post_type]) ? 'checked' : '';

        printf('<div style="margin-top:10px"><label class="qrccheckboxwrap" for ="ee%s">%s <input type="checkbox" id="ee%s" value="%s" name="qrc_vcard_generator[%s]" %s>
  <span class="qrccheckmark"></span>
</label></br></div>', esc_attr($post_type),esc_html($post_type_title->labels->name), esc_attr($post_type),esc_attr($post_type), esc_attr($post_type) ,esc_attr($checked));

    }
}
function qrc_metavcard_display()
{


        printf('<p><input type="checkbox" class="qrc-apple-switch"" id="qrc_metavcard_display"></p>');


    }

/**
 * This function is a callback function of  add seeting section
 */

public function settting_log_sec_func()
{?>

<div class="qrc-box-header" >
            <h3 class="sui-box-title"><?php echo esc_html__('Bulk vCard Generator (Pro)', 'qrc_composer') ?>
</h3>
<p class="vrcomponents"><?php echo esc_html__(' These settings create vcard meta fields in posts, pages, products, or custom posts. After filling information in meta field, it will generate vCard QR code and display metafield section as well as frontend, for details click video button or read documentation', 'qrc_composer') ?><a id="qrcvcardfs" video-url="https://www.youtube.com/watch?v=xgdf97_GYWw"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>

        </div>
        <?php
}


/**
 * admin form field validation
 */

public function qr_log_option_page_sanitize($input)
{
    $sanitary_values = array();

    
    if (isset($input['qrcvcardsingle_name']))
    {
        $sanitary_values['qrcvcardsingle_name'] = sanitize_text_field($input['qrcvcardsingle_name']);
    }
    
    if (isset($input['qrcvcardsingle_company']))
    {
        $sanitary_values['qrcvcardsingle_company'] = sanitize_text_field($input['qrcvcardsingle_company']);
    }
    
    if (isset($input['qrcvcardsingle_subtitle']))
    {
        $sanitary_values['qrcvcardsingle_subtitle'] = sanitize_text_field($input['qrcvcardsingle_subtitle']);
    }
    
    if (isset($input['qrcvcardsingle_mbunber']))
    {
        $sanitary_values['qrcvcardsingle_mbunber'] = sanitize_text_field($input['qrcvcardsingle_mbunber']);
    }
    
    if (isset($input['qrcvcardsingle_pbunber']))
    {
        $sanitary_values['qrcvcardsingle_pbunber'] = sanitize_text_field($input['qrcvcardsingle_pbunber']);
    }
    
    if (isset($input['qrcvcardsingle_email']))
    {
        $sanitary_values['qrcvcardsingle_email'] = sanitize_text_field($input['qrcvcardsingle_email']);
    }
    
    if (isset($input['qrcvcardsingle_address']))
    {
        $sanitary_values['qrcvcardsingle_address'] = sanitize_text_field($input['qrcvcardsingle_address']);
    }
    
    if (isset($input['qrcvcardsingle_note']))
    {
        $sanitary_values['qrcvcardsingle_note'] = sanitize_text_field($input['qrcvcardsingle_note']);
    }
    
    if (isset($input['qrcvcardsingle_website']))
    {
        $sanitary_values['qrcvcardsingle_website'] = sanitize_text_field($input['qrcvcardsingle_website']);
    }


    $post_types = get_post_types();

    foreach ($post_types as $post_type)
    {

        if (isset($input[$post_type]))
        {
            $sanitary_values[$post_type] = $input[$post_type];
        }
    }

    return $sanitary_values;
}

}