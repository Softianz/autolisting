
var ajaxCustomFieldRequest;
var all_loaded_icons = {};
jQuery(document).ready(function($){
    
    // custom fields
    $(document).on('click', '.apuslistdo-custom-field-add-available-field', function () {
        "use strict";
        if ( $(this).parent().hasClass('disabled') ) {
            alert('You have add this field');
            return false;
        }

        global_custom_field_counter++;
        var ajax_url = apuslistdo_customfield_common_vars.ajax_url,
                $this = $(this),
                randid = $this.data('randid'),
                fieldtype = $this.data('fieldtype'),
                fieldlabel = $this.data('fieldlabel'),
                field_container = $('#foo' + randid);

        var action = 'apuslistdo_custom_field_available_html';
        var old_text = $this.html();
        //$this.html('<i class="dashicons dashicons-update fa-spin"></i>');
        $this.find('i').attr( 'class','dashicons dashicons-update fa-spin');
        
        var dataString = 'action=' + action + '&fieldtype=' + fieldtype + '&global_custom_field_counter=' + global_custom_field_counter;

        
        if (typeof (ajaxCustomFieldRequest) != 'undefined') {
            ajaxCustomFieldRequest.abort();
        }
        ajaxCustomFieldRequest = $.ajax({
            type: "POST",
            url: ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                if (response != 'error') {
                    $this.html(old_text);
                    field_container.append(response.html);

                    $this.parent().addClass('disabled');

                    $('input.show_in_package', field_container).each(function(){
                        change_checkbox_show_in_package($(this));
                    });

                } else {
                    $this.html(' There is an error.');
                }
            }
        });

        return false;
    });

    $(document).on('click', '.btn-add-field', function () {
        "use strict";
        global_custom_field_counter++;
        var ajax_url = apuslistdo_customfield_common_vars.ajax_url,
                $this = $(this),
                randid = $this.data('randid'),
                select_element = $('.apuslistdo-field-types'),
                fieldtype = select_element.val(),
                field_container = $('#foo' + randid);

        var action = '';
        var old_text =  $this.html();
        $this.html('<i class="dashicons dashicons-update fa-spin"></i>');
        action = 'apuslistdo_custom_field_html';
        var dataString = 'action=' + action + '&fieldtype=' + fieldtype + '&global_custom_field_counter=' + global_custom_field_counter;

        if (typeof (ajaxCustomFieldRequest) != 'undefined') {
            ajaxCustomFieldRequest.abort();
        }
        ajaxCustomFieldRequest = $.ajax({
            type: "POST",
            url: ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                if (response != 'error') {
                    $this.html(old_text);
                    field_container.append(response.html);

                    $('input.show_in_package', field_container).each(function(){
                        change_checkbox_show_in_package($(this));
                    });
                } else {
                    $this.html(' There is an error.');
                }
            }
        });

        return false;
    });

    $(document).on('click', '.custom-fields-remove', function () {
        "use strict";
        var $this = $(this),
                randid = $this.data('randid');
        var parent_ul = $('.custom-field-class-' + randid).parents('ul');
        var fieldtype = $this.data('fieldtype');

        $('.custom-field-class-' + randid).slideUp("normal").promise().done(function () {
            $(this).remove();
            $('.apuslistdo-form-field-list ul li.'+ fieldtype).removeClass('disabled');
            // show empty msg if all lis removed
            if (parent_ul.children().length <= 1) {
                parent_ul.find('.custom-field-empty-msg').show('slow');
            }
        });
    });

    //
    function change_checkbox_show_in_package(element){
        if ( element.prop('checked') == true ) {
            element.closest('.form-group-wrapper').find('.show_if_show_in_package').show();
        } else {
            element.closest('.form-group-wrapper').find('.show_if_show_in_package').hide();
        }
    }
    if ( $('input.show_in_package').length > 0 ) {
        change_checkbox_show_in_package($('input.show_in_package'));
    }
    $(document).on('change', 'input.show_in_package', function () {

        change_checkbox_show_in_package( $(this) );
    });

    $(document).on('click', '.custom-fields-edit', function () {
        "use strict";
        var parent = $(this).parent().parent();
        
        parent.find('.field-data').slideToggle();
    });
    
    $(document).on('keyup change', '.apuslistdo-custom-field-label', function(e){
        var parent = $(this).parents('.apuslistdo-custom-field-container');
        parent.find('.field-intro a b').text( '(' + $(this).val() + ')' );
    });

    $(document).on('keyup change', '.apuslistdo-custom-field-key', function(e){
        if (!apuslistdo_isArrowKey(e)) {
            var new_val = apuslistdo_str2url($(this).val().replace(/^[0-9]+\./, ''), 'UTF-8');
            $(this).val(new_val);
            var check = apuslistdo_check_key_available(new_val);
            if ( check ) {
                $(this).parent().append('<span class="error">This key is not right</span>');
                $(this).removeClass('error').addClass('error');
            } else {
                $(this).removeClass('error');
                $(this).parent().find('.error').remove();
            }
        }
    });

    function apuslistdo_isArrowKey(k_ev)
    {
        var unicode=k_ev.keyCode? k_ev.keyCode : k_ev.charCode;
        if (unicode >= 37 && unicode <= 40)
            return true;
        return false;
    }

    function apuslistdo_str2url(str, encoding, ucfirst)
    {
        str = str.toUpperCase();
        str = str.toLowerCase();
        
            /* Lowercase */
            str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E5\u0101\u0103\u0105\u0430]/g, 'a');
            str = str.replace(/[\u0431]/g, 'b');
            str = str.replace(/[\u00E7\u0107\u0109\u010D\u0446]/g, 'c');
            str = str.replace(/[\u010F\u0111\u0434]/g, 'd');
            str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B\u0435\u044D]/g, 'e');
            str = str.replace(/[\u0444]/g, 'f');
            str = str.replace(/[\u011F\u0121\u0123\u0433\u0491]/g, 'g');
            str = str.replace(/[\u0125\u0127]/g, 'h');
            str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131\u0438\u0456]/g, 'i');
            str = str.replace(/[\u0135\u0439]/g, 'j');
            str = str.replace(/[\u0137\u0138\u043A]/g, 'k');
            str = str.replace(/[\u013A\u013C\u013E\u0140\u0142\u043B]/g, 'l');
            str = str.replace(/[\u043C]/g, 'm');
            str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B\u043D]/g, 'n');
            str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F8\u014D\u014F\u0151\u043E]/g, 'o');
            str = str.replace(/[\u043F]/g, 'p');
            str = str.replace(/[\u0155\u0157\u0159\u0440]/g, 'r');
            str = str.replace(/[\u015B\u015D\u015F\u0161\u0441]/g, 's');
            str = str.replace(/[\u00DF]/g, 'ss');
            str = str.replace(/[\u0163\u0165\u0167\u0442]/g, 't');
            str = str.replace(/[\u00F9\u00FA\u00FB\u0169\u016B\u016D\u016F\u0171\u0173\u0443]/g, 'u');
            str = str.replace(/[\u0432]/g, 'v');
            str = str.replace(/[\u0175]/g, 'w');
            str = str.replace(/[\u00FF\u0177\u00FD\u044B]/g, 'y');
            str = str.replace(/[\u017A\u017C\u017E\u0437]/g, 'z');
            str = str.replace(/[\u00E4\u00E6]/g, 'ae');
            str = str.replace(/[\u0447]/g, 'ch');
            str = str.replace(/[\u0445]/g, 'kh');
            str = str.replace(/[\u0153\u00F6]/g, 'oe');
            str = str.replace(/[\u00FC]/g, 'ue');
            str = str.replace(/[\u0448]/g, 'sh');
            str = str.replace(/[\u0449]/g, 'ssh');
            str = str.replace(/[\u044F]/g, 'ya');
            str = str.replace(/[\u0454]/g, 'ye');
            str = str.replace(/[\u0457]/g, 'yi');
            str = str.replace(/[\u0451]/g, 'yo');
            str = str.replace(/[\u044E]/g, 'yu');
            str = str.replace(/[\u0436]/g, 'zh');

            /* Uppercase */
            str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5\u0410]/g, 'A');
            str = str.replace(/[\u0411]/g, 'B');
            str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C\u0426]/g, 'C');
            str = str.replace(/[\u010E\u0110\u0414]/g, 'D');
            str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A\u0415\u042D]/g, 'E');
            str = str.replace(/[\u0424]/g, 'F');
            str = str.replace(/[\u011C\u011E\u0120\u0122\u0413\u0490]/g, 'G');
            str = str.replace(/[\u0124\u0126]/g, 'H');
            str = str.replace(/[\u0128\u012A\u012C\u012E\u0130\u0418\u0406]/g, 'I');
            str = str.replace(/[\u0134\u0419]/g, 'J');
            str = str.replace(/[\u0136\u041A]/g, 'K');
            str = str.replace(/[\u0139\u013B\u013D\u0139\u0141\u041B]/g, 'L');
            str = str.replace(/[\u041C]/g, 'M');
            str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A\u041D]/g, 'N');
            str = str.replace(/[\u00D3\u014C\u014E\u0150\u041E]/g, 'O');
            str = str.replace(/[\u041F]/g, 'P');
            str = str.replace(/[\u0154\u0156\u0158\u0420]/g, 'R');
            str = str.replace(/[\u015A\u015C\u015E\u0160\u0421]/g, 'S');
            str = str.replace(/[\u0162\u0164\u0166\u0422]/g, 'T');
            str = str.replace(/[\u00D9\u00DA\u00DB\u0168\u016A\u016C\u016E\u0170\u0172\u0423]/g, 'U');
            str = str.replace(/[\u0412]/g, 'V');
            str = str.replace(/[\u0174]/g, 'W');
            str = str.replace(/[\u0176\u042B]/g, 'Y');
            str = str.replace(/[\u0179\u017B\u017D\u0417]/g, 'Z');
            str = str.replace(/[\u00C4\u00C6]/g, 'AE');
            str = str.replace(/[\u0427]/g, 'CH');
            str = str.replace(/[\u0425]/g, 'KH');
            str = str.replace(/[\u0152\u00D6]/g, 'OE');
            str = str.replace(/[\u00DC]/g, 'UE');
            str = str.replace(/[\u0428]/g, 'SH');
            str = str.replace(/[\u0429]/g, 'SHH');
            str = str.replace(/[\u042F]/g, 'YA');
            str = str.replace(/[\u0404]/g, 'YE');
            str = str.replace(/[\u0407]/g, 'YI');
            str = str.replace(/[\u0401]/g, 'YO');
            str = str.replace(/[\u042E]/g, 'YU');
            str = str.replace(/[\u0416]/g, 'ZH');

            str = str.toLowerCase();

            str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]/g,'');
        
        str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u002F\u003A\u003B\u003C\u003D\u003E]/g, '');
        str = str.replace(/[\s\'\:\/\[\]-]+/g, ' ');

        // Add special char not used for url rewrite
        str = str.replace(/[ ]/g, '-');
        str = str.replace(/[\/\\"'|,;%]*/g, '');

        if (ucfirst == 1) {
            var first_char = str.charAt(0);
            str = first_char.toUpperCase()+str.slice(1);
        }

        return str;
    }

    function apuslistdo_check_key_available($key) {
        var $i = 0;
        $('.apuslistdo-custom-field-key').each(function(){
            if ( $key === $(this).val() ) {
                $i = $i + 1;
            }
        });
        if ( $i > 1 ) {
            return true;
        } else {
            return false;
        }
        
    }
    

    // listing types
    var listing_type = $('.style-wrapper .style-item input[type=radio]:checked').val();
    $('.style-wrapper .style-item').on('click', function(){
        var parent = $(this).closest('.style-wrapper');
        $('.style-item', parent).removeClass('active');
        $(this).addClass('active');

        var listing_type_new = $('.style-wrapper .style-item input[type=radio]:checked').val();
        console.log( listing_type +' | ' + listing_type_new);
        if ( listing_type !== listing_type_new ) {
            $('.show-when-type-changed').show(800);
            $('.custom-fields-wrapper').slideUp();
        } else {
            $('.show-when-type-changed').hide(800);
            $('.custom-fields-wrapper').slideDown();
        }
    });

    // icons
    $(function(){
        $("body").trigger("all_loading_icons");
        
        $('#apus_tax_icon_font').fontIconPicker({
            theme: 'fip-bootstrap',
            source: all_loaded_icons
        });

        var icon_type = $('input[name=apus_icon_type]:checked').val();
        $('.icon-type-wrapper').hide();
        if ( icon_type == 'font' ) {
            $('.icon-type-font').show();
        } else {
            $('.icon-type-image').show();
        }
        $('input[name=apus_icon_type]').change(function(){
            var icon_type = $('input[name=apus_icon_type]:checked').val();
            $('.icon-type-wrapper').hide();
            if ( icon_type == 'font' ) {
                $('.icon-type-font').show();
            } else {
                $('.icon-type-image').show();
            }
        });

        //
        var icon_type = $('input[name=apus-type-icon-type]:checked').val();
        $('.apus-type-icon-type-wrapper').hide();
        if ( icon_type == 'font' ) {
            $('.apus-type-icon-type-font').show();
        } else {
            $('.apus-type-icon-type-image').show();
        }
        $('input[name=apus-type-icon-type]').change(function(){
            var icon_type = $('input[name=apus-type-icon-type]:checked').val();
            $('.apus-type-icon-type-wrapper').hide();
            if ( icon_type == 'font' ) {
                $('.apus-type-icon-type-font').show();
            } else {
                $('.apus-type-icon-type-image').show();
            }
        });

        
        $('#apus_tax_color_input').wpColorPicker();
    });
    
});
