<?php
/**
 * The file that defines the bulk print admin area
 *
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      1.0.9
 *
 * @package    qrc_composer_pro
 * @subpackage qrc_composer_pro/admin
 */

class QR_code_Admin_settings{

        public function __construct()
        {
        add_action('admin_init', array($this ,'qcr_settings_page'));



    }

    public function qcr_settings_page()
    {

    register_setting("qrc_composer_settings", "qrc_composer_settings", array($this ,'qrc_option_page_sanitize')); 
    
    add_settings_section("qrc_design_section", " ", array($this ,'settting_sec_desfifn'), 'qrc_design_sec');
    
    add_settings_section("qrc_download_section", " ", array($this ,'settting_sec_func'), 'qrc_admin_sec');


    add_settings_field("qr_code_size", esc_html__("Front QR Code Size", "qr-code-composer") ,array($this , "qr_input_size"), 'qrc_design_sec', "qrc_design_section");

        add_settings_field("qr_color_management", esc_html__("QR Color", "qr-code-composer") ,array($this , "qr_color_management"), 'qrc_design_sec', "qrc_design_section");

    add_settings_field("qr_bgcolor_management", esc_html__("QR Background Color", "qr-code-composer") , array($this ,"qr_bgcolor_management"), 'qrc_design_sec', "qrc_design_section");
        add_settings_field("qrc_quitzone", esc_html__("Margin", "qr-code-composer") , array($this ,"qrc_quitzone"), 'qrc_design_sec', "qrc_design_section");
    add_settings_field("qrc_ecorenlevel", esc_html__("Level", "qr-code-composer") , array($this ,"qrc_ecorenlevel"), 'qrc_design_sec', "qrc_design_section");





    add_settings_field("qrc_logo_image", esc_html__("Logo Upload (Pro)", "qr-code-composer") , array($this ,"qrc_logo_image"), 'qrc_design_sec', "qrc_design_section", array('class' =>'premiumversion'));

    add_settings_field("qrc_imagesize", esc_html__("Image Size", "qr-code-composer") , array($this ,"qrc_imagesize"), 'qrc_design_sec', "qrc_design_section", array('class' =>'premiumversion'));


    add_settings_field("qr_code_dsize", esc_html__("Download QR Code Size(Pro)", "qr-code-composer") ,array($this , "qr_code_dsize"), 'qrc_design_sec', "qrc_design_section", array('class' =>'premiumversion'));

    add_settings_field("qr_download_text", esc_html__("Download QR Button", "qr-code-composer") , array($this ,"qr_download_text"), 'qrc_design_sec', "qrc_design_section");
    add_settings_field("qr_alignment", esc_html__("Alignment", "qrc_composer") , array($this ,"qr_alignment"), 'qrc_admin_sec', "qrc_download_section",array(
            'class'  =>  'alignme', 
        'label_for' => 'qr_alignment',

    ));

    if (class_exists('WooCommerce'))
    {
        add_settings_field("wc_qr_alignment", esc_html__(" Alignment (Product Page)", "qrc_composer") , array($this ,"wc_qr_alignment"), 'qrc_admin_sec', "qrc_download_section",array(
            'class'  =>  'wcalignme', 
        'label_for' => 'wc_qr_alignment',

    ));

        add_settings_field("qrc_wc_ptab_name", esc_html__("Change Text of Product Tab", "qrc_composer") ,array($this , "qrc_wc_ptab_name"), 'qrc_admin_sec', "qrc_download_section",array(
            'class'  =>  'ptab_name', 
        'label_for' => 'qrc_wc_ptab_name',

    ));

    }

    add_settings_field("qr_checkbox", esc_html__("Hide QR code according to post type", "qrc_composer") ,array($this , "qr_checkbox"), 'qrc_admin_sec', "qrc_download_section" ,array(
            'class'  =>  'qr_checkbox',

    ));


    add_settings_field("qr_checkbox_page", esc_html__("Hide QR code according to Page", "qrc_composer") , array(
        $this,
        "qr_checkbox_page"
    ) , 'qrc_admin_sec', "qrc_download_section" ,array(
            'class'  =>  'qr_checkbox_page',

    ));


    add_settings_field("qr_stcode_management", esc_html__("Shortcode for Current Page URL", "qrc_composer") ,array($this , "qr_stcode_management"), 'qrc_admin_sec', "qrc_download_section",array(
            'class'  =>  'qr_stcode_management',

    ));

    }
   /**
     * This function is a callback function of  add seeting section
     */
    function settting_sec_desfifn()
    {   
        return true;
    }
    function settting_sec_func()
    {   
       ?>
<div class="qrc-box-header" >
            <h3 class="sui-box-title"><?php echo esc_html__('Auto Generate QR', 'qrc_composer') ?></h3>
<p><?php echo esc_html__('These QR codes are automatically displayed after the content of the web page. current page url will be used as content of QR code.', 'qrc_composer') ?><a class="qrcdownsize" id="qrcauto" video-url="https://www.youtube.com/watch?v=LyQGEShmhn8"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a></p>

        </div>

       <?php

    }

    public function qrc_imagesize() {
    $options = get_option('qrc_composer_settings');
    $imagesize = isset($options['mSize']) ? $options['mSize'] : (int)20;
        printf(
            '<input type="range"  class="qrcrange" min="5" max="40" step="1" name="qrc_composer_settings[mSize]" id="imagesize" value="%s" oninput="this.nextElementSibling.value = this.value"><output class="rangeoutouy">'.(int)$imagesize.'</output>',(int)$imagesize);
    }
    public function qrc_ecorenlevel() {

    $options = get_option('qrc_composer_settings');
    $ecLevel = isset($options['ecLevel']) ? $options['ecLevel'] : 'L';

        ?>
        <select name="qrc_composer_settings[ecLevel]" id="ecLevel">
            <option value="H" <?php echo esc_attr($ecLevel) == 'H' ? 'selected' : '' ?>>H - high (30%)</option>
            <option value="Q" <?php echo esc_attr($ecLevel) == 'Q' ? 'selected' : '' ?>>Q - quartile (25%)</option>

            <option value="M" <?php echo esc_attr($ecLevel) == 'M' ? 'selected' : '' ?>>M - medium (15%)</option>
            <option value="L" <?php echo esc_attr($ecLevel) == 'L' ? 'selected' : '' ?>>L - low (7%)</option>
        </select>
        <?php
    }

 public function qrc_quitzone() {

    $options = get_option('qrc_composer_settings');
    $quiet = isset($options['quiet']) ? $options['quiet'] : '0';

        printf(
            '<input type="range" min="0" max="4" step="1" name="qrc_composer_settings[quiet]" class="qrcrange" id="quiet" value="%s" oninput="this.nextElementSibling.value = this.value"><output class="rangeoutouy">%s</output>',(int)$quiet,(int)$quiet);

    }

function qr_checkbox_page(){

    $qrc_type_pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => - 1,
        ));
        if ($qrc_type_pages)
        {
            foreach ($qrc_type_pages as $qrc_type_page){

        $options = get_option('qrc_composer_settings');

        $checked = isset($options[$qrc_type_page->ID]) ? 'checked' : '';

            printf('<div style="margin-top:10px"><label class="qrccheckboxwrap" for ="%s">%s
  <input type="checkbox" id="%s"  value="%s" name="qrc_composer_settings[%s]" %s>
  <span class="qrccheckmark"></span>
</label></br></div>', esc_attr($qrc_type_page->ID),esc_html($qrc_type_page->post_title),esc_attr($qrc_type_page->ID),esc_attr($qrc_type_page->ID),esc_attr($qrc_type_page->ID),esc_attr($checked));


        }


    
        }
    }


function qrc_wc_ptab_name()
{

    $options = get_option('qrc_composer_settings');
    $qrc_wc_ptab_name = isset($options['qrc_wc_ptab_name']) ? $options['qrc_wc_ptab_name'] : esc_html__('QR Code','qr-code-composer');

    printf('<input type="text" name="qrc_composer_settings[qrc_wc_ptab_name]" value="%s" placeholder="e:g: QR Code" id="qrc_wc_ptab_name">', esc_attr($qrc_wc_ptab_name)); 

}

function qr_checkbox()
{

    $args = array(
        'public' => true,
    );

        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page','wp_template','wp_template_part','wp_navigation','wp_global_styles','shop_order_placehold');

    $types = get_post_types( $args);
    $post_types = array_diff($types, $excluded_posttypes);

    foreach ($post_types as $post_type)
    {
        $post_type_title = get_post_type_object($post_type);

        $options = get_option('qrc_composer_settings');

        $checked = isset($options[$post_type]) ? 'checked' : '';

        printf('<div><label class="qrccheckboxwrap"  for ="%s" id="qrc_label_wrap">%s
  <input  type="checkbox" id="%s" value="%s" name="qrc_composer_settings[%s]" %s>
  <span class="qrccheckmark"></span>
</label></br></div>', esc_attr($post_type), esc_html($post_type), esc_attr($post_type),esc_attr($post_type), esc_attr($post_type), esc_attr($checked));




    }


}


/**
 * This function is a callback function of  add seeting field
 */

function qr_input_size()
{

    $options = get_option('qrc_composer_settings');
    $options_value = isset($options['qr_code_picture_size_width']) ? $options['qr_code_picture_size_width'] : 200;

    printf('<input type="number" min="50" max="1000" step="50" id="qwe_sizw" name="qrc_composer_settings[qr_code_picture_size_width]"   value="%s"  required><p class="qrc_description">'.esc_html__('QR code Image frontend size; default 200px', 'qr-code-composer').'</p>', esc_attr($options_value));

}
/**
 * This function is a callback function of  add seeting field
 */

function qr_code_dsize()
{
    printf('<input type="number" min="500" max="10000" step="100" value="700" id="qr_code_dsize"><p class="qrc_description">'.esc_html__('QR code download image Quality; default 700px', 'qr-code-composer').'</p>');

}

/**
 * This function is a callback function of  add seeting field
 */

function qr_alignment()
{

    $options = get_option('qrc_composer_settings');
    $qrc_alignment = isset($options['qrc_select_alignment']) ? $options['qrc_select_alignment'] : '';

    ?>
    <select name="qrc_composer_settings[qrc_select_alignment]" id="qr_alignment">
        
    <option value="left" <?php echo esc_attr($qrc_alignment) == 'left' ? 'selected' : '' ?>><?php esc_html_e('Left', 'qr-code-composer'); ?></option>
    <option value="right" <?php echo esc_attr($qrc_alignment) == 'right' ? 'selected' : '' ?>><?php esc_html_e('Right', 'qr-code-composer'); ?></option>   
    <option value="center" <?php echo esc_attr($qrc_alignment) == 'center' ? 'selected' : '' ?>><?php esc_html_e('Center', 'qr-code-composer'); ?></option>

    </select>

    <?php
}

/**
 * This function is a callback function of  add seeting field
 */

function qr_download_text()
{

    $options = get_option('qrc_composer_settings');
    $options_value = isset($options['qr_download_text']) ? $options['qr_download_text'] : 'Download QR';

    $qr_download_hide = isset($options['qr_download_hide']) ? $options['qr_download_hide'] : 'no';

 
  

    ?>
    <div class="qrdownlaodtext">
    <strong>
    <label class="qrc_dwnbtnlabel" for="qrc_dwnbtnlabel"><?php esc_html_e('Remove?', 'qr-code-composer'); ?></label></strong>
    <select name="qrc_composer_settings[qr_download_hide]" class="qrcremovedownlaod" id="qrc_dwnbtnlabel">
        
    <option value="yes" <?php echo esc_attr($qr_download_hide) == 'yes' ? 'selected' : '' ?>><?php esc_html_e('Remove Download Button', 'qr-code-composer'); ?></option>
    <option value="no" <?php echo esc_attr($qr_download_hide) == 'no' ? 'selected' : '' ?>><?php esc_html_e('Keep Download Button', 'qr-code-composer'); ?></option>    

    </select>
   <div class="removealsscolors">
<?php
   printf('<p><strong>
    <label class="inputetxtas" for="inputetxtas">'.esc_html("Label", "qr-code-composer").'</label></strong><input type="text" name="qrc_composer_settings[qr_download_text]" value="%s" placeholder="Download Qr" id="inputetxtas"></p>', esc_attr($options_value)); 
    $value = (isset($options['qr_dwnbtn_color'])) ? $options['qr_dwnbtn_color'] : '#000';
    printf('<p class="qrc_dwnbtn"><strong>
    <label class="qrc_dwnbtnlabel" for="qr_dwnbtn_color">'.esc_html("Color", "qr-code-composer").'</label></strong><input type="text" name="qrc_composer_settings[qr_dwnbtn_color]" value="%s" class="qrc-btn-color-picker" id="qr_dwnbtn_color"></p>', esc_attr($value));
    $valuebg = (isset($options['qr_dwnbtnbg_color'])) ? $options['qr_dwnbtnbg_color'] : '#c8fd8c';
    printf('<p><strong>
    <label class="qrc_dwnbtnlabel" for="qr_dwnbtnbg_color">'.esc_html(" Background", "qr-code-composer").'</label></strong><input type="text" name="qrc_composer_settings[qr_dwnbtnbg_color]" value="%s" class="qrc-btn-bg-picker" id="qr_dwnbtnbg_color"></p></div></div>', esc_attr($valuebg));

}

/**
 * This function is a callback function of  add seeting field
 */

function wc_qr_alignment()
{

    $options = get_option('qrc_composer_settings');
    $qrc_wc_alignment = isset($options['qrc_wc_select_alignment']) ? $options['qrc_wc_select_alignment'] : '';

    ?>
    <select name="qrc_composer_settings[qrc_wc_select_alignment]" id="wc_qr_alignment">
        
    <option value="left" <?php echo esc_attr($qrc_wc_alignment) == 'left' ? 'selected' : '' ?>><?php esc_html_e('Left', 'qr-code-composer'); ?></option>
    <option value="right" <?php echo esc_attr($qrc_wc_alignment) == 'right' ? 'selected' : '' ?>><?php esc_html_e('Right', 'qr-code-composer'); ?></option>    
    <option value="center" <?php echo esc_attr($qrc_wc_alignment) == 'center' ? 'selected' : '' ?>><?php esc_html_e('Center', 'qr-code-composer'); ?></option>

    </select>

    <?php
}
/**
 * This function is a callback function of  add seeting field
 */

function qrc_logo_image()
{?>


            <input id="wooqr_upload_image" type="text" value=""/>
           <input id="wooqr_upload_button" class="button button-primary" type="button" value="Upload image" />
        <?php

        printf(
            '<img id="wooqrimg-buffer" src="">');


}

/**
 * Qr background Color field
 */
function qr_bgcolor_management()
{
    $options = get_option('qrc_composer_settings');

    $bg_value = (isset($options['background'])) ? $options['background'] : 'transparent';
    printf('<input type="text" name="qrc_composer_settings[background]" value="%s"  id="qr_bg" class="qrc-color-picker">',esc_attr($bg_value));

}

/**
 *  Qr Color field
 */

function qr_color_management()
{
      $options = get_option('qrc_composer_settings');

    $qr_color = (isset($options['qr_color'])) ? $options['qr_color'] : '#000';
    printf('<input type="text" name="qrc_composer_settings[qr_color]" value="%s" class="qrc-color-picker" id="fill">',esc_attr($qr_color));

}

function qr_stcode_management()
{
    printf('<p class="qrcshortvar">
            <input id="qr_stcode_management" type="text" class="shortcodereadoly" value="[qrc_code_composer]" readonly >
            <button type="button" class="qrcclipbtns" data-clipboard-demo data-clipboard-target="#qr_stcode_management" title="copy shortcode"><span class="dashicons dashicons-admin-page"></span></button>
            </p>');

    printf('<div style="width:%s; padding:10px 0px"><em>'.esc_html__('For developer: ','qrc_composer').'<span style="color:#673ab7"><em ><</em>?php echo do_shortcode["qrc_code_composer"];<em>?</em>></<em></span></div>', '90%');

}

/**
 * admin form field validation
 */

function qrc_option_page_sanitize($input)
{
    $sanitary_values = array();
    if (isset($input['qr_code_picture_size_width']))
    {
        $sanitary_values['qr_code_picture_size_width'] = sanitize_text_field($input['qr_code_picture_size_width']);
    }

    if (isset($input['qr_download_text']))
    {
        $sanitary_values['qr_download_text'] = sanitize_text_field($input['qr_download_text']);
    }
    if (isset($input['background']))
    {
        $sanitary_values['background'] = sanitize_text_field($input['background']);
    }


    if (isset($input['quiet']))
    {
        $sanitary_values['quiet'] = sanitize_text_field($input['quiet']);
    }

    if (isset($input['qr_color']))
    {
        $sanitary_values['qr_color'] = sanitize_text_field($input['qr_color']);
    }
    if (isset($input['ecLevel']))
    {
        $sanitary_values['ecLevel'] = sanitize_text_field($input['ecLevel']);
    }

    if (isset($input['qrc_select_alignment']))
    {
        $sanitary_values['qrc_select_alignment'] = $input['qrc_select_alignment'];
    }

    if (isset($input['qrc_wc_select_alignment']))
    {
        $sanitary_values['qrc_wc_select_alignment'] = $input['qrc_wc_select_alignment'];
    }    

    if (isset($input['qr_download_hide']))
    {
        $sanitary_values['qr_download_hide'] = $input['qr_download_hide'];
    }
    if (isset($input['qr_dwnbtn_color']))
    {
        $sanitary_values['qr_dwnbtn_color'] = $input['qr_dwnbtn_color'];
    }
    if (isset($input['qr_dwnbtnbg_color']))
    {
        $sanitary_values['qr_dwnbtnbg_color'] = $input['qr_dwnbtnbg_color'];
    }
    if (isset($input['qrc_wc_ptab_name']))
    {
        $sanitary_values['qrc_wc_ptab_name'] = $input['qrc_wc_ptab_name'];
    }

    $post_types = get_post_types();

    foreach ($post_types as $post_type)
    {

        if (isset($input[$post_type]))
        {
            $sanitary_values[$post_type] = $input[$post_type];
        }
    }
        $qrc_type_pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => - 1,
        ));

            foreach ($qrc_type_pages as $qrc_type_page){

            if (isset($input[$qrc_type_page->ID]))
            {
                $sanitary_values[$qrc_type_page->ID] = $input[$qrc_type_page->ID];
            }
        }
    return $sanitary_values;
}
}

