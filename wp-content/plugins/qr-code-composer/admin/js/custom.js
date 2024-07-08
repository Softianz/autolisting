(function($) {
		$(window).scroll(function() {
    if ($(window).scrollTop() > 200) {
             $('.bulptinyprowarp').fadeIn(300);
    }
});
    $(document).ready(function() {
        "use strict";
        $("#elemtvide").videoPopup();
        $("#orderemail").videoPopup();
        $("#qrcauto").videoPopup();
        $("#qclinks").videoPopup();
        $("#qcphone").videoPopup();
        $("#qcwhatapp").videoPopup();
        $("#qcwifi").videoPopup();
        $("#qrcmap").videoPopup();
        $("#qrcevent").videoPopup();
        $("#qrcbbpress").videoPopup();
        $("#qrcbdypress").videoPopup();
        $("#qrcdokan").videoPopup();
        $("#qrcvcardfs").videoPopup();
        $('.qrcdesings').submit(function() {
            $('.qrcs_desingcrt').addClass("fancyLoaderCss");
            $('.qrcsdhicr_dsigns').hide();
            var b = $(this).serialize();
            $.post('options.php', b).error(function() {
                alert('error')
            }).success(function() {
                $(".qrcs_desingcrt").removeClass("fancyLoaderCss");
                $('.qrcsdhicr_dsigns').show();
                $('.qrcsdhicr_dsigns').html('<span class="dashicons dashicons-saved"></span>')
            });
            return !1
        });
        $('.qrccurrentqrcs').submit(function() {
            $('.qrcs_sdhicrt').addClass("fancyLoaderCss");
            $('.qrcsdhicr_djkfhjhj').hide();
            var b = $(this).serialize();
            $.post('options.php', b).error(function() {
                alert('error')
            }).success(function() {
                $(".qrcs_sdhicrt").removeClass("fancyLoaderCss");
                $('.qrcsdhicr_djkfhjhj').show();
                $('.qrcsdhicr_djkfhjhj').html('<span class="dashicons dashicons-saved"></span>')
            });
            return !1
        });

        $('.qrcpro_vacradsubmits').submit(function() {
            $('.qrcvcard_sdhi').addClass("fancyLoaderCss");
            $('.qrcvcard_djkfhjhj').hide();
            var b = $(this).serialize();
            $.post('options.php', b).error(function() {
                alert('error')
            }).success(function() {
                $(".qrcvcard_sdhi").removeClass("fancyLoaderCss");
                $('.qrcvcard_djkfhjhj').show();
                $('.qrcvcard_djkfhjhj').html('<span class="dashicons dashicons-saved"></span>')
            });
            return !1
        });
        $('.qrcpro_integration').submit(function() {
            $('.qrcintegrates').addClass("fancyLoaderCss");
            $('.qrcintegrates_djkfhjhj').hide();
            var b = $(this).serialize();
            $.post('options.php', b).error(function() {
                alert('error')
            }).success(function() {
                $(".qrcintegrates").removeClass("fancyLoaderCss");
                $('.qrcintegrates_djkfhjhj').show();
                $('.qrcintegrates_djkfhjhj').html('<span class="dashicons dashicons-saved"></span>')
            });
            return !1
        });
		
		
		

        $('.qrcprodemo').datetimepicker({
            timepicker: false,
            format: 'Y/m/d'
        });
        $('.qrcprodemo1').datetimepicker({
            datepicker: false,
            format: 'H:i'
        });
        $('.qrcprodemo2').datetimepicker({
            timepicker: false,
            format: 'Y/m/d'
        });
        $('.qrcprodemo3').datetimepicker({
            datepicker: false,
            format: 'H:i'
        });

        function bbpressoptions() {
            $('.bbpressoptions').on('change', function() {
                if ($(this).val() == 'none' || $(this).val() == 'url') {
                    $('.bbpressremovefiled').hide();

                } else {
                    $('.bbpressremovefiled').show()
                }

                if ($(this).val() == 'shortcode') {
                    $('.shortcodes').show();
                } else {
                    $('.shortcodes').hide();

                }
            });

            if ($('.bbpressoptions').val() == 'none' || $('.bbpressoptions').val() == 'url') {
                $('.bbpressremovefiled').hide();

            } else {
                $('.bbpressremovefiled').show()
            }
            if ($('.bbpressoptions').val() == 'shortcode') {
                $('.shortcodes').show();
            } else {
                $('.shortcodes').hide();

            }
        }
        bbpressoptions();

        function qrbudypressoptions() {


            $('.qrbudypressoptions').on('change', function() {
                if ($(this).val() == 'none' || $(this).val() == 'url') {
                    $('.budypressremovefiled').hide();

                } else {
                    $('.budypressremovefiled').show()
                }

                if ($(this).val() == 'shortcode') {
                    $('.shortcodesr').show();
                } else {
                    $('.shortcodesr').hide();

                }
            });

            if ($('.qrbudypressoptions').val() == 'none' || $('.qrbudypressoptions').val() == 'url') {
                $('.budypressremovefiled').hide();

            } else {
                $('.budypressremovefiled').show()
            }
            if ($('.qrbudypressoptions').val() == 'shortcode') {
                $('.shortcodesr').show();
            } else {
                $('.shortcodesr').hide();

            }

        }
        qrbudypressoptions();

        function qrcdokhanoptions() {


            $('.dokanqrc').on('change', function() {
                if ($(this).val() == 'none' || $(this).val() == 'url') {
                    $('.dokanremovefiled').hide();

                } else {
                    $('.dokanremovefiled').show()
                }

                if ($(this).val() == 'shortcode') {
                    $('.shortcodesdokan').show();
                } else {
                    $('.shortcodesdokan').hide();

                }
            });

            if ($('.dokanqrc').val() == 'none' || $('.dokanqrc').val() == 'url') {
                $('.dokanremovefiled').hide();

            } else {
                $('.dokanremovefiled').show()
            }
            if ($('.dokanqrc').val() == 'shortcode') {
                $('.shortcodesdokan').show();
            } else {
                $('.shortcodesdokan').hide();

            }

        }
        qrcdokhanoptions();


    });

    $(document).ready(function() {
        $("#inputetxtas").on("input", function() {
            // Print entered value in a div box
            $("#result").text($(this).val());
        });

        $('.qrcremovedownlaod').on('change', function() {
            if ($(this).val() == 'yes') {
                $('.qrdemodownload').hide();
                $('.removealsscolors').hide();

            } else {
                $('.qrdemodownload').show();
                $('.removealsscolors').show();
            }
        });

        if ($('.qrcremovedownlaod').val() == 'yes') {
            $('.qrdemodownload').hide();
            $('.removealsscolors').hide();

        } else {
            $('.qrdemodownload').show();
            $('.removealsscolors').show();

        }



        $('.qrc-btn-color-picker').wpColorPicker({
            change: function(event, ui) {
                var theColor = ui.color.toString();
                $('#result').css("color", theColor);
            }
        });
        $('.qrc-btn-bg-picker').wpColorPicker({
            change: function(event, ui) {
                var theColor = ui.color.toString();
                $('#result').css("background", theColor);
            }
        });


    });




    $(document).ready(function() {
        $('#qr_print_tzx_ty').on('change', function() {
            $('#qr_print_product_ty').hide();
            if ($(this).val() == 'product_cat') {
                $('#qr_print_product_ty').show();
                $('#qr_print_cat_ty').hide()
            } else {
                $('#qr_print_product_ty').hide();
                $('#qr_print_cat_ty').show()
            }
        });
		
		
		
		$('.alignme th').append("<p>Choose the alignment of the QR code, by default it will be on the left</p>");
        $('.wcalignme th').append("<p>Choose the alignment of the QR code on the product page, by default it will be on the left</p>");
        $('.ptab_name th').append("<p>The QR code is located in the tab called QR Code on the product page. Rename tab name</p>");
        $('.qr_checkbox th').append("<p>Disable the QR code for specific post type that you wish to prevent the QR code on.</p>");
        $('.qr_checkbox_page th').append("<p>Disable the QR code for specific pages that you wish to prevent the QR code on. Also can remove QR code from page, post, product, custom post using meta field</p>");
        $('.qr_stcode_management th').append("<p>This is the shortcode for generating QR code of current page, current page url will be used as content of QR code.</p>");
        $('.qr_code_custom_text th').append("<p>Creating QR codes with custom links, static text or numbers as QR code content.</p>");
        $('.qr_code_phonenumber th').append("<p>Creating QR codes with Phone or Mobile number as QR code content.</p>");
        $('.qr_code_mail_text th').append("<p>Creating QR codes with WhatsApp Number as QR code content.</p>");
        $('.qr_code_wifi_text th').append("<p>Creating QR codes with WIFI Access as QR code content.</p>");
        $('.qr_code_event_text th').append("<p>Creating QR codes with Event Management as QR code content.</p>");
        $('.qr_code_maps_text th').append("<p>Creating QR codes with Google Map Location as QR code content.</p>");
        $('.qrc_metavcard_display th').append("<p>Automatically displaying QR Code for WooCommerce customers.</p>");


        $('.qr_code_vcard th').append("<p>A simple vCard QR code based on the information on the side. If you want to generate more, use the  <a href='https://qrc-admin.dipashi.com/wp-admin/admin.php?page=qrc_shortcode'> shortcode generator for vCard</a></p>");
        $('.qrc_vacrdtempe th').append("<p>vCard Templates is a feature for displaying vCard information on the frontend. <a href='https://qrcode-composer.dipashi.com/card/elizabeth-i-brown/'>View Demo</a></p>");
		        $('.qrc_vacrdauodisplay th').append("<p>Meta vCard QR code will be automatically displayed on the frontend after the content.</p>");
		
		
        $('.qr_checkbox_vcrad th').append("<p>Clicking the switcher button next to the post type name will enable the vCard meta field for that post type<a href='https://qrcode-composer.dipashi.com/docs/automatically-display-vcard/' target='_blank'> Read Docs</a></p>");

        $('.qrc_userdsiplay th').append("<p>The plugin generates auto QR codes for user profiles, vCard QR code are generated from user profile information, If you want to close <a href='profile.php/#qrcuserQRcode'>Look Profile Page</a></p>");
        $('.qrc_bbpress_display th').append("<p>Forum Memeber QR Code Generator for BB Press. QR code of memeber's profile URL and memeber's vCard QR code. <a href='https://qrcode-composer.dipashi.com/forums/users/dipashi/'> View Demo</a></p>");
        $('.qrc_bdypress_display th').append("<p>  Profile QR Code Generator for Buddy Press. QR code of user's profile URL and user's vCard QR code. <a href='https://qrcode-composer.dipashi.com/members/dipashi/profile/qr-code/'> View Demo</a></p>");
        $('.qrc_dokan_display th').append("<p>Vendor QR code generator for Dokan. QR code of vendor's profile URL and vendor's vCard QR code. <a href='https://qrcode-composer.dipashi.com/members/dipashi/profile/qr-code/'> View Demo</a></p>");

    })
})(jQuery);
(function($) {

	
jQuery(document).ready(function($) {

  // Suffix that will be used on the classes of the content divs.
  var tab_suffix = '-tab';

  // Not necessary, just to enable people to choose whatever tab_suffix they want.
  function escape_regexp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
  }

  // Get the class ending with tab_suffix from an element.
  function get_tab_name_from_class(el) {
    var tab_class_pattern = new RegExp('\\S*' + escape_regexp(tab_suffix));
    if ($(el) && $(el).attr('class')) {
      return $(el).attr('class').match(tab_class_pattern)[0];
    }
  }

  // Update the dom with the selected tab.
  function hash_content_update() {

    var active_section,
      tab_names;

    // Get all classes ending with -tab from div elements directly inside .tab-content.
    tab_names = $('div.tab-content > div').map(function() {
      var tab_name = get_tab_name_from_class($(this));
      if (tab_name) {
        return tab_name.split(tab_suffix)[0];
      }
    }).get();

    if (tab_names.length > 0) {

      // Show first tab initially.
      active_section = tab_names[0];

      // Check if the url hash matches one of the tab names.
      if (document.location.href.split('#')[1] && tab_names.indexOf(document.location.href.split('#')[1]) > -1) {
        active_section = document.location.href.split('#')[1];
      }
      // Handle tab contents.
      $('div.tab-content div.active').removeClass('active');
      $('div.tab-content div.' + active_section + tab_suffix).addClass('active');

      // Handle tab menu.
      $('div.tab-nav ul li.active').removeClass('active');
      $('div.tab-nav ul li a[href="#' + active_section + '"]').closest('li').addClass('active');

    }

  }

  // Set listener for hashchange
  $(window).bind('hashchange', function() {
    hash_content_update();
  });

  // Run the initial content update.
  hash_content_update();

});

}(jQuery));

(function($) {
    $(document).ready(function() {
        "use strict";
		$("#qrc-vides").videoPopup();
		$("#qrc-prints").videoPopup();
		$("#qrc-shortcoe").videoPopup();
		$("#qrc-find").videoPopup();
		$("#qrc-oder").videoPopup();
		$("#qrc-pdf").videoPopup();
		$("#qrc-downl").videoPopup();
		});

}(jQuery));