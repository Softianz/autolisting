<?php


class QRCAdminMain{


  public function __construct()
  {
    add_action( 'admin_menu', array($this, 'admin_menu_define' ));  
  }

/**
 * This function is Add Menu page call back function.
 */

public function admin_menu_define()
{

    $icon_url = QRC_COMPOSER_URL . 'admin/img/mini.png';

    add_menu_page(esc_html__('QR Composer', 'qr-code-composer') , esc_html__('QR Composer', 'qr-code-composer') , 'manage_options', 'qr_composer', array(
        $this,
        'qrc_option_func'
    ) , $icon_url,70);


    add_submenu_page('qr_composer', esc_html__('Shortcode Generator', 'qr-code-composer'), esc_html__('Shortcode Generator', 'qr-code-composer'), 'manage_options', 'qrc_shortcode', array(
        $this,
        'qrc_shortcode'
    ));
    add_submenu_page('qr_composer', esc_html__('Order Email QR', 'qr-code-composer'), esc_html__('Order Email QR', 'qr-code-composer'), 'manage_options', 'qrc_ordermail', array(
        $this,
        'qrc_ordermail'
    ));
    add_submenu_page('qr_composer', esc_html__('Bulk vCard list', 'qr-code-composer'), esc_html__('Bulk vCard list', 'qr-code-composer'), 'manage_options', 'qrc_vcardlist', array(
        $this,
        'qrc_vcardlist'
    ));


    add_submenu_page('qr_composer', esc_html__(' Post type QR List', 'qr-code-composer'), '<span class="qrc_down_qr_menu">'.esc_html__('Post type QR List', 'qr-code-composer').'</span>', 'manage_options', 'qrc_list_view', array(
        $this,
        'qrc_list_view'
    ));


    add_submenu_page('qr_composer', esc_html__('Bulk Print QR Code', 'qr-code-composer'), '<span class="qrc_print_qr_menu"> '.esc_html__(' Bulk Print QR Code', 'qr-code-composer').'</span>', 'manage_options', 'qrc_print_pdf', array(
        $this,
        'qrc_print_pdf'
    ));


}
/**
 * This function is Qr Code Composer Pro Features Field
 */

function qrc_pro_func(){ 


}
   function qrc_shortcode(){ 


          ?>
                <div class="qrcodewrap">
                  <div class="qr_wp_admin">
                      <ul class="qrc_nav_bar">
                          <li><a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_shortcode" target="_blank"><?php echo esc_html('Pro Dashbord demo', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/plugins/qr-code-composer/" target="_blank"><?php echo esc_html('Buy Premium', 'qr-code-composer') ?></a></li>
                          <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/contact-us/" target="_blank"><?php echo esc_html('Support', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'qr-code-composer') ?></a></li>
                      </ul>
                      <ul  class="qrc_hdaer_cnt">
                          <li> <img src=" <?php echo esc_url( QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                          <li  class="qrc_fd_cnt"> 
                              <h3><?php echo esc_html('Shortcode Generator', 'qr-code-composer')?> </h3>
                      <small><?php echo esc_html('Create multiple shortcodes for each element. Also can do different QR design, logo change etc', 'qr-code-composer') ?></small></li>
                      </ul>

                  </div>

               <div class="tirmoof">
                       <div class="tirmoof_box">
                                         
                    <div id="tirmoof_djkfh">
    
            <div class="qrc_print_pro_demo"  style="background:url(<?php echo esc_attr( QRC_COMPOSER_URL .'/admin/img/shortcodedemo-min.png') ; ?>);min-height:1767px;width:100%;height:100%"></div>

<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Shortcode Generator (Premium)
</h3>
<p>Create multiple shortcodes for each element. Also can do different QR design, logo change etc</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Upgrade and Unlock <span class="dashicons dashicons-unlock"></span></a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_shortcode" target="_blank">Premium admin Demo page</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
                    
              </div>
            </div>
          </div>
         </div>

         <?php

     }
   function qrc_ordermail(){ 


          ?>
                <div class="qrcodewrap">
                  <div class="qr_wp_admin">
                      <ul class="qrc_nav_bar">
                          <li><a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_ordermail" target="_blank"><?php echo esc_html('Pro Dashbord demo', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/plugins/qr-code-composer/" target="_blank"><?php echo esc_html('Buy Premium', 'qr-code-composer') ?></a></li>
                          <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/contact-us/" target="_blank"><?php echo esc_html('Support', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'qr-code-composer') ?></a></li>
                      </ul>
                      <ul  class="qrc_hdaer_cnt">
                          <li> <img src=" <?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                          <li  class="qrc_fd_cnt"> 
                              <h3><?php echo esc_html('Order QR code (Premium)', 'qr-code-composer')?> </h3>
                      <small><?php echo esc_html('The order QR code is displayed on the order dashboard and it will print in the order email', 'qr-code-composer') ?></small></li>
                      </ul>

                  </div>

               <div class="tirmoof">
                       <div class="tirmoof_menu" id="qrccomposerprowraper">
  <div class="tab-nav">
    <ul>
      <li class="active"><a href="#tab1"><?php echo esc_html__('Order QR code', 'qr-code-composer') ?></a></li>
      <li class=""><a href="#tab2"><?php echo esc_html__('QR Code on Invoice', 'qr-code-composer') ?></a></li>
    </ul> <!-- END tabs-nav -->
  </div>


<div class="tab-content">
  
  <div class="tab1-tab active">
    <div class="bulptiblur">
    <img src="<?php echo esc_url( QRC_COMPOSER_URL .'/admin/img/orderqt-min.png') ; ?>" alt="order QR demoo" class="qrcpreiumversion">
    </div>
<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>WooCommerce Order QR Code</h3>
<p>After an order is completed, the QR code will be printed on the order email. Order Email QR Code can be created from different elements. Order ID, Transaction ID, Billing Address, Order Amount, Order Date, Shipping Cost, and more. These items can be user-controlled. This means you can control what elements are included in the order QR code</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Upgrade and Unlock <span class="dashicons dashicons-unlock"></span></a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_ordermail" target="_blank">Premium admin Demo page</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
</div>
  <div class="tab2-tab">
    <img src="<?php echo esc_url( QRC_COMPOSER_URL .'/admin/img/PDF-invoice-ore.png') ; ?>" alt="order QR demoo"  class="qrcpreiumversion">
  </div>

         </div>
</div>
</div>
</div>


         <?php

     }
   function qrc_vcardlist(){ 


          ?>
                <div class="qrcodewrap">
                  <div class="qr_wp_admin">
                      <ul class="qrc_nav_bar">
                          <li><a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_vcardlist&tab=vcardsettings" target="_blank"><?php echo esc_html('Pro Dashbord demo', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/plugins/qr-code-composer/" target="_blank"><?php echo esc_html('Buy Premium', 'qr-code-composer') ?></a></li>
                          <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/contact-us/" target="_blank"><?php echo esc_html('Support', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'qr-code-composer') ?></a></li>
                      </ul>
                      <ul  class="qrc_hdaer_cnt">
                          <li> <img src=" <?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                          <li  class="qrc_fd_cnt"> 
                              <h3><?php echo esc_html('Download All vCard QR code from one page', 'qr-code-composer')?> </h3>
                      <small><?php echo esc_html('Download all Post type vCard QR Code Images from One Page', 'qr-code-composer') ?></small></li>
                      </ul>

                  </div>

               <div class="tirmoof">
                       <div class="tirmoof_box">
                                         
                    <div id="tirmoof_djkfh">
    
            <div class="qrc_print_pro_demo"  style="background:url(<?php echo esc_url( QRC_COMPOSER_URL .'/admin/img/vcardlist-min.PNG') ; ?>);min-height:600px;width:100%;height:100%"></div>

<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Download vCard QR Code is a PRO Feature</h3>
<p>The plugin allows the download of all  Post type vCard QR Code Images from One Page</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Upgrade and Unlock <span class="dashicons dashicons-unlock"></span></a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_vcardlist&tab=vcardlist" target="_blank">Premium admin Demo page</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
                    
              </div>
            </div>
          </div>
         </div>

         <?php

     }
    function qrc_list_view(){ 

          ?>
        <div class="qrcodewrap">
                  <div class="qr_wp_admin">
                      <ul class="qrc_nav_bar">
                          <li><a href="https://qrcode-composer.dipashi.com/qr-code-download/" target="_blank"><?php echo esc_html('Live demo', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/plugins/qr-code-composer/" target="_blank"><?php echo esc_html('Buy Premium', 'qr-code-composer') ?></a></li>
                          <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/contact-us/" target="_blank"><?php echo esc_html('Support', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'qr-code-composer') ?></a></li>
                      </ul>
                      <ul  class="qrc_hdaer_cnt">
                          <li> <img src=" <?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                          <li  class="qrc_fd_cnt"> 
                              <h3><?php echo esc_html('Download QR code from one page', 'qr-code-composer')?> </h3>
                      <small><?php echo esc_html('Download all Post type QR Codes from One Page', 'qr-code-composer') ?></small></li>
                      </ul>

                  </div>

               <div class="tirmoof">
                       <div class="tirmoof_menu" id="qrccomposerprowraper">
  <div class="tab-nav">
    <ul>
      <li class="active"><a href="#tab1"><?php echo esc_html__('Settings', 'qr-code-composer') ?></a></li>
      <li class=""><a href="#tab2"><?php echo esc_html__('Download Page', 'qr-code-composer') ?></a></li>
    </ul> <!-- END tabs-nav -->
  </div>


<div class="tab-content">
  
  <div class="tab1-tab active">
    <div class='qrcprosd'>
    <?php 
                 settings_fields("qrc_list_view_option");

                 do_settings_sections('qrc_list_setting');

     ?>
<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Download all QR Code is a PRO Feature</h3>
<p>The plugin allows the download of all QR codes based on Post type . and it is from one page.</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Upgrade and Unlock <span class="dashicons dashicons-unlock"></span></a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_list_view" target="_blank">Premium admin Demo page</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
        </div>
  </div> 

    <div class="tab2-tab">
            <div class="qrc_print_pro_demo"  style="background:url(<?php echo esc_url( QRC_COMPOSER_URL .'/admin/img/download.PNG') ; ?>);min-height:596px;width:100%;height:100%"></div>
<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Download all QR Code is a PRO Feature</h3>
<p>The plugin allows the download of all QR codes based on Post type . and it is from one page.</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Upgrade and Unlock <span class="dashicons dashicons-unlock"></span></a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_list_view&tab=qrc_list_page" target="_blank">Premium admin Demo page</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>


    </div>
</div>




                       </div>
          </div>
         </div>

         <?php

     }


function qrc_print_pdf()
{ 
     ?>
                <div class="qrcodewrap">
                  <div class="qr_wp_admin">
                      <ul class="qrc_nav_bar">
                          <li><a href="https://qrcode-composer.dipashi.com/qr-code-print-demo/" target="_blank"><?php echo esc_html('Bulk Print demo', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/qr-code-composer/" target="_blank"><?php echo esc_html('Buy premium', 'qr-code-composer') ?></a></li>
                          <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                          <li><a href="https://sharabindu.com/contact-us/" target="_blank"><?php echo esc_html('Support', 'qr-code-composer') ?></a></li>
                          <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'qr-code-composer') ?></a></li>
                      </ul>
                      <ul  class="qrc_hdaer_cnt">
                          <li> <img src=" <?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                              <li  class="qrc_fd_cnt"> 
                                  <h3><?php echo esc_html('QR Code Bulk Print(Pro)', 'qr-code-composer');?> </h3>
                          <small><?php echo esc_html('Print QR Code In Bulk Qty based on Post type', 'qr-code-composer') ?></small></li>
                      </ul>

                  </div>

               <div class="tirmoof">
                       <div class="tirmoof_menu" id="qrccomposerprowraper">
  <div class="tab-nav">
    <ul>
      <li class="active"><a href="#tab1"><?php echo esc_html__('Settings', 'qr-code-composer') ?></a></li>
      <li class=""><a href="#tab2"><?php echo esc_html__('Print Page', 'qr-code-composer') ?></a></li>
    </ul> <!-- END tabs-nav -->
  </div>


<div class="tab-content">
  
  <div class="tab1-tab active">

<div class='qrcprosd'>
                    <?php
                               settings_fields("qrc_print_option");

                 do_settings_sections('qrc_print_setting')
?>
</div><div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Bulk print is a PRO Feature</h3>
<p>Bulk QR codes can be printed from the plugin’s dashboard or from the front end via built-in shortcodes. You can use filter by category. This will printout as a PDF</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Unlock Bulk Print</a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_print_pdf" target="_blank"> Live demo of Bulk Print QR code</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
      </div>
  <div class="tab2-tab">

        <div class="qrc_print_pro_demo"  style="background:url(<?php echo esc_url( QRC_COMPOSER_URL .'/admin/img/print.png') ; ?>);min-height: 675px;width:100%"></div> 

<div class="bulptinyprowarp">
<div class="bulptinypro">
            <h3>Bulk print is a PRO Feature</h3>
<p>Bulk QR codes can be printed from the plugin’s dashboard or from the front end via built-in shortcodes. You can use filter by category. This will printout as a PDF</p>

<div class="mqrfeatures">

<a href="https://sharabindu.com/plugins/qr-code-composer/#pricing" target="_blank" class="mqrlocout">Unlock Bulk Print</a>
<a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_print_pdf&tab=qrc_print_page" target="_blank"> Live demo of Bulk Print QR code</a>
</div>
<div class="mediumbonus-alert"> <img draggable="false" role="img" class="emoji" alt="🎁" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f381.svg"> <span><strong>Bonus:</strong> You can upgrade to the Pro plan today and <strong>save 40% off</strong> </span><!----></div>
        </div>
        </div>
</div>


                        
              </div>
            </div>
          </div>
         </div>

         <?php
          }


     public function qrc_option_func()
     {
         $options = get_option('qr-code-composer_settings');
    $options_value = isset($options['qr_download_text']) ? $options['qr_download_text'] : 'Download QR';
    $valuebg = (isset($options['qr_dwnbtnbg_color'])) ? $options['qr_dwnbtnbg_color'] : '#c8fd8c';
    $value = (isset($options['qr_dwnbtn_color'])) ? $options['qr_dwnbtn_color'] : '#000';
        ?>
         <div class="qrcodewrap">
             <div class="qr_wp_admin">
                 <ul class="qrc_nav_bar">
                     <li><a href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qr_composer"><?php echo esc_html('Premium Settings Page', 'qr-code-composer') ?></a></li>
                     <li><a href="https://qrcode-composer.dipashi.com/"><?php echo esc_html('Pro Demo', 'qr-code-composer') ?></a></li>
                     <li><a href="https://qrcode-composer.dipashi.com/docs/introduction/"><?php echo esc_html('Docs', 'qr-code-composer') ?></a></li>

                     <li><a href="https://sharabindu.com/plugins/qr-code-composer/"><?php echo esc_html('Get Premium', 'qr-code-composer') ?></a></li>
                 </ul>
                 <ul  class="qrc_hdaer_cnt">
                     <li> <img src=" <?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/logo.png') ?>" alt="qr logo"></li>

                     <li  class="qrc_fd_cnt"> 
                         <h3><?php echo esc_html('QR Code Composer', 'qr-code-composer') . ' <sup>' .esc_html( QRC_COMPOSER_VERSION); ?></sup> </h3>
                 <small><?php echo esc_html('It improves the potential of the brand and business to attract the attention of the target demographic', 'qr-code-composer') ?></small></li>
                 </ul>

             </div>
    <div class="qrcodewrap" id="qrccomposerprowraper">
<div class="tab-nav">
  <ul>
    <li class="active"><a href="#tab1"><?php echo esc_html("Design QR code", "qr-code-composer") ?></a></li>
    <li><a href="#tab2"><?php echo esc_html("Auto Generate QR Code", "qr-code-composer") ?></a></li>
    <li><a href="#tab3"><?php echo esc_html("Various Components QR", "qr-code-composer") ?></a></li>
    <li><a href="#tab4"><?php echo esc_html("vCard QR Code", "qr-code-composer") ?></a></li>
    <li><a href="#tab5"><?php echo esc_html("Integration", "qr-code-composer") ?></a></li>
  </ul> <!-- END tabs-nav -->
</div>
  <div class="tab-content">

    <div  class="tab1-tab active">
        <form method="post" action="options.php" class="qrcdesings" >
            <div class="desingwrapper">
            <div class="leftside">

                    <input type="hidden" name="qr-code-composer_settings[render]" value="image" id="render">
                        <input type="hidden" name="qr-code-composer_settings[size]" value="700" id="size">
                        <input type="hidden" name="qr-code-composer_settings[text]" value="http://localhost/yoobar/hello-world/" id="text">
    
            <?php              
            settings_fields("qrc_composer_settings");
             do_settings_sections('qrc_design_sec'); ?>
             <div class="qrcsubmits">
             <button type ="submit" id="osiudi" class="button button-primary"><?php echo esc_html('Save Changes','qr-code-composer') ?> <span class="qrcs_desingcrt"></span></button>
         <span class="qrcsdhicr_dsigns"></span>
         </div>
         </div>
         <div class="rightside">
         <div class="qrc_prev_manage">
<div class="prbloth">Preview</div>
            <div id="qrccomsposerprview" class="qrc_canvas"></div> 
        <div>
       <a class="qrdemodownload">
           <button type="button" style="color:<?php echo esc_attr($value);?>;background:<?php echo esc_attr($valuebg);?>;font-weight: 600;border: 1px solid transparent;padding: 6px 0;margin-top: 5px;" id="result"><?php echo esc_html($options_value); ?></button>
           </a>
</div>
        </div>    

         </div>
         <div class="rightsidelast">
                       <div>
                     <div class="qrc_pro_ftcs_cont">
                     <h4 class="pro_ftcs__h"><?php echo esc_html__('Premium Features', 'qr-code-composer') ?></h4>
                     <img style="width:200px" src="<?php echo esc_url( QRC_COMPOSER_URL . '/admin/img/images-min.png') ?>" alt="Pro Features">

                      <ul>

                         <li><?php echo esc_html__('Body Shape QR Code', 'qr-code-composer') ?></li>
                        <li><?php echo esc_html__('EyeFrame Shape QR', 'qr-code-composer') ?></li>
                         <li><?php echo esc_html__('Eyeball Shape QR', 'qr-code-composer') ?></li>
                         <li><?php echo esc_html__('Gradient Color QR', 'qr-code-composer') ?></li>
                         <li><?php echo esc_html__('Logo Upload', 'qr-code-composer') ?></li>
                     </ul>
                     <a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qr_composer"><?php echo esc_html__('Admin Demo', 'qr-code-composer') ?></a>
                 </div>
             </div>   


         </div>
         </div>
    </div>
    <div  class="tab2-tab">
        <div id="dynamic-qr">
<div class="qrc_wrap-md-7">
  <?php do_settings_sections('qrc_admin_sec'); ?>
         </div>
<div class="qrc_wrap-md-5 lksfuieusb">
             <div>
                     <div class="qrc_pro_ftcs_cont">
                     <h4 class="pro_ftcs__h"><?php echo esc_html__('Order Email QR Code (Premium)', 'qr-code-composer') ?></h4>
                    
                     <p><?php echo esc_html__('Automatically generates QR code for every new order and old order. WC order email QR code, can be created from different elements.', 'qr-code-composer') ?></p>
                     <img style="box-shadow: 2px 2px 11px 2px #9f9f9f;" src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/order-qr-code.png') ?>" alt="Pro Features">


                     <a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=codemanas-woocommerce-preview-emails"><?php echo esc_html__('Order Email Demo', 'qr-code-composer') ?></a>
<a class="oreremils" id="orderemail" video-url="https://www.youtube.com/watch?v=hSCa9Tz0FZc" style="cursor: pointer;"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a>
         <h4 class="rotnfjj"><?php echo esc_html__('Order QR code in PDF Invoice', 'qr-code-composer') ?></h4>
 <img style="box-shadow: 2px 2px 11px 2px #9f9f9f;" src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/pdf-min.png') ?>" alt="Pro Features">
                     <a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/edit.php?post_type=shop_order"><?php echo esc_html__('PDF Demo', 'qr-code-composer') ?></a>
                 </div>
             </div>
         </div> 
</div>

             <div class="qrcsubmits">
             <button type ="submit" id="osiudi" class="button button-primary"><?php echo esc_html('Save Changes','qr-code-composer') ?> <span class="qrcs_desingcrt"></span></button>
         <span class="qrcsdhicr_dsigns"></span>
        </div>
</form>
    </div>

    <div  class="tab3-tab">
    <div id="dynamic-qr">
        <div class="qrc_wrap-md-7">
        <form method="post" action="options.php" class="qrcdesings" id="qrcdesings">
         <?php  

            settings_fields("qrc_custom_link_generator");
             do_settings_sections('qrc_logo_admin_sec');
         ?> 
             <div class="qrcsubmits">
             <button type ="submit" id="osiudi" class="button button-primary"><?php echo esc_html('Save Changes','qr-code-composer') ?> <span class="qrcs_desingcrt"></span></button>
         <span class="qrcsdhicr_dsigns"></span>
        </div>
      </form>  
      </div><div class="qrc_wrap-md-4">
                    <div class="qrc_pro_ftcs_cont">
                     <h4 class="pro_ftcs__h"><?php echo esc_html__('Shortcode Generator (Premium)', 'qr-code-composer') ?></h4>
                     <p><?php echo esc_html__('In the premium version, you can create multiple shortcodes for each element. Also can do different QR designs, logo changes etc', 'qr-code-composer') ?></p>
                     <img class="shrycodegenrs" src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/shortcodegene-min.png') ?>" alt="Pro Features">
                     <a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_shortcode"><?php echo esc_html__('Admin Demo', 'qr-code-composer') ?></a>

                 </div>

       </div>
  </div>    
    </div>
    <div  class="tab4-tab">
        <div id="dynamic-qr">
            
        <div class="qrc_wrap-md-8">
        <form method="post" action="options.php" class="qrcpro_vacradsubmits" >              

         <?php  

             settings_fields("qrc_vcard_generator");
             do_settings_sections('qrc_vacrd_admin_sec');
         ?>                <div class="qrcsubmits">
           <button type ="submit" id="osiudi" class="button button-primary"><?php echo esc_html('Save Changes','qr-code-composer') ?> <span class="qrcvcard_sdhi"></span></button>
           <span class="qrcvcard_djkfhjhj"></span>    </div>
         </form>
         </div>
<div class="qrc_wrap-md-4">
             <div>
                 <div class="qrc_pro_ftcs_cont">
                     <h4 class="pro_ftcs__h">Bulk vCard (Premium)</h4>
 <img src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/vcarddemo-min.png') ?>" alt="Pro Features" class="varcdemos">
                     
                    <a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/post.php?post=3133&action=edit">Admin Demo</a>
<h4 class="pro_ftcs__h">Downlaod All vCard Images: (Premium)</h4>
 <p>You can download all Meta vCard QR code images from one page. Ability to filter by post type and pagination. </p>                    
 <img src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/downlaod-vcard-min.png') ?>" alt="Pro Features" class="varcdemos">
<a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_vcardlist&tab=vcardlist">Backend demo</a>



 
                 </div>
             </div>
         </div>         
    </div>
            </div>
    <div  class="tab5-tab">
        <div id="dynamic-qr">
            
        <div class="qrc_wrap-md-8">
        <form method="post" action="options.php" class="qrcpro_integration">              

         <?php  

             settings_fields("qrc_admin_integrate");
             do_settings_sections('qrc_admin_integrate_sec');
         ?>
 

  <div class="qrcsubmits">
                  <button type ="submit" id="osiudi" class="button button-primary"><?php echo esc_html('Save Changes','qr-code-composer') ?> <span class="qrcintegrates"></span></button>
         <span class="qrcintegrates_djkfhjhj"></span>
            </div>



         </form>
    </div>
<div class="qrc_wrap-md-4">
             <div>
                 <div class="qrc_pro_ftcs_cont">
                     <h4 class="pro_ftcs__h">Widget API Suported (Premium)</h4>
 <p>Users can create and display QR codes using WordPress widgets</p>                    
 <img src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/widget-min.png') ?>" alt="Pro Features" class="varcdemos">
<a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/widgets.php">Widget Backend demo</a>
                     <h4 class="pro_ftcs__h">Elementor Addon: (Premium)</h4>
 <p>We have introduced the “QR Code Composer” addon to generate QR codes with Elementor. With this addon, you can generate QR codes for the current page URL, custom link, custom text, Google map location, Wi-Fi access, WhatsApp access, Vcard, event QR, email, and number.</p>                    
 <img src="<?php echo esc_url(QRC_COMPOSER_URL . '/admin/img/elementor-min.png') ?>" alt="Pro Features" class="varcdemos">
<a class="qrc_gtnow" href="https://qrc-admin.dipashi.com/wp-admin/post.php?post=2984&action=elementor">Elementor Backend demo</a>
<a class="oreremils" id="elemtvide" video-url="https://www.youtube.com/watch?v=USdX-PuPjuU&t=3s" style="cursor: pointer;"><span title="Video Documentation" id="qrcdocsides" class="dashicons dashicons-video-alt3"></span></a>

                 </div>
             </div>
         </div>  
         </div>  
         </div>  
  </div> <!-- END tabs-content -->
  </div>
        <div class="qrcProsoComosebox">
         
                                
         </div>
     </div>


    <?php
     }


}
if(class_exists('QRCAdminMain')){

  new QRCAdminMain();
}