<?php
/**
 * The file that defines admin area
 *
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      1.0.0
 *
 * @package    qrcode
 * @subpackage qrcode/admin
 */



function qrcode_vcard_single_gnr_lite($rand,$size,$name,$company,$subtitle,$mobile,$phone,$email,$website,$address,$note,$qr_download_){


	$mastervcard = 'BEGIN:VCARD\nVERSION:3.0\nN:'.$name.'\nORG:'.$company.'\nTITLE:'.$subtitle.'\nTEL:'.$phone.'\nTEL:'.$mobile.'\nURL:'.$website.'\nEMAIL:'.$email.'\nADR:'.$address.'\nNOTE:'.$note.'\nEND:VCARD';

    return printf('<div><img id="QRC_vCard_" alt="%s" ></div>'.esc_html($qr_download_).'<script>
    	function QRC_LVcard(){var qr = window.qr = new QRious({
    	              element: document.getElementById("QRC_vCard_"),
    	              size: %s,
    	              value: "'.esc_js($mastervcard).'"
    	            });
    	            jQuery("#downloadvCradRand").click(function() {
    	        download(jQuery("#QRC_vCard_").attr("src"),"%s.png","image/png");
    	        });}QRC_LVcard();

		</script>',esc_js($name),esc_js($size),esc_js($name));

}
