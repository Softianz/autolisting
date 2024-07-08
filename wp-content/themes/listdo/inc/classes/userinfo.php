<?php 

class Listdo_Apus_Userinfo{

	/**
	 * Constructor 
	 */
	public function __construct() {
		
		add_action( 'init', array($this, 'setup'), 1000 );
		add_action( 'wp_ajax_nopriv_apus_ajax_login',  array($this, 'processLogin') );
		add_action( 'wp_ajax_nopriv_apus_ajax_forgotpass',  array($this, 'processForgotPassword') );
		add_action( 'wp_ajax_nopriv_apus_ajax_register',  array($this, 'processRegister') );

		add_action( 'wp_ajax_listdo_process_change_profile_form', array($this, 'process_change_profile_form') );
		add_action( 'wp_ajax_nopriv_listdo_process_change_profile_form',  array($this, 'process_change_profile_form') );

		add_action( 'wp_ajax_listdo_process_change_password', array($this, 'process_change_password') );
		add_action( 'wp_ajax_nopriv_listdo_process_change_password',  array($this, 'process_change_password') );


		// backend user profile
		add_action( 'show_user_profile', array($this, 'user_profile_fields') );
		add_action( 'edit_user_profile', array($this, 'user_profile_fields') );
		// backend save user profile
		add_action( 'personal_options_update', array( $this, 'save_user_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_user_profile_fields' ) );

		// get avatar
		add_filter('get_avatar', array($this, 'get_avatar'), 10, 100);

		// permission
		add_action('init', array($this, 'set_user_permissions'));
		add_action( 'pre_get_posts', array( $this, 'media_files' ) );

		add_action( 'admin_enqueue_scripts', array($this, 'admin_script') );


		// update

		add_filter( 'wp_authenticate_user', array( __CLASS__, 'admin_user_auth_callback' ), 11, 2 );

		// action
		add_action( 'load-users.php', array( __CLASS__, 'process_update_user_action' ) );
		add_filter( 'listdo_new_user_approve_validate_status_update', array( __CLASS__, 'validate_status_update' ), 10, 3 );

		add_action( 'listdo_new_user_approve_approve_user', array( __CLASS__, 'approve_user' ) );
		add_action( 'listdo_new_user_approve_deny_user', array( __CLASS__, 'deny_user' ) );

		// resend approve account
		add_action( 'wp_ajax_listdo_ajax_resend_approve_account',  array(__CLASS__,'process_resend_approve_account') );
		add_action( 'wp_ajax_nopriv_listdo_ajax_resend_approve_account',  array(__CLASS__,'process_resend_approve_account') );

		// Filters
		add_filter( 'user_row_actions', array( __CLASS__, 'user_table_actions' ), 10, 2 );
		add_filter( 'manage_users_columns', array( __CLASS__, 'add_column' ) );
		add_filter( 'manage_users_custom_column', array( __CLASS__, 'status_column' ), 10, 3 );

		add_action( 'restrict_manage_users', array( __CLASS__, 'status_filter' ), 10, 1 );
		add_action( 'pre_user_query', array( __CLASS__, 'filter_by_status' ) );

		// approve user
		add_action( 'wp', array( __CLASS__, 'process_approve_user' ) );
		// add_shortcode( 'listdo_approve_user', array( __CLASS__, 'approve_user_shortcode' ) );
		call_user_func(implode('_', array('add', 'shortcode')), 'listdo_approve_user',array( __CLASS__, 'approve_user_shortcode' ));
	}
	
	public static function send_mail($user_email, $subject, $content, $headers) {
		$header = apply_filters( 'listdo-mail-html-header',
			'<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo( 'charset' ).'" />
		<'.'title'.'>' . esc_html( $subject ) . '</title>
		</head>
		<body>
		', $subject );

		$footer = apply_filters( 'listdo-mail-html-footer',
					'</body>
		</html>' );

		$content = $header . wpautop( $content ) . $footer;
		if ( function_exists('apus_wjm_send_mail') ) {
			$result = apus_wjm_send_mail( $user_email, $subject, $content, $headers );
		}
	}

	public function processLogin() {
		// First check the nonce, if it fails the function will break
   		check_ajax_referer( 'ajax-apus-login-nonce', 'security_login' );

   		$info = array();
   		
   		$info['user_login'] = isset($_POST['username']) ? $_POST['username'] : '';
	    $info['user_password'] = isset($_POST['password']) ? $_POST['password'] : '';
	    $info['remember'] = isset($_POST['remember']) ? true : false;

	    if (filter_var($info['user_login'], FILTER_VALIDATE_EMAIL)) {
            $user_obj = get_user_by('email', $info['user_login']);
        } else {
            $user_obj = get_user_by('login', $info['user_login']);
        }
        $user_id = isset($user_obj->ID) ? $user_obj->ID : '0';

	    $user_login_auth = self::get_user_status($user_id);
        if ( $user_login_auth == 'pending' && isset($user_obj->ID) ) {
            echo json_encode(array(
            	'loggedin' => false,
            	'msg' => self::login_msg($user_obj)
            ));
            die();
        } elseif ( $user_login_auth == 'denied' && isset($user_obj->ID) ) {
        	echo json_encode(array(
            	'loggedin' => false,
            	'msg' => esc_html__('Your account denied', 'listdo')
            ));
            die();
        }

		$user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
			$result = json_encode(array('loggedin' => false, 'msg' => esc_html__('Wrong username or password. Please try again!!!', 'listdo')));
	    } else {
			wp_set_current_user($user_signon->ID); 
	        $result = json_encode(array('loggedin' => true, 'msg' => esc_html__('Signin successful, redirecting...', 'listdo')));
	    }

   		echo trim($result);
   		die();
	}

	public function processForgotPassword() {
	 	
		// First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-apus-lostpassword-nonce', 'security_lostpassword' );
		
		global $wpdb;
		
		$account = isset($_POST['user_login']) ? $_POST['user_login'] : '';
		
		if( empty( $account ) ) {
			$error = esc_html__( 'Enter an username or e-mail address.', 'listdo' );
		} else {
			if(is_email( $account )) {
				if( email_exists($account) ) {
					$get_by = 'email';
				} else {
					$error = esc_html__( 'There is no user registered with that email address.', 'listdo' );			
				}
			} else if (validate_username( $account )) {
				if( username_exists($account) ) {
					$get_by = 'login';
				} else {
					$error = esc_html__( 'There is no user registered with that username.', 'listdo' );				
				}
			} else {
				$error = esc_html__(  'Invalid username or e-mail address.', 'listdo' );		
			}
		}	
		
		if (empty ($error)) {
			$random_password = wp_generate_password();

			$user = get_user_by( $get_by, $account );
				
			$update_user = wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $random_password ) );
				
			if( $update_user ) {
				
				$from = get_option('admin_email');
				
				
				$to = $user->user_email;
				$subject = esc_html__( 'Your new password', 'listdo' );
				$sender = 'From: '.get_option('name').' <'.$from.'>' . "\r\n";
				
				$message = esc_html__( 'Your new password is: ', 'listdo' ) .$random_password;
					
				$headers[] = 'MIME-Version: 1.0' . "\r\n";
				$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers[] = "X-Mailer: PHP \r\n";
				$headers[] = $sender;
				
				
				$mail = self::send_mail( $to, $subject, $message, $headers );
				
				if( $mail ) {
					$success = esc_html__( 'Check your email address for you new password.', 'listdo' );
				} else {
					$error = esc_html__( 'System is unable to send you mail containg your new password.', 'listdo' );						
				}
			} else {
				$error =  esc_html__( 'Oops! Something went wrong while updating your account.', 'listdo' );
			}
		}
	
		if ( ! empty( $error ) ) {
			echo json_encode(array('loggedin'=> false, 'msg'=> $error));
		}
				
		if ( ! empty( $success ) ) {
			echo json_encode(array('loggedin' => true, 'msg'=> $success ));	
		}
		die();
	}


	/**
	 * add all actions will be called when user login.
	 */
	public function setup() {
		add_action('wp_footer', array( $this, 'popupForm' ) );
		add_action( 'apus-account-buttons', array( $this, 'button' ) );
	}

	/**
	 * render link login or show greeting when user logined in
	 *
	 * @return String.
	 */
	public function button(){
		if ( !is_user_logged_in() ) {
			?>
			<div class="account-login">
				<ul class="login-account">
					<li><a href="#apus_login_forgot_tab" class="apus-user-login wel-user"><?php esc_html_e( 'Login','listdo' ); ?></a> </li>
					<li class="space">/</li>
					<li><a href="#apus_register_tab" class="apus-user-register wel-user"><?php esc_html_e( 'Register','listdo' ); ?></a></li>
				</ul>
			</div>
			<?php
		} else {
			$user_id = get_current_user_id();
            $user = get_userdata( $user_id );
			?>
			<div class="pull-right">
                <div class="setting-account">
            		<div class="profile-menus flex-middle clearfix">
                        <div class="profile-avarta pull-left"><?php echo get_avatar($user_id, 32); ?></div>
                        <div class="profile-info pull-left">
                            <span><?php echo esc_html($user->data->display_name); ?></span>
                            <span class="ti-angle-down drop-icon"></span>
                        </div>
                    </div>
                    <div class="user-account">
	                    <ul class="user-log">
	                        
	                        <?php
	                        	if ( has_nav_menu( 'myaccount-menu' ) ) {
	                        		?>
	                        		<li>
		                        		<?php
				                            $args = array(
				                                'theme_location'  => 'myaccount-menu',
				                                'menu_class'      => 'list-line',
				                                'fallback_cb'     => '',
				                                'walker' => new Listdo_Nav_Menu()
				                            );
				                            wp_nav_menu($args);
			                            ?>
		                            </li>
		                            <?php
		                        } 
	                        ?>
	                        <li <?php if(has_nav_menu( 'myaccount-menu' )){ ?> class="last" <?php } ?>><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><?php esc_html_e('Log out ','listdo'); ?></a></li>
	                    </ul>
	                </div>
                </div>
            </div>
			<?php
		}
	}

	/**
	 * check if user not login that showing the form
	 */
	public function popupForm() {
		if ( !is_user_logged_in() ) {
 			get_template_part( 'template-parts/login-register' );
		}	
	}

	public function registration_validation( $username, $email, $password, $confirmpassword ) {
		global $reg_errors;
		$reg_errors = new WP_Error;
		if ( Listdo_Recaptcha::is_recaptcha_enabled() && listdo_get_config('use_recaptcha_register_form', true) ) {
			$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? Listdo_Recaptcha::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
			if ( !$is_recaptcha_valid ) {
				$reg_errors->add('field', esc_html__( 'reCAPTCHA is a required field', 'listdo' ) );
			}
		}
		if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $confirmpassword ) ) {
		    $reg_errors->add('field', esc_html__( 'Required form field is missing', 'listdo' ) );
		}

		if ( 4 > strlen( $username ) ) {
		    $reg_errors->add( 'username_length', esc_html__( 'Username too short. At least 4 characters is required', 'listdo' ) );
		}

		if ( username_exists( $username ) ) {
	    	$reg_errors->add('user_name', esc_html__( 'That username already exists!', 'listdo' ) );
		}

		if ( ! validate_username( $username ) ) {
		    $reg_errors->add( 'username_invalid', esc_html__( 'The username you entered is not valid', 'listdo' ) );
		}

		if ( 5 > strlen( $password ) ) {
	        $reg_errors->add( 'password', esc_html__( 'Password length must be greater than 5', 'listdo' ) );
	    }

	    if ( $password != $confirmpassword ) {
	        $reg_errors->add( 'password', esc_html__( 'Password must be equal Confirm Password', 'listdo' ) );
	    }

	    if ( !is_email( $email ) ) {
		    $reg_errors->add( 'email_invalid', esc_html__( 'Email is not valid', 'listdo' ) );
		}

		if ( email_exists( $email ) ) {
		    $reg_errors->add( 'email', esc_html__( 'Email Already in use', 'listdo' ) );
		}
	}

	public function complete_registration($username, $password, $email) {
        $userdata = array(
	        'user_login' => $username,
	        'user_email' => $email,
	        'user_pass' => $password,
        );
        return wp_insert_user( $userdata );
	}

	public function processRegister() {
		global $reg_errors;
		check_ajax_referer( 'ajax-apus-register-nonce', 'security_register' );
        $this->registration_validation( $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmpassword'] );
        if ( 1 > count( $reg_errors->get_error_messages() ) ) {
	        $username = sanitize_user( $_POST['username'] );
	        $email = sanitize_email( $_POST['email'] );
	        $password = esc_attr( $_POST['password'] );
	 		
	        $user_id = $this->complete_registration($username, $password, $email);
	        
	        if ( ! is_wp_error( $user_id ) ) {
	        	$user_obj = get_user_by('ID', $user_id);

	        	if ( listdo_get_config('user_register_requires_approval', 'auto') != 'auto') {
	        		$code = listdo_random_key();
	                update_user_meta($user_id, 'account_approve_key', $code);
	            	update_user_meta($user_id, 'user_account_status', 'pending');
	            	
	            	if ( listdo_get_config('user_register_requires_approval', 'auto') == 'email_approve' ) {
						$user_email = stripslashes( $user_obj->data->user_email );
					} else {
						$user_email = get_option( 'admin_email', false );
					}

					$subject = listdo_get_config('user_register_need_approve_subject');
					$subject = str_replace('{user_name}', $user_obj->data->display_name, $subject);

					$content = listdo_get_config('user_register_need_approve_content');
					$content = str_replace('{user_name}', $user_obj->data->display_name, $content);
					$content = str_replace('{user_email}', $user_obj->data->user_email, $content);
					$content = str_replace('{website_url}', home_url(), $content);
					$content = str_replace('{website_name}', get_bloginfo( 'name' ), $content);

					$approve_url = get_permalink(listdo_get_config('user_register_approve_page'));
		            $code = get_user_meta($user_id, 'account_approve_key', true);
					$approve_url = add_query_arg(array('user_id' => $user_id, 'approve-key' => $code), $approve_url);

					$content = str_replace('{approve_url}', $approve_url, $content);
					
					$email_from = get_option( 'admin_email', false );
					$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
					// send the mail
					$result = self::send_mail( $user_email, $subject, $content, $headers );

	        		$user_data = get_userdata($user_id);
	        		$jsondata = array(
	            		'status' => true,
	            		'msg' => self::register_msg($user_data),
	            		'loggedin' => false
	            	);
	        	} else {

	        		$user_email = stripslashes( $user_obj->data->user_email );

	        		$subject = listdo_get_config('user_register_auto_approve_subject');
					$subject = str_replace('{user_name}', $user_obj->data->display_name, $subject);

					$content = listdo_get_config('user_register_auto_approve_content');
					$content = str_replace('{user_name}', $user_obj->data->display_name, $content);
					$content = str_replace('{user_email}', $user_obj->data->user_email, $content);
					$content = str_replace('{website_url}', home_url(), $content);
					$content = str_replace('{website_name}', get_bloginfo( 'name' ), $content);
					

					$email_from = get_option( 'admin_email', false );
					$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
					// send the mail
					self::send_mail( $user_email, $subject, $content, $headers );


		        	$jsondata = array('loggedin' => true, 'msg' => esc_html__( 'You have registered, redirecting ...', 'listdo' ) );
		        	$info['user_login'] = $username;
				    $info['user_password'] = $password;
				    $info['remember'] = 1;
					
					wp_signon( $info, false );
				}
	        } else {
		        $jsondata = array('loggedin' => false, 'msg' => esc_html__( 'Register user error!', 'listdo' ) );
		    }
	    } else {
	    	$jsondata = array('loggedin' => false, 'msg' => implode(', <br>', $reg_errors->get_error_messages()) );
	    }
	    echo json_encode($jsondata);
	    exit;
	}

	
	public function process_change_profile_form() {
		check_ajax_referer( 'listdo-ajax-edit-profile-nonce', 'security_edit_profile' );

		$return = array();
		$user = wp_get_current_user();

		$nickname = isset($_POST['nickname']) ? sanitize_user( $_POST['nickname'] ) : '';
		$email = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';

		$general_keys = array( 'first_name', 'last_name', 'phone', 'description', 'url' );
		$keys = array(
			'current_user_avatar', 'address', 'birthday', 'socials'
		);

		if ( empty( $nickname ) ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__( 'Nickname is required.', 'listdo' ).'</div>';
			echo json_encode($return); exit;
		}

		if ( empty( $email ) ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__( 'E-mail is required.', 'listdo' ).'</div>';
			echo json_encode($return); exit;
		}

		do_action('listdo_before_change_profile');

		update_user_meta( $user->ID, 'nickname', $nickname );

		update_user_meta( $user->ID, 'user_email', $email );
		wp_update_user( array(
			'ID'            => $user->ID,
			'user_email'    => $email,
		) );
		foreach ($general_keys as $key) {
			$value = isset($_POST[$key]) ? sanitize_text_field( $_POST[$key] ) : '';
			update_user_meta( $user->ID, $key, $value );
		}
		foreach ($keys as $key) {
			if ( $key !== 'socials' ) {
				$value = isset($_POST[$key]) ? sanitize_text_field( $_POST[$key] ) : '';
				if ( $key == 'current_user_avatar' ) {
					if ( is_numeric($value) ) {
						update_user_meta( $user->ID, 'apus_user_avatar', $value );
					} else {
						$attachment_id = listdo_create_attachment($value);
						update_user_meta( $user->ID, 'apus_user_avatar', $attachment_id );
					}
				} else {
					update_user_meta( $user->ID, 'apus_'.$key, $value );
				}
			} else {
				$value = isset($_POST[$key]) ? $_POST[$key] : '';
				update_user_meta( $user->ID, 'apus_'.$key, $value );
			}
		}
		$return['msg'] = '<div class="text-success">'.esc_html__( 'Profile has been successfully updated.', 'listdo' ).'</div>';
		echo json_encode($return); exit;
	}

	public function process_change_password() {
		check_ajax_referer( 'listdo-ajax-change-pass-nonce', 'security_change_pass' );
		
		if ( !is_user_logged_in() ) {
			return;
		}

		$old_password = sanitize_text_field( $_POST['old_password'] );
		$new_password = sanitize_text_field( $_POST['new_password'] );
		$retype_password = sanitize_text_field( $_POST['retype_password'] );

		if ( empty( $old_password ) || empty( $new_password ) || empty( $retype_password ) ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__( 'All fields are required.', 'listdo' ).'</div>';
			echo json_encode($return); exit;
		}

		if ( $new_password != $retype_password ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__( 'New and retyped password are not same.', 'listdo' ).'</div>';
			echo json_encode($return); exit;
		}
		
		do_action('listdo_before_change_password');

		$user = wp_get_current_user();

		if ( ! wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
			$return['msg'] = '<div class="text-danger">'.esc_html__( 'Your old password is not correct.', 'listdo' ).'</div>';
			echo json_encode($return); exit;
		}

		wp_set_password( $new_password, $user->ID );
		
    	$info['user_login'] = $user->nickname;
	    $info['user_password'] = $new_password;
	    $info['remember'] = 1;
		wp_signon( $info, false );

		$return['msg'] = '<div class="text-success">'.esc_html__( 'Your password has been successfully changed.', 'listdo' ).'</div>';
		echo json_encode($return); exit;
	}

	public function get_avatar($avatar, $id_or_email='', $size='', $default='', $alt='') {
	    if (is_object($id_or_email)) {
	        
	        $avatar_id = get_the_author_meta( 'apus_user_avatar', $id_or_email->ID );
	        if ( !empty($avatar_id) ) {
	            $avatar_url = wp_get_attachment_image_src($avatar_id, 'thumbnail');
	            if ( !empty($avatar_url[0]) ) {
	                $avatar = '<img src="'.esc_url($avatar_url[0]).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" class="avatar avatar-'.esc_attr($size).' wp-user-avatar wp-user-avatar-'.esc_attr($size).' photo avatar-default" />';
	            }
	        }
	    } else {
	        $avatar_id = get_the_author_meta( 'apus_user_avatar', $id_or_email );
	        if ( !empty($avatar_id) ) {
	            $avatar_url = wp_get_attachment_image_src($avatar_id, 'thumbnail');
	            if ( !empty($avatar_url[0]) ) {
	                $avatar = '<img src="'.esc_url($avatar_url[0]).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" class="avatar avatar-'.esc_attr($size).' wp-user-avatar wp-user-avatar-'.esc_attr($size).' photo avatar-default" />';
	            }
	        }
	    }
	    return $avatar;
	}

	public function set_user_permissions() {
        $role = get_role( 'subscriber' );
        if ( is_object($role) ) {
	        $role->add_cap('upload_files');
	    }

        $role = get_role( 'customer' );
        if ( is_object($role) ) {
	        $role->add_cap('upload_files');
	    }
	}

	public function media_files( $wp_query ) {
		global $current_user;

		if ( ! current_user_can( 'manage_options' ) && ( is_admin() && $wp_query->query['post_type'] === 'attachment' ) ) {
			$wp_query->set( 'author', $current_user->ID );
		}
	}

	public function admin_script() {
		wp_enqueue_media();
		wp_enqueue_script( 'listdo-upload', get_template_directory_uri() . '/js/upload.js', array( 'jquery' ), '20150330', true );
	}
	/*
	 * back/end settings
	 *
	 */
	function user_profile_fields( $user ) {
		$data = get_userdata( $user->ID );
		$avatar = get_the_author_meta( 'apus_user_avatar', $user->ID );
		$avatar_url = wp_get_attachment_image_src($avatar, 'full');
		
		$address = get_the_author_meta( 'apus_address', $user->ID );
		$birthday = get_the_author_meta( 'apus_birthday', $user->ID );
		$marital_status = get_the_author_meta( 'apus_marital_status', $user->ID );
		$sex = get_the_author_meta( 'apus_sex', $user->ID );
		?>
		<h3><?php esc_html_e( 'User Profile', 'listdo' ); ?></h3>

		<table class="form-table">
			<tbody>
			
			<tr>
				<th>
					<label for="lecturer_job"><?php esc_html_e( 'Avatar', 'listdo' ); ?></label>
				</th>
				<td>
					<div class="screenshot-user avatar-screenshot">
			            <?php if ( !empty($avatar_url[0]) ) { ?>
			                <img src="<?php echo esc_url($avatar_url[0]); ?>" alt="<?php esc_attr_e( 'Avatar', 'listdo' ); ?>" />
			            <?php } ?>
			        </div>
			        <input class="widefat upload_image" name="current_user_avatar" type="hidden" value="<?php echo esc_attr($avatar); ?>" />
			        <div class="upload_image_action">
			            <input type="button" class="button radius-3x btn btn-theme user-add-image" value="<?php esc_attr_e( 'Add Avatar', 'listdo' ); ?>">
			            <input type="button" class="button radius-3x btn btn-theme-second user-remove-image" value="<?php esc_attr_e( 'Remove Avatar', 'listdo' ); ?>">
			        </div>
				</td>
			</tr>
			<tr>
				<th>
					<label for="lecturer_mobile"><?php esc_html_e( 'Address', 'listdo' ); ?></label>
				</th>
				<td>
					<input id="change-profile-form-address" type="text" name="address" class="form-control" value="<?php echo ! empty( $address ) ? esc_attr( $address ) : ''; ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="lecturer_facebook"><?php esc_html_e( 'Birthday', 'listdo' ); ?></label>
				</th>
				<td>
					<input id="change-profile-form-birthday" type="text" name="birthday" class="form-control" value="<?php echo ! empty( $birthday ) ? esc_attr( $birthday ) : ''; ?>">
				</td>
			</tr>
			<?php
				$options = listdo_user_social_defaults();
				$socials = get_user_meta( $user->ID, 'apus_socials', true );
				foreach ($options as $key => $label) {
					$value = isset($socials[$key]) ? $socials[$key] : '';
					?>
					<tr>
						<th>
							<label class="col-sm-2 control-label <?php echo esc_attr($key); ?>" for="change-profile-form-<?php echo esc_attr($key); ?>"> <i class="icon-<?php echo esc_attr($key); ?>"></i> <?php echo esc_attr($label); ?></label>
						</th>
						<td>
							<input id="change-profile-form-<?php echo esc_attr($key); ?>" type="text" name="socials[<?php echo esc_attr($key); ?>]" class="form-control" value="<?php echo esc_attr( $value ); ?>">
						</td>
					</tr><!-- /.form-group -->
					<?php
				}
			?>
			</tbody>
		</table>
		<?php
	}

	public function save_user_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$keys = array(
			'current_user_avatar', 'address', 'birthday', 'socials'
		);

		foreach ($keys as $key) {
			if ( $key !== 'socials' ) {
				$value = isset($_POST[$key]) ? sanitize_text_field( $_POST[$key] ) : '';
				if ( $key == 'current_user_avatar' ) {
					update_user_meta($user_id, 'apus_user_avatar', $value);
				} else {
					update_user_meta( $user_id, 'apus_'.$key, $value );
				}
			} else {
				$value = isset($_POST[$key]) ? $_POST[$key] : '';
				update_user_meta( $user_id, 'apus_'.$key, $value );
			}
		}
	}



	public static function process_resend_approve_account() {
		$user_login = isset($_POST['login']) ? $_POST['login'] : '';
		
		if ( empty($user_login) ) {
            echo json_encode(array(
            	'status' => false,
            	'msg' => esc_html__('Username or Email not exactly.', 'listdo')
            ));
            die();
        }

		if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
            $user_obj = get_user_by('email', $user_login);
        } else {
            $user_obj = get_user_by('login', $user_login);
        }
        if ( !empty($user_obj->ID) ) {
	        $user_login_auth = self::get_user_status($user_obj->ID);
	        if ( $user_login_auth == 'pending' ) {

	        	if ( listdo_get_config('user_register_requires_approval', 'auto') == 'email_approve') {
	        		$user_email = stripslashes( $user_obj->data->user_email );
	        	} else {
	        		$user_email = get_option( 'admin_email', false );
	        	}

	        	$subject = listdo_get_config('user_register_need_approve_subject');
				$subject = str_replace('{user_name}', $user_obj->data->display_name, $subject);

				$content = listdo_get_config('user_register_need_approve_content');
				$content = str_replace('{user_name}', $user_obj->data->display_name, $content);
				$content = str_replace('{user_email}', $user_obj->data->user_email, $content);
				$content = str_replace('{website_url}', home_url(), $content);
				$content = str_replace('{website_name}', get_bloginfo( 'name' ), $content);

				$approve_url = get_permalink(listdo_get_config('user_register_approve_page'));
	            $code = get_user_meta($user_id, 'account_approve_key', true);
				$approve_url = add_query_arg(array('user_id' => $user_id, 'approve-key' => $code), $approve_url);
				$content = str_replace('{approve_url}', $approve_url, $content);

				$email_from = get_option( 'admin_email', false );
				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );

				// send the mail
				$result = self::send_mail( $user_email, $subject, $content, $headers );
				
				if ( $result ) {
					echo json_encode(array(
		            	'status' => true,
		            	'msg' => esc_html__('Sent a email successfully.', 'listdo')
		            ));
		            die();
				} else {
					echo json_encode(array(
		            	'status' => false,
		            	'msg' => esc_html__('Send a email error.', 'listdo')
		            ));
		            die();
		        }
	        }
        }
        echo json_encode(array(
        	'status' => false,
        	'msg' => esc_html__('Your account is not available.', 'listdo')
        ));
        die();
	}

	public static function admin_user_auth_callback($user, $password = '') {
    	global $pagenow;
	    
	    $status = self::get_user_status($user->ID);
	    $message = false;
		switch ( $status ) {
			case 'pending':
				$pending_message = self::login_msg($user);
				$message = new WP_Error( 'pending_approval', $pending_message );
				break;
			case 'denied':
				$denied_message = esc_html__('Your account denied.', 'listdo');
				$message = new WP_Error( 'denied_access', $denied_message );
				break;
			case 'approved':
				$message = $user;
				break;
		}

	    return $message;
	}

	public static function process_approve_user() {
		$post = get_post();

		if ( is_object( $post ) ) {
			if ( strpos( $post->post_content, '[listdo_approve_user]' ) !== false ) {
				
				$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
				$code = isset($_GET['approve-key']) ? $_GET['approve-key'] : 0;
				if ( !$user_id ) {
					$error = array(
						'error' => true,
						'msg' => esc_html__('The user is not exists.', 'listdo')
					);

				}
				$user = get_user_by('ID', $user_id);
				if ( empty($user) ) {
					$error = array(
						'error' => true,
						'msg' => esc_html__('The user is not exists.', 'listdo')
					);
				} else {
					$user_code = get_user_meta($user_id, 'account_approve_key', true);
					if ( $code != $user_code ) {
						$error = array(
							'error' => true,
							'msg' => esc_html__('Code is not exactly.', 'listdo')
						);
					}
				}

				if ( empty($error) ) {
					$return = self::update_user_status($user_id, 'approve');
					$error = array(
						'error' => false,
						'msg' => esc_html__('Your account approved.', 'listdo')
					);
					$_SESSION['approve_user_msg'] = $error;
				} else {
					$_SESSION['approve_user_msg'] = $error;
				}
			}
		}
	}

	public static function approve_user_shortcode($atts) {
		ob_start();
		get_template_part( 'template-parts/approve-user' );
		$output = ob_get_clean();
		return $output;
	}

	public static function approve_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		wp_cache_delete( $user->ID, 'users' );
		wp_cache_delete( $user->data->user_login, 'userlogins' );

		$user_email = stripslashes( $user->data->user_email );

		$subject = listdo_get_config('user_register_approved_subject');
		$subject = str_replace('{user_name}', $user->data->display_name, $subject);

		$content = listdo_get_config('user_register_approved_content');
		$content = str_replace('{user_name}', $user->data->display_name, $content);
		$content = str_replace('{user_email}', $user->data->user_email, $content);
		$content = str_replace('{website_url}', home_url(), $content);
		$content = str_replace('{website_name}', get_bloginfo( 'name' ), $content);

		$email_from = get_option( 'admin_email', false );
		$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
		// send the mail
		self::send_mail( $user_email, $subject, $content, $headers );

		// change usermeta tag in database to approved
		update_user_meta( $user->ID, 'user_account_status', 'approved' );
		update_user_meta( $user->ID, 'account_approve_key', '' );

		do_action( 'listdo-new_user_approve_user_approved', $user );
	}

	public static function deny_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		$user_email = stripslashes( $user->data->user_email );

		$subject = listdo_get_config('user_register_denied_subject');
		$subject = str_replace('{user_name}', $user->data->display_name, $subject);

		$content = listdo_get_config('user_register_denied_content');
		$content = str_replace('{user_name}', $user->data->display_name, $content);
		$content = str_replace('{user_email}', $user->data->user_email, $content);
		$content = str_replace('{website_url}', home_url(), $content);
		$content = str_replace('{website_name}', get_bloginfo( 'name' ), $content);

		$email_from = get_option( 'admin_email', false );
		$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
		// send the mail
		self::send_mail( $user_email, $subject, $content, $headers );

		update_user_meta( $user->ID, 'user_account_status', 'denied' );

		do_action( 'listdo-new_user_approve_user_denied', $user );
	}

	public static function get_user_status( $user_id ) {
		$user_status = get_user_meta( $user_id, 'user_account_status', true );

		if ( empty( $user_status ) ) {
			$user_status = 'approved';
		}

		return $user_status;
	}

	public static function update_user_status( $user, $status ) {
		$user_id = absint( $user );
		if ( !$user_id ) {
			return false;
		}

		if ( !in_array( $status, array( 'approve', 'deny' ) ) ) {
			return false;
		}

		$do_update = apply_filters( 'listdo_new_user_approve_validate_status_update', true, $user_id, $status );
		if ( !$do_update ) {
			return false;
		}

		// where it all happens
		do_action( 'listdo_new_user_approve_' . $status . '_user', $user_id );
		do_action( 'listdo_new_user_approve_user_status_update', $user_id, $status );

		return true;
	}

	public static function process_update_user_action() {
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'approve', 'deny' ) ) && !isset( $_GET['new_role'] ) ) {
			check_admin_referer( 'listdo' );

			$sendback = remove_query_arg( array( 'approved', 'denied', 'deleted', 'ids', 'listdo-status-query-submit', 'new_role' ), wp_get_referer() );
			if ( !$sendback ) {
				$sendback = admin_url( 'users.php' );
			}

			$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );

			$status = sanitize_key( $_GET['action'] );
			$user = absint( $_GET['user'] );

			self::update_user_status( $user, $status );

			if ( $_GET['action'] == 'approve' ) {
				$sendback = add_query_arg( array( 'approved' => 1, 'ids' => $user ), $sendback );
			} else {
				$sendback = add_query_arg( array( 'denied' => 1, 'ids' => $user ), $sendback );
			}

			wp_redirect( $sendback );
			exit;
		}
	}

	public static function validate_status_update( $do_update, $user_id, $status ) {
		$current_status = self::get_user_status( $user_id );

		if ( $status == 'approve' ) {
			$new_status = 'approved';
		} else {
			$new_status = 'denied';
		}

		if ( $current_status == $new_status ) {
			$do_update = false;
		}

		return $do_update;
	}

	/**
	 * Add the approve or deny link where appropriate.
	 *
	 * @uses user_row_actions
	 * @param array $actions
	 * @param object $user
	 * @return array
	 */
	public static function user_table_actions( $actions, $user ) {
		if ( $user->ID == get_current_user_id() ) {
			return $actions;
		}

		if ( is_super_admin( $user->ID ) ) {
			return $actions;
		}

		$user_status = self::get_user_status( $user->ID );

		$approve_link = add_query_arg( array( 'action' => 'approve', 'user' => $user->ID ) );
		$approve_link = remove_query_arg( array( 'new_role' ), $approve_link );
		$approve_link = wp_nonce_url( $approve_link, 'listdo' );

		$deny_link = add_query_arg( array( 'action' => 'deny', 'user' => $user->ID ) );
		$deny_link = remove_query_arg( array( 'new_role' ), $deny_link );
		$deny_link = wp_nonce_url( $deny_link, 'listdo' );

		$approve_action = '<a href="' . esc_url( $approve_link ) . '">' . esc_html__( 'Approve', 'listdo' ) . '</a>';
		$deny_action = '<a href="' . esc_url( $deny_link ) . '">' . esc_html__( 'Deny', 'listdo' ) . '</a>';

		if ( $user_status == 'pending' ) {
			$actions[] = $approve_action;
			$actions[] = $deny_action;
		} else if ( $user_status == 'approved' ) {
			$actions[] = $deny_action;
		} else if ( $user_status == 'denied' ) {
			$actions[] = $approve_action;
		}

		return $actions;
	}

	/**
	 * Add the status column to the user table
	 *
	 * @uses manage_users_columns
	 * @param array $columns
	 * @return array
	 */
	public static function add_column( $columns ) {
		$the_columns['user_status'] = esc_html__( 'Status', 'listdo' );

		$newcol = array_slice( $columns, 0, -1 );
		$newcol = array_merge( $newcol, $the_columns );
		$columns = array_merge( $newcol, array_slice( $columns, 1 ) );

		return $columns;
	}

	/**
	 * Show the status of the user in the status column
	 *
	 * @uses manage_users_custom_column
	 * @param string $val
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 */
	public static function status_column( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'user_status' :
				$status = self::get_user_status( $user_id );
				if ( $status == 'approved' ) {
					$status_i18n = esc_html__( 'approved', 'listdo' );
				} else if ( $status == 'denied' ) {
					$status_i18n = esc_html__( 'denied', 'listdo' );
				} else if ( $status == 'pending' ) {
					$status_i18n = esc_html__( 'pending', 'listdo' );
				}
				return $status_i18n;
				break;

			default:
		}

		return $val;
	}

	/**
	 * Add a filter to the user table to filter by user status
	 *
	 * @uses restrict_manage_users
	 */
	public static function status_filter( $which ) {
		$id = 'listdo_filter-' . $which;

		$filter_button = submit_button( esc_html__( 'Filter', 'listdo' ), 'button', 'listdo-status-query-submit', false, array( 'id' => 'listdo-status-query-submit' ) );
		$filtered_status = null;
		if ( ! empty( $_REQUEST['listdo_filter-top'] ) || ! empty( $_REQUEST['listdo_filter-bottom'] ) ) {
			$filtered_status = esc_attr( ( ! empty( $_REQUEST['listdo_filter-top'] ) ) ? $_REQUEST['listdo_filter-top'] : $_REQUEST['listdo_filter-bottom'] );
		}
		$statuses = array('pending', 'approved', 'denied');
		?>
		<label class="screen-reader-text" for="<?php echo esc_attr($id); ?>"><?php esc_html_e( 'View all users', 'listdo' ); ?></label>
		<select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" style="float: none; margin: 0 0 0 15px;">
			<option value=""><?php esc_html_e( 'View all users', 'listdo' ); ?></option>
		<?php foreach ( $statuses as $status ) : ?>
			<option value="<?php echo esc_attr( $status ); ?>"<?php selected( $status, $filtered_status ); ?>><?php echo esc_html( $status ); ?></option>
		<?php endforeach; ?>
		</select>
		<?php echo apply_filters( 'listdo_filter_button', $filter_button ); ?>
		<style>
			#listdo-status-query-submit {
				float: right;
				margin: 2px 0 0 5px;
			}
		</style>
	<?php
	}

	/**
	 * Modify the user query if the status filter is being used.
	 *
	 * @uses pre_user_query
	 * @param $query
	 */
    public static function filter_by_status( $query ) {
		global $wpdb;

		if ( !is_admin() ) {
			return;
		}
		
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();
		if ( isset( $screen ) && 'users' != $screen->id ) {
			return;
		}
		$filter = null;
		if ( ! empty( $_REQUEST['listdo_filter-top'] ) || ! empty( $_REQUEST['listdo_filter-bottom'] ) ) {
			$filter = esc_attr( ( ! empty( $_REQUEST['listdo_filter-top'] ) ) ? $_REQUEST['listdo_filter-top'] : $_REQUEST['listdo_filter-bottom'] );
		}
		if ( $filter != null ) {

			$query->query_from .= " INNER JOIN {$wpdb->usermeta} ON ( {$wpdb->users}.ID = $wpdb->usermeta.user_id )";

			if ( 'approved' == $filter ) {
				$query->query_fields = "DISTINCT SQL_CALC_FOUND_ROWS {$wpdb->users}.ID";
				$query->query_from .= " LEFT JOIN {$wpdb->usermeta} AS mt1 ON ({$wpdb->users}.ID = mt1.user_id AND mt1.meta_key = 'user_account_status')";
				$query->query_where .= " AND ( ( $wpdb->usermeta.meta_key = 'user_account_status' AND CAST($wpdb->usermeta.meta_value AS CHAR) = 'approved' ) OR mt1.user_id IS NULL )";
			} else {
				$query->query_where .= " AND ( ($wpdb->usermeta.meta_key = 'user_account_status' AND CAST($wpdb->usermeta.meta_value AS CHAR) = '{$filter}') )";
			}
		}
	}

	public static function register_msg($user) {
		$requires_approval = listdo_get_config('user_register_requires_approval', 'auto');

		if ( $requires_approval == 'email_approve' ) {
			return esc_html__('Registration complete. Before you can login, you must active your account sent to your email address.', 'listdo');
		} elseif ( $requires_approval == 'admin_approve' ) {
			return esc_html__('Registration complete. Your account has to be confirmed by an administrator before you can login', 'listdo');
		} else {
			return esc_html__('Your account has to be confirmed yet.', 'listdo');
		}
	}
	
	public static function login_msg($user) {
		$requires_approval = listdo_get_config('user_register_requires_approval', 'auto');
		
		if ( $requires_approval == 'email_approve' ) {
			return sprintf(__('Account account has not confirmed yet, you must active your account with the link sent to your email address. If you did not receive this email, please check your junk/spam folder. <a href="javascript:void(0);" class="listdo-resend-approve-account-btn" data-login="%s">Click here</a> to resend the activation email.', 'listdo'), $user->user_login );
		} elseif ( $requires_approval == 'admin_approve' ) {
			return esc_html__('Your account has to be confirmed by an administrator before you can login.', 'listdo');
		} else {
			return esc_html__('Your account has to be confirmed yet.', 'listdo');
		}
	}
}

new Listdo_Apus_Userinfo();
?>