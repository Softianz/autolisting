<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Metabox of the PAGE
// Set a unique slug-like ID
//
$prefix = '_bppivimages_';

//
// Create a metabox
//

CSF::createMetabox( $prefix, array(
  'title'        => 'Panorama Settings',
  'post_type'    => 'bppiv-image-viewer',
  'show_restore' => true,
  'footer_credit'    => ' ',
) );

//
// section: Panorama
//
CSF::createSection( $prefix, array(
  'fields' => array(

    // panorama controls
    array(
      'id'       => 'bppiv_type',
      'type'     => 'button_set',
      'title'    => __('Panorama Type.', 'panorama-viewer'),
      'subtitle' => __('Choose Panorama Type', 'panorama-viewer'),
      'desc'     => __('Select Panorama, Default- Image.', 'panorama-viewer'),
      'multiple' => false,
      'options'  => array(
        'image'   => 'Image',
        'image360'=> 'Image 360°',
        'video'   => 'Video',
        'gallery'  => 'Gallery',
        'tour360'  => 'Tour 360°',
        'gstreet'  => 'Google Street View',
      ),
      'default'  => 'image'
    ),

    array(
      'id'           => 'bppiv_image_src',
      'type'         => 'media',
      'library'      => 'image',
      'button_title' => __('Upload Image', 'panorama-viewer'),
      'title'        => __('Image Source.', 'panorama-viewer'),
      'desc'         => __('To create an image panorama, Panoramic image is Recommended is Recommended.', 'panorama-viewer'),
      'dependency'   => array( 'bppiv_type', '==', 'image' ),
    ),
    array(
      'id'           => 'image_src_360',
      'type'         => 'upload',
      'library'      => 'image',
      'button_title' => __('Upload Image', 'panorama-viewer'),
      'title'        => __('360° Image Source.', 'panorama-viewer'),
      'desc'         => __('To create an image panorama, Panoramic image is Recommended. You can also use external Panoramic Image link here.', 'panorama-viewer'),
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),

    ),
    // video source
    array(
      'id'           => 'bppiv_video_src',
      'type'         => 'media',
      'library'      => 'video',
      'button_title' => __('Upload Video', 'panorama-viewer'),
      'title'        => __('Video Source.', 'panorama-viewer'),
      'desc'         => __('Upload Panoramic Video', 'panorama-viewer'),
      'dependency'   => array( 'bppiv_type', '==', 'video' ),
    ),

    // Gallery
    array(
      'id'           => 'bppiv_pan_gallery',
      'type'         => 'group',
      'library'      => 'video',
      'button_title' => __('Add New Gallery Item', 'panorama-viewer'),
      'title'        => __('Panorama Gallery.', 'panorama-viewer'),
      'subtitle'     => __('Show multiple items according to your need .', 'panorama-viewer'),
      'desc'         => __('Create Panorama Gallery, Supports panoramic image and Video Both', 'panorama-viewer'),
      'fields'    => array(
        array(
          'id'    => 'panoramic_img',
          'type'  => 'media',
          'title' => 'Panoramic Images',
          'library' => 'image',
        ),
        array(
          'id'    => 'gal_type_cheek',
          'type'  => 'checkbox',
          'title' => 'Set Video',
          'default' => false,
          'desc'    => __('If you want to Set Video for this item please checked it', 'panorama-viewer'),
        ),
        array(
          'id'    => 'gal_type_video',
          'type'  => 'media',
          'title' => 'Gallery Video',
          'desc'  => __('Upload Panoramic Video', 'panorama-viewer'),
          'library' => 'video',
          'dependency'   => array( 'gal_type_cheek', '==', '1' ),
        ),
      ),
      'class'     => 'panorama-readonly',  
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),
    array(
      'id'           => 'bppiv_gallery_limit',
      'type'         => 'spinner',
      'title'        => __('Gallery Limits', 'panorama-viewer'),
      'subtitle'     => __('Number of items to show in primary gallery', 'panorama-viewer'),
      'desc'         => __('How much item do you want to show ?', 'panorama-viewer'),
      'default'  => 6,
      'class'     => 'panorama-readonly',  
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),

    // Load More Button
    array(
      'id'         => 'loadMore_btn_text',
      'type'       => 'text',
      'title'      => __('LoadMore Button Text', 'bgallery'),
      'subtitle'   => __('You can use Custom Text in Button', 'bgallery'),
      'desc'       => __('Input LoadMore Button Text', 'bgallery'),
      'default'    => 'Load More',
      'class'     => 'panorama-readonly', 
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),
    array(
      'id'         => 'loadMore_text_color',
      'type'       => 'color',
      'title'      => __('LoadMore Text Color', 'bgallery'),
      'subtitle'   => __('You can use Custom Color', 'bgallery'),
      'desc'       => __('Choose LoadMore Button Text Color', 'bgallery'),
      'default'    => '#fff',
      'class'     => 'panorama-readonly', 
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),
    array(
      'id'         => 'loadMore_btn_bg',
      'type'       => 'color',
      'title'      => __('LoadMore Button Background', 'bgallery'),
      'desc'       => __('Choose LoadMore Button Background Color', 'bgallery'),
      'default'    => '#000',
      'class'     => 'panorama-readonly', 
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),
    array(
      'id'         => 'loadMore_hover_bg',
      'type'       => 'color',
      'title'      => __('LoadMore Hover Background', 'bgallery'),
      'desc'       => __('Choose LoadMore Hover Background Color', 'bgallery'),
      'default'    => '#222',
      'class'     => 'panorama-readonly', 
      'dependency'   => array( 'bppiv_type', '==', 'gallery' ),
    ),
    // Google Street View 
    array(
      'id'           => 'bppiv_pano_id',
      'type'         => 'text',
      'title'        => __('Panorama ID', 'panorama-viewer'),
      'desc'         => __('Input here Google Street View Panorama Id <a href="https://e4youth.org/blog/2019/02/05/snapping-360-images-from-google-street-view/" target="_blank">Click here for Panorama ID Details</a>', 'panorama-viewer'),
      'placeholder'  => 'Paste here panorama id',
      'default'      => 'JmSoPsBPhqWvaBmOqfFzgA',
      'class'     => 'panorama-readonly', 
      'dependency'   => array( 'bppiv_type', '==', 'gstreet' ),
    ),
    // Image and Videos Fields
    array(
      'id'           => 'bppiv_image_width',
      'type'         => 'dimensions',
      'title'        => __('Width', 'panorama-viewer'),
      'desc'         => __('Panorama Viewer Width', 'panorama-viewer'),
      'default'  => array(
        'width'  => '100',
        'unit'   => '%',
      ),
      'height'   => false,
      'dependency'   => array( 'bppiv_type', '!=', 'gallery' ),
    ),
    array(
      'id'           => 'bppiv_image_height',
      'type'         => 'dimensions',
      'title'        => __('Height', 'panorama-viewer'),
      'desc'         => __('Panorama Viewer height', 'panorama-viewer'),
      'units'        => ['px', 'em', 'pt'],
      'default'  => array(
        'height' => '320',
        'unit'   => 'px',
      ),
      'width'   => false,
      'dependency'   => array( 'bppiv_type', '!=', 'gallery' ),
    ),
    array(
      'id'       => 'bppiv_auto_rotate',
      'type'     => 'switcher',
      'title'    => __('Auto Rotate ?', 'panorama-viewer'),
      'desc'     => __('Enable or Disable Auto Rotate', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', 'any', 'image,image360'  ),
    ),
    array(
      'id'       => 'bppiv_speed',
      'type'     => 'spinner',
      'title'    => __('Auto Rotate Speed', 'panorama-viewer'),
      'subtitle' => __('Choose Auto Rotate Speed', 'panorama-viewer'),
      'desc'     => __('Auto rotate speed as in degree per second. Positive is counter-clockwise and negative is clockwise.', 'panorama-viewer'), 
      'default'  => 2.0,
      'dependency' => array( 'bppiv_type|bppiv_auto_rotate', 'any|==', 'image,image360|true' ),
    ),
    array(
      'id'       => 'control_show_hide',
      'type'     => 'switcher',
      'title'    => __('Hide Default Control ?', 'panorama-viewer'),
      'subtitle' => __('Show / Hide Switch for Default Control.', 'panorama-viewer'),
      'desc'     => __('Show or Hide Control', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => false,
      'dependency'   => array( 'bppiv_type', 'any', 'image,image360' ),
    ),
    array(
      'id'       => 'initial_view',
      'type'     => 'switcher',
      'title'    => __('Initial View', 'panorama-viewer'),
      'subtitle' => __('Choose Custom Angle of View for Initial Viewing ', 'panorama-viewer'),
      'desc'     => __('Enable or Disable Initial Viewe. Default "OFF"', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => false,
      'class'     => 'panorama-readonly',  
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    array(
      'id'    => 'initial_view_property',
      'type'  => 'spacing',
      'title' => 'Initial Values',
      'subtitle'=> __('Set The Custom values for Initial View. Default Initial Values are ("X=2.3 Y=-360.4 Z=120")', 'model-viewer'),
      'desc'    => __('Set Your Desire Values. (X= Horizontal Position, Y= Vertical Position, Z= Zoom Level/Position) ', 'model-viewer'),
      'class'     => 'panorama-readonly',  
      'default'  => array(
        'top'    => 2.3,
        'right'  => -360.4,
        'bottom' => 120,
      ),
      'left'   => false,
      'show_units' => false,
      'top_icon'    => 'pitch',
      'right_icon'  => 'yaw',
      'bottom_icon' => 'hfov',
      'dependency' => array( 'initial_view', '==', '1' ),
    ),
    array(
      'id'       => 'custom_control',
      'type'     => 'switcher',
      'title'    => __('Custom Control', 'panorama-viewer'),
      'subtitle' => __('Custom Control will replace default control bar', 'panorama-viewer'),
      'desc'     => __('Show or Hide Custom Control. Default "NO"', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => false,
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    array(
      'id'       => 'bppiv_auto_load',
      'type'     => 'switcher',
      'title'    => __('Auto Load', 'panorama-viewer'),
      'desc'     => __('Enable or Disable Autoload', 'panorama-viewer'),
      'subtitle'     => __('Image will be automatically load without click', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    array(
      'id'       => 'draggable_360',
      'type'     => 'switcher',
      'title'    => __('Draggable ', 'panorama-viewer'),
      'desc'     => __('Enable or Disable mouse and touch dragging', 'panorama-viewer'),
      'subtitle'     => __('Image will be Draggable with this feature', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    array(
      'id'       => 'compass_360',
      'type'     => 'switcher',
      'title'    => __('Compass ', 'panorama-viewer'),
      'desc'     => __('Show or Hide Compass.', 'panorama-viewer'),
      'subtitle' => __('Enable or Disable Compass. Default "No"', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => false,
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    // Video Settings
    array(
      'id'       => 'bppiv_auto_play',
      'type'     => 'switcher',
      'title'    => __('Auto Play ?', 'panorama-viewer'),
      'desc'     => __('Enable or Disable Auto Play', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'video' ),
    ),
    array(
      'id'       => 'bppiv_video_mute',
      'type'     => 'switcher',
      'title'    => __('Video Mute ?', 'panorama-viewer'),
      'subtitle' => __('Enable or Disable Video Mute', 'panorama-viewer'),
      'desc'     => __('Specify if the video should auto play', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'video' ),
    ),
    array(
      'id'       => 'bppiv_video_loop',
      'type'     => 'switcher',
      'title'    => __('Video Loop ?', 'panorama-viewer'),
      'desc'     => __('Enable or Disable Video Loop', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'video' ),
    ),
    array(
      'id'       => 'control_show_hide_video',
      'type'     => 'switcher',
      'title'    => __('Control Bar', 'panorama-viewer'),
      'desc'     => __('Choose "No" to Hide Control. Default "Yes"', 'panorama-viewer'),
      'desc'     => __('Show or Hide Control', 'panorama-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => false,
      'dependency'   => array( 'bppiv_type', '==', 'video' ),
    ),
    array(
      'id'       => 'title_author',
      'type'     => 'switcher',
      'title'    => __('Title & Author', 'panorama-viewer'),
      'subtitle' => __('Display Title & Author Text. Default "No"', 'panorama-viewer'),
      'desc'     => __('Show or Hide Title, Author Name', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
      'dependency'   => array( 'bppiv_type', '==', 'image360' ),
    ),
    array(
      'id'       => 'title_360',
      'type'     => 'text',
      'title'    => __('Title', 'panorama-viewer'),
      'subtitle' => __('Display Title Text.', 'panorama-viewer'),
      'desc'     => __('Input Title Text', 'panorama-viewer'),
      'class'     => 'panorama-readonly',  
      'placeholder' => "360° Image",
      'default' => "360° Panorama",
      'dependency'   => array( 'bppiv_type|title_author', '==|==', 'image360|1' ),
    ),
    array(
      'id'       => 'author_360',
      'type'     => 'text',
      'title'    => __('Author', 'panorama-viewer'),
      'subtitle' => __('Display Author Name."', 'panorama-viewer'),
      'desc'     => __('Input Author Name', 'panorama-viewer'),
      'placeholder' => "bPlugins",
      'default' => '<a href="https://bplugins.com/">bPlugins</a>',
      'class'     => 'panorama-readonly',  
      'dependency'   => array( 'bppiv_type|title_author', '==|==', 'image360|1' ),
    ),


// Tour Fields
  array(
    'id'        => 'tour_360',
    'type'      => 'group',
    'title'     => 'Tour 360°',
    'subtitle'  => 'Multiple panoramas can be joined together into a virtual tour using this tour feature.',
    'class'     => 'panorama-readonly', 
    'fields'    => array(
      array(
        'id'    => 'tour_id',
        'type'  => 'text',
        'title'    => __('Tour Name', 'panorama-viewer'),
        'subtitle'    => __('Use Tour Unique Name. For example: house, house123.', 'panorama-viewer'),
        'desc'    => __('Input Your Unique name here. Don\'t use space !!', 'panorama-viewer'),
        'default' => 'house'
      ),
      array(
        'id'    => 'tour_img',
        'type'  => 'upload',
        'title'  => __('Tour Image', 'panorama-viewer'),
        'subtitle'=>__('Use Tour Image', 'panorama-viewer'),
        'desc'    =>__('Upload Your Tour Image or Use External Image Link', 'panorama-viewer'),
      ),
      // Title Author
      array(
        'id'       => 'tourTitleAuthor',
        'type'     => 'switcher',
        'title'    => __('Title & Author ', 'panorama-viewer'),
        'subtitle' => __('Show or Hide Title and Author.', 'panorama-viewer'),
        'desc'     => __('Choose Show or Hide. Default is "Show"', 'panorama-viewer'),
        'text_on'   => 'Show',
        'text_off'  => 'Hide',
        'text_width' => 70,
        'default'  => false,
      ),
      array(
        'id'    => 'title',
        'type'  => 'text',
        'title'  => __('Title', 'panorama-viewer'),
        'subtitle'=> __('Use Tour Title', 'panorama-viewer'),
        'desc'    => __('Input Your Tour Title here.', 'panorama-viewer'),
        'default' => 'Spring House or Dairy',
        'dependency'   => array( 'tourTitleAuthor', '==', '1' )
      ),
      array(
        'id'    => 'author',
        'type'  => 'text',
        'title'  => __('Author', 'panorama-viewer'),
        'subtitle'=> __('Use Tour Author/Location Name', 'panorama-viewer'),
        'desc'    => __('Input Tour Author/Location Name here.', 'panorama-viewer'),
        'default' => 'bPlugins',
        'dependency'   => array( 'tourTitleAuthor', '==', '1' )
      ),

      // hotspot
      array(
        'id'       => 'tour_hotSpot',
        'type'     => 'switcher',
        'title'    => __('HotSpot ', 'panorama-viewer'),
        'subtitle' => __('Choose HotSpot Option. OFF or ON ', 'panorama-viewer'),
        'desc'     => __('OFF or ON HotSpot. Default "ON"', 'panorama-viewer'),
        'text_on'   => 'ON',
        'text_off'  => 'OFF',
        'default'  => true,
      ),
      array(
        'id'    => 'hotSpot_txt',
        'type'  => 'text',
        'title'  => __('HotSpot Text', 'panorama-viewer'),
        'subtitle'=>__('Use HotSpot Text That will display during mouse hover', 'panorama-viewer'),
        'desc'    =>__('Input Your HotSpot Text Here', 'panorama-viewer'),
        'default' => 'Spring House',
        'dependency'   => array( 'tour_hotSpot', '==', '1' ),
      ),
      array(
        'id'    => 'target_id',
        'type'  => 'text',
        'title'  => __('Target ID', 'panorama-viewer'),
        'subtitle'=>__('Use Targeted ID That will create HotSpot relation between two Scene / Tour Image', 'panorama-viewer'),
        'desc'    =>__('Input Targeted Tour name here. Tour name will work like ID', 'panorama-viewer'),
        'dependency'   => array( 'tour_hotSpot', '==', '1' )
      ),
      array(
        'id'    => 'default_data',
        'type'  => 'switcher',
        'title'  => __('Default', 'panorama-viewer'),
        'subtitle'=>__('Set as default to display Primary Scene', 'panorama-viewer'),
        'desc'    =>__('Choose Yes to set as default Scene. If you don\'t Choose automatically will Display first Item', 'panorama-viewer'),
        'text_on'   => 'Yes',
        'text_off'  => 'No',
        'default'  => false,
      ),
    ),
    'dependency'   => array( 'bppiv_type', '==', 'tour360' ),
  ),


    
) // End fields


) );


function bppiv_exclude_fields_before_save( $data ) {

$exclude = array(
  'bppiv_pan_gallery',
  'bppiv_gallery_limit',
  'loadMore_btn_text',
  'loadMore_text_color',
  'loadMore_btn_bg',
  'loadMore_hover_bg',
  'bppiv_pano_id',

);

foreach ( $exclude as $id ) {
unset( $data[$id] );
}

return $data;

}

add_filter( 'csf_sc__save', 'bppiv_exclude_fields_before_save', 10, 1 );