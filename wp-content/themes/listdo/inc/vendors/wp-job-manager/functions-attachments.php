<?php
/**
 * bookmark
 *
 * @package    listdo
 * @author     ApusTheme <apusthemes@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  13/06/2016 ApusTheme
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class Listdo_Attachments {

	public static function init() {
        add_filter('preprocess_comment',        array(__CLASS__, 'checkAttachment'), 10, 1);
        //add_action('comment_form_top',          array(__CLASS__, 'displayBeforeForm'));
        add_action('comment_post',              array(__CLASS__, 'saveAttachment'));
        add_action('delete_comment',            array(__CLASS__, 'deleteAttachment'));
        //add_filter('comment_text',              array(__CLASS__, 'displayAttachment'), 10, 3);
        add_filter('comment_notification_text', array(__CLASS__, 'notificationText'), 10, 2);
	}

    private static function getMimeTypes()
    {
        return array(
            'image/jpeg',
            'image/jpg',
            'image/jp_',
            'application/jpg',
            'application/x-jpg',
            'image/pjpeg',
            'image/pipeg',
            'image/vnd.swiftview-jpeg',
            'image/x-xbitmap',
            'image/gif',
            'image/x-xbitmap',
            'image/gi_',
            'image/png',
            'application/png',
            'application/x-png'
        );
    }

    private static function getAllowedFileExtensions()
    {
        return array( 'jpg', 'jpeg', 'gif', 'png' );
    }

	public static function displayBeforeForm()
    {
    	global $post;
    	if ( !empty($post->post_type) && $post->post_type == 'job_listing' ) {
	        echo '</form><form action="'. esc_url(home_url('/') .'/wp-comments-post.php').'" method="POST" enctype="multipart/form-data" class="comment-form">';
	    }
    }

    public static function displayUploadField() {
        global $post;
        $images_upload = listdo_get_config('listing_review_enable_upload_image', true);
        $html = '';
        ob_start();
        if ( $images_upload ) {
            ?>
            <div class="form-group group-upload">
                <input class="hidden" id="field_attachments" name="attachments[]" type="file" multiple="multiple" accept="image/jpg,image/png,image/jpeg,image/gif" />
                <button type="button" id="field_attachments_cover">
                    <span class="title-upload"><?php esc_html_e('Drop images to upload', 'listdo'); ?></span>
                    <span class="break"><?php esc_html_e('or', 'listdo'); ?></span>
                    <div class="upload-file-btn">
                        <i class="flaticon-gallery"></i>
                        <span><?php esc_html_e('Gallery Images', 'listdo'); ?></span>
                    </div>
                </button>
                <div class="group-upload-preview clearfix"></div>
            </div>
            <?php
        }
        $html = ob_get_clean();
        return $html;
    }

    public static function saveAttachment($commentId)
    {
        $files = $_FILES['attachments'];
        if ( $files['size'] > 0 ) {
            $comment = get_comment( $commentId ); 
            $post_id = $comment->comment_post_ID ;
            foreach ($files['name'] as $key => $value) {                            
                if ($files['name'][$key]) {                     
                    $file = array(
                        'name' => $files['name'][$key],                     
                        'type' => $files['type'][$key],                         
                        'tmp_name' => $files['tmp_name'][$key],                         
                        'error' => $files['error'][$key],                       
                        'size' => $files['size'][$key]
                    );                  
                    $_FILES = array ("attachments" => $file);                   
                    $count = 0;                 
                    foreach ($_FILES as $file => $array) {
                        $attachId = self::insertAttachment($file, $post_id);
                        self::insertCommentMeta($commentId, $attachId);
                    }
                }
            }
        }
    }

    public static function insertCommentMeta($commentId, $attachId)
    {
    	$attachments = get_comment_meta($commentId, 'attachments', TRUE);
    	if ( !empty($attachments) && is_array($attachments) ) {
    		$attachments[$attachId] = $attachId;
    	} else {
    		$attachments = array( $attachId => $attachId );
    	}
    	update_comment_meta($commentId, 'attachments', $attachments);
    }

    public static function insertAttachment($fileHandler, $postId)
    {
    	if ( $_FILES[$fileHandler]['error'] !== UPLOAD_ERR_OK )
    		return false;

        require_once ABSPATH . "wp-admin" . '/includes/image.php';
        require_once ABSPATH . "wp-admin" . '/includes/file.php';
        require_once ABSPATH . "wp-admin" . '/includes/media.php';
        return media_handle_upload($fileHandler, $postId);
    }

    public static function deleteAttachment($commentId)
    {
        $attachments = get_comment_meta($commentId, 'attachments', TRUE);
        if ( !empty($attachments) && is_array($attachments) ) {
        	foreach ($attachments as $attachmentId) {
        		if (is_numeric($attachmentId) && !empty($attachmentId) ) {
		            wp_delete_attachment($attachmentId, TRUE);
		        }
        	}
        }
    }


    public static function notificationText($notify_message,  $comment_id)
    {
        $attachmentId = get_comment_meta($comment_id, 'attachmentId', TRUE);
        $attachments = get_comment_meta($comment_id, 'attachments', TRUE);
        if ( !empty($attachments) && is_array($attachments) ) {
        	$notify_message .= esc_html__('Attachment:', 'listdo');
        	foreach ($attachments as $attachmentId) {
        		$attachmentName = basename(get_attached_file($attachmentId));
        		$notify_message .= "\r\n" .  $attachmentName . "\r\n\r\n";
        	}
        }
        return $notify_message;
    }

    public static function displayAttachment()
    {
        ob_start();
        get_template_part( 'job_manager/single/parts/attachments-images' );
        $output = ob_get_clean();
        
        return $output;
    }

    public static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= (1024 * 1024 * 1024); //1073741824
                break;
            case 'm':
                $val *= (1024 * 1024); //1048576
                break;
            case 'k':
                $val *= 1024;
                break;
        }

        return $val;
    }

    
    public static function checkAttachment($data)
    {
        $allowed_html_array = array( 'strong' => array() );
        $max_size = self::return_bytes(ini_get('post_max_size'));
        $files = $_FILES['attachments'];
        $errors = array();
        if ( !empty($files['size']) ) {
            foreach ($files['name'] as $key => $value) {

                if ($files['size'][$key] > 0 && $files['error'][$key] == 0){

                    $fileInfo = pathinfo($files['name'][$key]);
                    $fileExtension = strtolower($fileInfo['extension']);

                    if(function_exists('finfo_file')){
                        $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $files['tmp_name'][$key]);
                    } elseif(function_exists('mime_content_type')) {
                        $fileType = mime_content_type($files['tmp_name'][$key]);
                    } else {
                        $fileType = $files['type'][$key];
                    }
                    
                    // Is: allowed mime type / file extension, and size? extension making lowercase, just to make sure
                    if (!in_array($fileType, self::getMimeTypes()) || !in_array(strtolower($fileExtension), self::getAllowedFileExtensions()) || $files['size'][$key] > $max_size) { // file size from admin
                        $errors[] = sprintf(wp_kses(__('<strong>ERROR:</strong> File <strong>%1$s</strong> you upload must be valid file type <strong>(%2$s)</strong>, and under %3$s!','listdo'), $allowed_html_array), $files['name'][$key], implode( ', ', self::getMimeTypes()), ini_get('post_max_size'));
                    }

                    // error 4 is actually empty file mate
                } elseif($files['error'][$key] == 1) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> The uploaded file exceeds the upload_max_filesize directive in php.ini.','listdo'), $allowed_html_array);
                } elseif($files['error'][$key] == 2) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.','listdo'), $allowed_html_array);
                } elseif($files['error'][$key] == 3) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> The uploaded file was only partially uploaded. Please try again later.','listdo'), $allowed_html_array);
                } elseif($files['error'][$key] == 6) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> Missing a temporary folder.','listdo'), $allowed_html_array);
                } elseif($files['error'][$key] == 7) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> Failed to write file to disk.','listdo'), $allowed_html_array);
                } elseif($files['error'][$key] == 7) {
                    $errors[] = wp_kses(__('<strong>ERROR:</strong> A PHP extension stopped the file upload.','listdo'), $allowed_html_array);
                }
                
            }
        }
        if ( sizeof($errors) > 0 ) {
            wp_die( implode(', ', $errors) );
        }
        return $data;
    }
}

Listdo_Attachments::init();