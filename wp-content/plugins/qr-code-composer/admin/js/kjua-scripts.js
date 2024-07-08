/* eslint-disable func-names,no-var,prefer-reflect,prefer-arrow-callback */
(function () {

    var win = window; // eslint-disable-line no-undef
    var FR = win.FileReader;
    var doc = win.document;
    var kjua = win.kjua;

    var gui_val_pairs = [
        ['size', 'px'],
        ['minversion', ''],
        ['quiet', ' modules'],
        ['rounded', '%'],
        ['msize', '%'],
        ['mposx', '%'],
        ['mposy', '%']
    ];

    function el_by_id(id) {
        return doc.getElementById(id);
    }

    function val_by_id(id) {
        var el = el_by_id(id);
        return el && el.value;
    }

    function int_by_id(id) {
        return parseInt(val_by_id(id), 10);
    }

    function on_event(el, type, fn) {
        el.addEventListener(type, fn);
    }

    function on_ready(fn) {
        on_event(doc, 'DOMContentLoaded', fn);
    }

    function for_each(list, fn) {
        Array.prototype.forEach.call(list, fn);
    }

    function all(query, fn) {
        var els = doc.querySelectorAll(query);
        if (fn) {
            for_each(els, fn);
        }
        return els;
    }

    function update_gui() {
        gui_val_pairs.forEach(function (pair) {
            var label = all('label[for="' + pair[0] + '"]')[0];
            var text = label.innerHTML;
            label.innerHTML = text.replace(/:.*$/, ': ' + val_by_id(pair[0]) + pair[1]);
        });
    }

    function update_qrcode() {
        var options = {
            render: val_by_id('render'),
            size: val_by_id('size'),
            text: "QR Code Composer",
            ecLevel: val_by_id('ecLevel'),
            fill: val_by_id('fill'),
            back: val_by_id('qr_bg'),
            quiet: int_by_id('quiet'),

            mode: 'image',
            mSize: int_by_id('imagesize'),
            mPosX: 50,
            mPosY: 50,
            image: el_by_id('wooqrimg-buffer')
        };
        //console.log(val_by_id('fontname'));
        var container = el_by_id('qrccomsposerprview');
        var qrcode = kjua(options);
        for_each(container.childNodes, function (child) {
            container.removeChild(child);
        });
        if (qrcode) {
            container.appendChild(qrcode);
        }
    }

    function update() {
        // update_gui();
        update_qrcode();
    }

    function on_img_input() {
        var input = el_by_id('wooqr_upload_image');
        //console.log(input.value);
        if (input.value) {
            setTimeout(update, 250);
        }
    }
    jQuery(document).ready(function () {

		 jQuery(".qrc-color-picker").wpColorPicker({
         change: function(event, ui) {
						
		 el_by_id('fill');
		el_by_id('qr_bg');

            setTimeout(update, 100);			
						
                        }
                    });


    })

	
	
	
	
	
	
	
    jQuery(document).ready(function () {

        jQuery(document).on("change input","#wooqr_upload_image", function(){
            var input = el_by_id('wooqr_upload_image');
            console.log(input.value);
            setTimeout(update, 250);
        });


    })
    on_ready(function () {
        on_event(el_by_id('wooqr_upload_image'), 'change', function(){

        });
        all('input, textarea, select', function (el) {
            on_event(el, 'input', update);
            on_event(el, 'change', update);
        });
        on_event(win, 'load', update);
        update();
    });



}(jQuery));


