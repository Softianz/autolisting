( function( $ ) {
'use strict';
    function qrc_vcardcontent() {	 
	   jQuery(".qrc_vcardcontent").each(function() {
	var el = kjua({
    text: $(this).data('text'),
    size:datas.size,
	fill: datas.color,
	back: datas.background,
	quiet:datas.quiet,
	ecLevel:datas.ecLevel,
    });	
		            var ereer = $(this).attr('id');
            var $Mqrds = '#' + ereer;
	document.querySelector($Mqrds).appendChild(el);
	
	
    });

	}qrc_vcardcontent();

	       function qrcomposerdownlaods(){
        jQuery( ".qrcdownload" ).each(function(index) {
            $(this).on("click", function(){
        var qrcFindImages =  $(this).closest('.qrcprowrapper').children('.qrc_qrcode,.qrc_vcardcontent').find('img').attr('src');
        jQuery(this).attr("href",qrcFindImages)
                
            });
        });
    }qrcomposerdownlaods();
	       function QRCdownloads1(){
        jQuery("#QRCdownloads1").each(function(index) {
            $(this).on("click", function(){
        var qrcFindImages =  $(this).closest('.qrcprowrapper').children('.qrc_qrcode,.qrc_vcardcontent').find('img').attr('src');
        jQuery(this).attr("href",qrcFindImages)
                
            });
        });
    }QRCdownloads1();


		$("#QRCdownloads").click(function(){
        var download = document.getElementById("QRCdownloads");
        var imagesa = jQuery("#billing img").attr("src");
        download.setAttribute("href", imagesa);
        
        });	
		

}(jQuery) );