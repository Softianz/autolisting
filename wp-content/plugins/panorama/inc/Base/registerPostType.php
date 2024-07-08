<?php
namespace BPPIV\Base;

class registerPostType{

    public function register(){
        add_action( 'init', [$this, 'init']);
        add_filter( 'manage_bppiv-image-viewer_posts_columns', [$this, 'bppiv_columns_head_only_panorama'], 10 );
        add_action( 'manage_bppiv-image-viewer_posts_custom_column', [$this, 'bppiv_columns_content_only_panorama'], 10, 2);
        add_action( 'edit_form_after_title', [$this, 'bppiv_shortcode_area'] );
        add_filter( 'admin_footer_text', [$this, 'bppiv_admin_footer'] );
        add_filter( 'gettext', [$this, 'bppiv_change_publish_button'], 10, 2 );
        add_filter( 'post_updated_messages', [$this, 'bppiv_updated_messages'] );
        add_action( 'admin_head-post.php', [$this, 'bppiv_hide_publishing_actions'] );
        add_action( 'admin_head-post-new.php', [$this, 'bppiv_hide_publishing_actions'] );
        if ( is_admin() ) {
            add_filter('post_row_actions', [$this, 'bppiv_remove_row_actions'],  10, 2 );
        }
    }

    public function init(){
        $labels = array(
            'name'           => __( 'Panorama Viewer', 'panorama-viewer' ),
            'menu_name'      => __( 'Panorama Viewer', 'panorama-viewer' ),
            'name_admin_bar' => __( 'Panorama Viewer', 'panorama-viewer' ),
            'add_new'        => __( 'Add New', 'panorama-viewer' ),
            'add_new_item'   => __( 'Add New ', 'panorama-viewer' ),
            'new_item'       => __( 'New Panorama ', 'panorama-viewer' ),
            'edit_item'      => __( 'Edit Panorama ', 'panorama-viewer' ),
            'view_item'      => __( 'View Panorama ', 'panorama-viewer' ),
            'all_items'      => __( 'All Panoramas', 'panorama-viewer' ),
            'not_found'      => __( 'Sorry, we couldn\'t find the Feed you are looking for.' ),
        );

        $args = array(
            'labels'          => $labels,
            'description'     => __( 'Panorama Options.', 'panorama-viewer' ),
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'    => true,
            'menu_icon'       => 'dashicons-welcome-view-site',
            'query_var'       => true,
            'rewrite'         => array(
            'slug' => 'panorama-viewer',
        ),
            'capability_type' => 'post',
            'has_archive'     => false,
            'hierarchical'    => false,
            'menu_position'   => 20,
            'supports'        => array( 'title' ),
        );
        \register_post_type( 'bppiv-image-viewer', $args );
    }

    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function bppiv_columns_head_only_panorama( $defaults ){
        $defaults['directors_name'] = 'ShortCode';
        return $defaults;
    }
    
    function bppiv_columns_content_only_panorama( $column_name, $post_ID ){
        if ( $column_name == 'directors_name' ) {
            // show content of 'directors_name' column
            echo  '<input onClick="this.select();" value="[panorama id=' . esc_attr($post_ID) . ']" >' ;
        }
    }

    function bppiv_shortcode_area(){
        global  $post ;
        if ( $post->post_type == 'bppiv-image-viewer' ) {
            ?>	
            <div class="shortcode_gen">
                <label for="bppiv_shortcode"><?php 
                esc_html_e( 'Copy this shortcode and paste it into your post, page, or text widget content', 'panorama-viewer' );
                ?>:</label>

                <span>
                    <input type="text" id="bppiv_shortcode" onfocus="this.select();" readonly="readonly"  value="[panorama id=<?php 
                echo  $post->ID ;
                ?>]" /> 		
                </span>

            </div>
	        <?php 
        }
    }

       /*-------------------------------------------------------------------------------*/
    /* Footer Review Request .
    /*-------------------------------------------------------------------------------*/
    function bppiv_admin_footer( $text )       {
        
        if ( 'bppiv-image-viewer' == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/panorama/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong> Panorama Viewer </strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'panorama-viewer' ), $url );
        }
        
        return $text;
    }

       /*-------------------------------------------------------------------------------*/
    /* Change publish button to save.
       /*-------------------------------------------------------------------------------*/
    function bppiv_change_publish_button( $translation, $text )    {
        if ( 'bppiv-image-viewer' == get_post_type() ) {
            if ( $text == 'Publish' ) {
                return 'Save';
            }
        }
        return $translation;
    }

    /*-------------------------------------------------------------------------------*/
    // Remove post update massage and link
    /*-------------------------------------------------------------------------------*/
    function bppiv_updated_messages( $messages ){
        $messages['bppiv-image-viewer'][1] = __( 'Shortcode updated ', 'panorama-viewer' );
        return $messages;
    }

    // HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
    function bppiv_hide_publishing_actions(){
        $my_post_type = 'bppiv-image-viewer';
        global  $post ;
        if ( $post->post_type == $my_post_type ) {
            echo  '
            <style type="text/css">
                #misc-publishing-actions,
                #minor-publishing-actions{
                    display:none;
                }
            </style>
        ' ;
        }
    }

    // Hide & Disabled View, Quick Edit and Preview Button
    function bppiv_remove_row_actions( $idtions ) {
        global  $post ;
        
        if ( $post->post_type == 'bppiv-image-viewer' ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        
        return $idtions;
    }
    
}