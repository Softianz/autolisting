<?php

$options = get_option('qrc_vcard_generator');
$rand = wp_rand(37684782, 23297323);
$name = isset($options['qrcvcardsingle_name']) ? $options['qrcvcardsingle_name'] : '';    
$company = isset($options['qrcvcardsingle_company']) ? $options['qrcvcardsingle_company'] : '';
$subtitle = isset($options['qrcvcardsingle_subtitle']) ? $options['qrcvcardsingle_subtitle'] : '';
$mobile = isset($options['qrcvcardsingle_mbunber']) ? $options['qrcvcardsingle_mbunber'] : '';
$phone = isset($options['qrcvcardsingle_pbunber']) ? $options['qrcvcardsingle_pbunber'] : '';
$email = isset($options['qrcvcardsingle_email']) ? $options['qrcvcardsingle_email'] : '';
$address = isset($options['qrcvcardsingle_address']) ? $options['qrcvcardsingle_address'] : '';
$note = isset($options['qrcvcardsingle_note']) ? $options['qrcvcardsingle_note'] : '';
$website = isset($options['qrcvcardsingle_website']) ? $options['qrcvcardsingle_website'] : '';

$size = isset($options['qrcvcrad_size_width']) ? $options['qrcvcrad_size_width'] : 200;

$download_qr = isset($options['qrcvcrad_download_text']) ? $options['qrcvcrad_download_text'] : esc_html__('Download QR', "qr-code-composer");
$qrcvcrad_dwnbtn_color = isset($options['qrcvcrad_dwnbtn_color']) ? $options['qrcvcrad_dwnbtn_color'] : '#000';
$qrcvcrad_btnbg_color = isset($options['qrcvcrad_btnbg_color']) ? $options['qrcvcrad_btnbg_color'] : '#c8fd8c';

$qrcvcrad_wnload_hide = isset($options['qrcvcrad_wnload_hide']) ? $options['qrcvcrad_wnload_hide'] : 'no';