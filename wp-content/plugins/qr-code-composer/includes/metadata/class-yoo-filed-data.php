<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.2.2
 * @package    Yoo_Bar
 * @subpackage Yoo_Bar/includes
 * @author     Sharabindu <info@sharabindu.com>
 */
class vCardQRMetabox {

       private $screens = array('post', 'page', 'card');

       private $fields = array(
         array(
           'label' => 'Full Name',
           'id' => 'qvcr_full_name',
           'type' => 'text',
           'description' => 'Enter your name as you want it display',
           'placeholder' => 'e.g: John Doe'
          ),
         array(
           'label' => 'Business Name',
           'id' => 'qvcr_bn_name',
           'type' => 'text',
           'description' => 'Enter your business name as you want it display ',
           'placeholder' => 'e.g: Gold Fashion'
          ),
         array(
           'label' => 'Position(s) / Title(s)',
           'id' => 'qvcr_ps_name',
           'type' => 'text',

           'placeholder' => 'e.g: Accountant'
          ),
         array(
            'label' => 'Image/Logo',
            'id' => 'media_yourlabel',
            'type' => 'media',
            'returnvalue' => 'url',
          ), 
         array(
           'label' => 'Business E-mail',
           'id' => 'qvcr_bn_email',
           'type' => 'email',
           'placeholder' => 'e.g: johndoe@domain.com'
          ),
         array(
           'label' => 'Website',
           'id' => 'qvcr_bn_site',
           'type' => 'text',
           'placeholder' => 'e.g: https://businessname.com'
          ),
         array(
           'label' => 'Business Phone Number',
           'id' => 'qvcr_bn_pnumber',
           'type' => 'text',
           'placeholder' => 'e.g: 12345678'
          ),
         array(
           'label' => 'Cell Phone Number',
           'id' => 'qvcr_bn_clnmber',
           'type' => 'text',
           'description' => '* If different from Business phone number.',
           'placeholder' => 'e.g: 987654321'
          ),
         array(
           'title' => 'Business Address',
           'label' => 'Street Address',
           'id' => 'qvcr_st_address',
           'type' => 'text',
           'class' => 'qvcr__address',

          ),
         array(
           'label' => 'Street Address line 2',
           'id' => 'qvcr_st_address2',
           'type' => 'text',
           'class' => 'qvcr__address',
          ),
         array(
           'label' => 'City',
           'id' => 'qvcr_city',
           'type' => 'text',
           'class' => 'qvcr__address',
          ),
         array(
           'label' => 'State / Province',
           'id' => 'qvcr_c_state',
           'type' => 'text',
           'class' => 'qvcr__address',
          ),
         array(
           'label' => 'Zip-Code',
           'id' => 'qvcr_zip_code',
           'type' => 'text',
           'class' => 'qvcr__address',
          ),
         array(
           'label' => 'Country',
           'id' => 'qvcr_country',
           'type' => 'select',
           'class' => 'qvcr__address',
           'options' => array(
              '',
              'Afghanistan',
              'Albania',
              'Algeria',
              'Andorra',
              'Angola',
              'Antigua and Barbuda',
              'Argentina',
              'Armenia',
              'Australia',
              'Austria',
              'Azerbaijan',
              'The Bahamas',
              'Bahrain',
              'Bangladesh',
              'Barbados',
              'Belarus',
              'Belgium',
              'Belize',
              'Benin',
              'Bhutan',
              'Bolivia',
              'Bosnia and Herzegovina',
              'Botswana',
              'Brazil',
              'Brunei',
              'Bulgaria',
              'Burkina Faso',
              'Burundi',
              'Cabo Verde',
              'Cambodia',
              'Cameroon',
              'Canada',
              'Central African Republic',
              'Chad',
              'Chile',
              'China',
              'Colombia',
              'Comoros',
              'Congo, {Democratic Republic}',
              'Congo',
              'Costa Rica',
              'Côte d’Ivoire',
              'Croatia',
              'Cuba',
              'Cyprus',
              'Czech Republic',
              'Denmark',
              'Djibouti',
              'Dominica',
              'Dominican Republic',
              'East Timor (Timor-Leste)',
              'Ecuador',
              'Egypt',
              'El Salvador',
              'Equatorial Guinea',
              'Eritrea',
              'Estonia',
              'Eswatini',
              'Ethiopia',
              'Fiji',
              'Finland',
              'France',
              'Gabon',
              'The Gambia',
              'Georgia',
              'Germany',
              'Ghana',
              'Greece',
              'Grenada',
              'Guatemala',
              'Guinea',
              'Guinea-Bissau',
              'Guyana',
              'Haiti',
              'Honduras',
              'Hungary',
              'Iceland',
              'India',
              'Indonesia',
              'Iran',
              'Iraq',
              'Ireland',
              'Israel',
              'Italy',
              'Jamaica',
              'Japan',
              'Jordan',
              'Kazakhstan',
              'Kenya',
              'Kiribati',
              'Korea, North',
              'Korea, South',
              'Kosovo',
              'Kuwait',
              'Kyrgyzstan',
              'Laos',
              'Latvia',
              'Lebanon',
              'Lesotho',
              'Liberia',
              'Libya',
              'Liechtenstein',
              'Lithuania',
              'Luxembourg',
              'Madagascar',
              'Malawi',
              'Malaysia',
              'Maldives',
              'Mali',
              'Malta',
              'Marshall Islands',
              'Mauritania',
              'Mauritius',
              'Mexico',
              'Micronesia',
              'Moldova',
              'Monaco',
              'Mongolia',
              'Montenegro',
              'Morocco',
              'Mozambique',
              'Myanmar (Burma)',
              'Namibia',
              'Nauru',
              'Nepal',
              'Netherlands',
              'New Zealand',
              'Nicaragua',
              'Niger',
              'Nigeria',
              'North Macedonia',
              'Norway',
              'Oman',
              'Pakistan',
              'Palau',
              'Panama',
              'Papua New Guinea',
              'Paraguay',
              'Peru',
              'Philippines',
              'Poland',
              'Portugal',
              'Qatar',
              'Romania',
              'Russia',
              'Rwanda',
              'Saint Kitts and Nevis',
              'Saint Lucia',
              'Saint Vincent and the Grenadines',
              'Samoa',
              'San Marino',
              'Sao Tome and Principe',
              'Saudi Arabia',
              'Senegal',
              'Serbia',
              'Seychelles',
              'Sierra Leone',
              'Singapore',
              'Slovakia',
              'Slovenia',
              'Solomon Islands',
              'Somalia',
              'South Africa',
              'Spain',
              'Sri Lanka',
              'Sudan',
              'Sudan, South',
              'Suriname',
              'Sweden',
              'Switzerland',
              'Syria',
              'Taiwan',
              'Tajikistan',
              'Tanzania',
              'Thailand',
              'Togo',
              'Tonga',
              'Trinidad and Tobago',
              'Tunisia',
              'Turkey',
              'Turkmenistan',
              'Tuvalu',
              'Uganda',
              'Ukraine',
              'United Arab Emirates',
              'United Kingdom',
              'United States',
              'Uruguay',
              'Uzbekistan',
              'Vanuatu',
              'Vatican City',
              'Venezuela',
              'Vietnam',
              'Yemen',
              'Zambia',
              'Zimbabwe',
           ),
          ),
         array(
           'label' => 'About your business/ about You',
           'id' => 'qvcr_about_business',
           'type' => 'textarea',
           'description' => 'Please describe your business or credentials, etc.',

          ),
         array(
           'label' => 'Facebook Link',
           'id' => 'qvcr_facebook',
           'type' => 'text',
          ),
         array(
           'label' => 'LinkedIn Link',
           'id' => 'qvcr_linkedin',
           'type' => 'text',
          ),
         array(
           'label' => 'Twitter Link',
           'id' => 'qvcr_twitter',
           'type' => 'text',
          ),
         array(
           'label' => 'Pinterest Link',
           'id' => 'qvcr_pinterest',
           'type' => 'text',
          ),
         array(
           'label' => 'YouTube Link',
           'id' => 'qvcr_youtube',
           'type' => 'text',
          ),
         array(
           'label' => 'Instagram Link',
           'id' => 'qvcr_instaragram',
           'type' => 'text',
          ),
         array(
           'label' => 'Other link',
           'id' => 'qvcr_othelink',
           'type' => 'text',
          ),
         array(
           'label' => 'Other link Two',
           'id' => 'qvcr_othelink2',
           'type' => 'text',
          )  
       );

       public function __construct() {
         add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
       }

       public function add_meta_boxes() {

        $post_types = get_post_types();
        foreach ($post_types as $post_type)
        {
         $options = get_option('qrc_vcard_generator');

           $screen = isset($options[$post_type]) ? $options[$post_type] : ' ';

               add_meta_box(
                 'vCardQR',
                 __( 'vCard QR', 'qr-code-composer' ),
                 array( $this, 'meta_box_callback' ),$screen
               ,
                 'normal',
                 'default'
               );
           } 

       }

       public function meta_box_callback( $post ) {
         wp_nonce_field( 'vCardQR_data', 'vCardQR_nonce' ); 
         echo "CONTACT INFORMATION";
         $this->field_generator( $post );
       }

       public function field_generator( $post ) {
         $output = '';
         foreach ( $this->fields as $field ) {
            if ( empty( $field['description'] ) ) {
        
                $field['description'] = '';
              }
              $description = '<p><em>'.$field['description'].'</em></p>';

              if ( empty( $field['title'] ) ) {
              
                  $vtitle = ' ';
                }else{
                 $vtitle = '<h4 class="vcard_meta_title">'.$field['title'].'</h4>';   
                }
              if ( empty( $field['class'] ) ) {
              
                  $vclass = ' ';
                }else{
                 $vclass = 'vcard_metclass';   
                }

           $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';

           $meta_value = get_post_meta( $post->ID, $field['id'], true );
           if ( empty( $meta_value ) ) {
             if ( isset( $field['default'] ) ) {
               $meta_value = $field['default'];
             }
           }
           if ( empty($field['placeholder'] ) ) {
               $placeholder = '';
             }else{
               $placeholder =  $field['placeholder'];
             }
           switch ( $field['type'] ) {
             case 'textarea':
               $input = sprintf(
                 '<textarea style="width: 100%%" id="%s" name="%s" rows="5">%s</textarea>',
                 $field['id'],
                 $field['id'],
                 $meta_value
               );
               break;
               case 'media':
                  $meta_url = '';
                  if ($meta_value) {
                    if ($field['returnvalue'] == 'url') {
                      $meta_url = $meta_value;
                    } else {
                      $meta_url = wp_get_attachment_url($meta_value);
                    }
                  }
                  $input = sprintf(
                    '<input style="display:none;" id="%s" name="%s" type="text" value="%s" data-return="%s"><div id="vCradpreview%s" style="background-color:#fafafa;margin-right:12px;border:1px solid #eee;width: 150px;height:150px;background-image:url(%s);background-size:cover;background-repeat:no-repeat;background-position:center;"></div><input style="width: 15%%;margin-right:5px;" class="button new-media" id="%s_button" name="%s_button" type="button" value="Select" /><input style="width: 15%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Delete" />',
                    $field['id'],
                    $field['id'],
                    $meta_value,
                    $field['returnvalue'],
                    $field['id'],
                    $meta_url,
                    $field['id'],
                    $field['id'],
                    $field['id'],
                    $field['id']
                  );
                  break;
             case 'select':
             $input = sprintf(
               '<select id="%s" name="%s">',
               $field['id'],
               $field['id']
             );
             foreach ( $field['options'] as $key => $value ) {
               $field_value = !is_numeric( $key ) ? $key : $value;
               $input .= sprintf(
                 '<option %s value="%s">%s</option>',
                 $meta_value === $field_value ? 'selected' : '',
                 $field_value,
                 $value
               );
             }
             $input .= '</select>';
             break;
       
             default:
               $input = sprintf(
               '<input %s id="%s" name="%s" type="%s" value="%s" placeholder="%s">',
               $field['type'] !== 'color' ? 'style="width: 100%"' : '',
               $field['id'],
               $field['id'],
               $field['type'],
               $meta_value,
               $placeholder
             );
           }
           $output .= $this->format_rows( $label, $input ,$description,$vtitle,$vclass);
         }
         echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
         $post_id = get_the_ID();
                 $qrc_qr_image = '<div id="qrcmeta-Output_demo"></div>';
                  

                 $qrc_qr_image .=    '<div class="vcardQR_metxinfo">1).<em>'.esc_html__('Copy and paste this code anywhere in your site, it will works perfectly ','qr-code-composer').' </em> [qrc_vcard_bulk id ="'.$post_id.'" title = "'.(get_the_title($post_id)).'"]';

                $qrc_qr_image .= ".<p>2). ".esc_html__('if you are a developer then use below code for automatically display vCard QR in your desire location, but make sure it will  only woks this post ID/ post. if you use below code in base on single.php/singular.php then it will works automatically based on post id ','qr-code-composer')."</p><p><em><</em>?php echo qrc_vcard_dynamic();<em>?</em>></p></div>";
          
                     echo wp_kses_post($qrc_qr_image);

       }

       public function format_rows( $label, $input,$description,$vtitle,$vclass ) {
         return $vtitle .'<ul  class="qtc_vcard_wrap '.$vclass.'"><li class="qtc_vcard_cls"><strong>'.$label.'</strong>'.$description.'</li><li class="qtc_vcard_cls2">'.$input.'</li></ul>';
       }

     }

     if (class_exists('vCardQRMetabox')) {
       new vCardQRMetabox();
     };

     