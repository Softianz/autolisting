( function( $ ) {
'use strict';
    function qrc_pageurl() {
jQuery(".qrc_qrcode").each(function() {
    var el = kjua({
    text: $(this).data('text'),
    size:qrcomspoer_options.size,
	fill: qrcomspoer_options.color,
	back: qrcomspoer_options.background,
	quiet:qrcomspoer_options.quiet,
	ecLevel:qrcomspoer_options.ecLevel,
    });
			            var ereer = $(this).attr('id');
            var $Mqrds = '#' + ereer;
	document.querySelector($Mqrds).appendChild(el);
	
			var $this = $(this).find('img');
			
			console.log($this);
			var btnHide = qrcomspoer_options.downloadhide;

			if(btnHide === 'no'){
			
			$this.after('<div><a href="' + $this.attr('src') + '" download="' + $(this).data('title')+'.png" class="qrcdownload"><button type="button" style="min-width:'+ qrcomspoer_options.size +"px;background:"+qrcomspoer_options.brnbg+";color:"+qrcomspoer_options.btnclr+';font-weight: 600;border: 1px solid transparent;padding: 6px 0;margin-top: 12px;" class="uqr_code_btn">'+qrcomspoer_options.btnlabel+ '</button></a></div>');
			}
		
	});

	}qrc_pageurl()


}(jQuery) );