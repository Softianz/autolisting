<?php
/**
 * The file that defines the bulk print admin area
 *
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      1.0.9
 *
 * @package    qr-code-composer_pro
 * @subpackage qr-code-composer_pro/admin
 */

class QR_code_List_View_Light{

    public function __construct()
    {
    add_action('admin_init', array($this ,'qcr__list_setting'));

}


    function qcr__list_setting()
    {

        add_settings_section("section_setting", " ", array($this ,'qrc_print_settting_func'), 'qrc_list_setting');

        add_settings_field("qr_print_size", esc_html__("QR Code Size", "qr-code-composer") , array($this ,"qr_print_size"), 'qrc_list_setting', "section_setting");
        add_settings_field("qr_print_post_type", esc_html__("Post Type", "qr-code-composer") , array($this ,"qr_print_post_type"), 'qrc_list_setting', "section_setting");

        add_settings_field("qr_print_per_page", esc_html__("QR Per Page", "qr-code-composer") , array($this ,"qr_print_per_page"), 'qrc_list_setting', "section_setting");

        add_settings_field("qrc_print_orderby", esc_html__("Order By", "qr-code-composer") , array($this ,"qrc_print_orderby"), 'qrc_list_setting', "section_setting");  
        add_settings_field("qrc_download_order", esc_html__("Order", "qr-code-composer") , array($this ,"qrc_download_order"), 'qrc_list_setting', "section_setting");
        add_settings_field("qr_dwn_display_frontend", esc_html__("Enable Shortcode For Frontend?", "qr-code-composer") , array($this ,"qr_dwn_display_frontend"), 'qrc_list_setting', "section_setting");
    }

    function qr_dwn_display_frontend(){


        printf('<input type="checkbox" " class="qrc_apple-switch"   value="qrc_enable_dwn_shtco" checked><span style="display:inline-block;margin-right:30px"></span>[qrc-download]<p class="description"><em>'.esc_html__('Click to enable shortcodes for frontend', 'qr-code-composer').' <a href="https://qrcode-composer.dipashi.com/qr-code-download/"> View Demo</a></em></p>');

            }

    function qrc_print_orderby(){?>

            <select>

            <option><?php esc_html_e('None ', 'qr-code-composer'); ?></option>
            <option><?php esc_html_e('ID ', 'qr-code-composer'); ?></option>
            <option><?php esc_html_e('Title ', 'qr-code-composer'); ?></option>
            <option><?php esc_html_e('Date ', 'qr-code-composer'); ?></option>
            <option><?php esc_html_e('Name ', 'qr-code-composer'); ?></option>


            </select>
    <?php
    }

    function qrc_download_order(){?>

            <select>

            <option> <?php esc_html_e('Ascending ', 'qr-code-composer'); ?></option>
            <option> <?php esc_html_e('Descending ', 'qr-code-composer'); ?></option>

      </select>
    <?php
    }


    function qr_print_post_type()
    {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page');
        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);

        ?>
    <select>
        <?php foreach ($post_types as $post_type)
        {
        $post_type_title = get_post_type_object($post_type);
        ?>
        <option><?php echo esc_html($post_type_title->labels->name); ?></option>

        <?php
        } ?>
    </select>
    <p><?php esc_html_e('Downoad QR based on Post type ', 'qr-code-composer'); ?></p>
    <?php
    }

    function qrc_print_settting_func()
    { return true;
    }
    function qr_print_per_page()
    {

        $placeholder = esc_html__('QR Code Image Per Page,Display all: -1 ', 'qr-code-composer');
        printf('<input type="text" class="regular-text"  value="6" placeholder="Qr Code Per Page">
    <p class="description">%s</p>
    ', esc_attr($placeholder)); 
    } 

    function qr_print_size() { 

        $placeholder = esc_html__('Input a numeric value, e.g:200', 'qr-code-composer'); printf('<input type="text" class="regular-text" value="150" placeholder="Write a Value" />
    <p class="description">%s</p>
    ', esc_attr($placeholder)); 
    
    } 
}
