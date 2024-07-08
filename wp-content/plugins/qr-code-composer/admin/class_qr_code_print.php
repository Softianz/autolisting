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

class QR_code_Print_light{

    public function __construct()
    {
    add_action('admin_init', array($this ,'qcr__print_setting'));

}


    function qcr__print_setting()
    {
        
        add_settings_section("section_setting", " ", array($this ,'qrc_print_settting_func'), 'qrc_print_setting');

        add_settings_field("qr_print_size", esc_html__("QR Code Size", "qr-code-composer") , array($this ,"qr_print_size"), 'qrc_print_setting', "section_setting");


        add_settings_field("qr_print_post_type", esc_html__("Post Type", "qr-code-composer") , array($this ,"qr_print_post_type"), 'qrc_print_setting', "section_setting");
        add_settings_field("qr_print_taxony_type", esc_html__("Taxonomy", "qr-code-composer") , array($this ,"qr_print_taxony_type"), 'qrc_print_setting', "section_setting");
        add_settings_field("qr_print_cat_type", esc_html__("Category Type", "qr-code-composer") , array($this ,"qr_print_cat_type"), 'qrc_print_setting', "section_setting");


        add_settings_field("qr_print_per_page", esc_html__("Print Per Page", "qr-code-composer") , array($this ,"qr_print_per_page"), 'qrc_print_setting', "section_setting");
        add_settings_field("qrc_print_orderby", esc_html__("Order By", "qr-code-composer") , array($this ,"qrc_print_orderby"), 'qrc_print_setting', "section_setting");

        add_settings_field("qr_print_title_display", esc_html__("Display Title?", "qr-code-composer") , array($this ,"qr_print_title_display"), 'qrc_print_setting', "section_setting");
        if (class_exists("WooCommerce"))
        {
            add_settings_field("qr_print_price_display", esc_html__("Display Product Price?", "qr-code-composer") , array($this ,"qr_print_price_display"), 'qrc_print_setting', "section_setting");
        }

        add_settings_field("qr_print_display_frontend", esc_html__("Enable Shortcode For Frontend?", "qr-code-composer") , array($this ,"qr_print_display_frontend"), 'qrc_print_setting', "section_setting");
    }
    function qr_print_display_frontend(){

        printf('<input type="checkbox"  class="qrc_apple-switch"   value="qrc_enable_print_shtco" checked><span style="display:inline-block;margin-right:30px"></span>[qrc-print]<p class="description"><em>'.esc_html__('Click to enable shortcodes for frontend', 'qr-code-composer').' <a href="https://qrcode-composer.dipashi.com/qr-code-print-demo/"> View Demo</a></em></p>');

            }
    function qr_print_post_type()
    {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page');
        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);
    ?>
                <select id="qr_print_post_typeid">
             <?php foreach ($post_types as $post_type)
        {
            $post_type_title = get_post_type_object($post_type);
    ?>       
                <option><?php echo esc_html($post_type_title->labels->name); ?></option>

            <?php
        } ?>
               

                </select>
                <p><?php esc_html_e('Downoad QR based on Post type', 'qr-code-composer'); ?></p>
            <?php
    }
    function qrc_print_settting_func()
    {
        return true;
    }
    function qr_print_title_display()
    {

    ?>
                <select>
                    
                <option ><?php esc_html_e('Yes', 'qr-code-composer'); ?></option>
                <option><?php esc_html_e('No', 'qr-code-composer'); ?></option>   

                </select>

            <?php
    }
    function qr_print_per_page()
    {
        $placeholder = esc_html__('QR Code Image Per Page,Display all: -1 ', 'qr-code-composer');
        printf('<input type="text" class="regular-text"  value="7" placeholder="QR Code Per Page"><p class="description"></p>', esc_attr($placeholder));
    }
    function qr_print_cat_type()
    {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page','page','product');

        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);

    ?>
        <select id="qr_print_product_ty">

       <?php  $terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
            )); 
        if($terms){
        foreach ( $terms as $woo_category) { 
               
                    ?>

        <option><?php echo esc_html($woo_category->name); ?></option>

    <?php
     } }
    ?>
        </select>


    <select id="qr_print_cat_ty">
        <option value="0">---</option>
        <?php 

        foreach ($post_types as $post_type) {

        $post_type_title = get_post_type_object($post_type);
        $post_type_title->labels->name; 
        $taxonomies = get_object_taxonomies( array( 'post_type' => $post_type ) );
         foreach( $taxonomies as $taxonomy ) {
            
         $terms = get_terms(array(
        'taxonomy' => array( $taxonomy),
        'hide_empty' => true,
    )); 

        
         foreach ( $terms as $el_category) { 
       

            ?>

        <option><?php echo esc_html($el_category->name .' - ('. $post_type_title->labels->name .')'); ?></option>

        <?php  }


        }
        }

     ?>
    </select>

    <p><?php esc_html_e('Downoad QR based on Category type, if empty this field QR Code display as above post type', 'qr-code-composer'); ?></p>
    <?php
    }



    function qrc_print_orderby(){ ?>

            <select >

            <option value="none"> <?php esc_html_e('None', 'qr-code-composer'); ?></option>
            <option value="ID" >  <?php esc_html_e('ID', 'qr-code-composer'); ?></option>
            <option value="title">  <?php esc_html_e('Title', 'qr-code-composer'); ?></option>
            <option value="date" >  <?php esc_html_e('Date', 'qr-code-composer'); ?></option>
            <option value="name">  <?php esc_html_e('Name', 'qr-code-composer'); ?></option>


            </select>
    <?php
    }
    function qr_print_taxony_type()
    {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page','page','post','product');
        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);
        ?>

    <select id="qr_print_tzx_ty">
        <option value="0">---</option>
       
        <option value="category"><?php esc_html_e('Post Category  ', 'qr-code-composer'); ?></option>
        <option value="post_tag"><?php esc_html_e('Post Tags', 'qr-code-composer'); ?></option>
        <?php if(class_exists("WooCommerce")){
            ?>
        <option value="product_cat"><?php esc_html_e('Product Category ', 'qr-code-composer'); ?></option>
        <?php 
    }
        foreach ($post_types as $post_type) {

        $post_type_title = get_post_type_object($post_type);
        $post_type_title->labels->name;

         $taxonomies = get_object_taxonomies( array( 'post_type' => $post_type ) );
        

         foreach( $taxonomies as $taxonomy ) {
          ?>
            

        <option><?php echo esc_html($taxonomy .' - ('. $post_type_title->labels->name .')'); ?></option>

        <?php  


        }
        }



     ?>
    </select>

    <p><?php esc_html_e('Downoad QR based on Category type, if empty this field QR Code display as above post type', 'qr-code-composer'); ?></p>
    <?php
    }


    function qr_print_price_display()
    {

    ?>
    <select>
        <option><?php esc_html_e('Yes', 'qr-code-composer'); ?></option>
        <option><?php esc_html_e('No', 'qr-code-composer'); ?></option>
    </select>
    <p class="description"><?php echo esc_html__('If Post Type "product" ', 'qr-code-composer') ?></p>
    <?php
    }

    function qr_print_size()
    {

        $placeholder = esc_html__('Input a numeric value, e.g:200', 'qr-code-composer');
        printf('<input type="text" class="regular-text"  value="200" placeholder="Write a Value">
    <p class="description">%s</p>
    ',esc_attr($placeholder)); 
    } 
}
