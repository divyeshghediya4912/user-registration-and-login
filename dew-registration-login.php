<?php
/**
 * Plugin Name: User Registration and Login
 * Description: Provides simple front end registration forms and login forms using shortcodes
 * Version: 1.0.2
 * Plugin URI: http://dewtechnolab.com/portfolio/
 * Author: Dew Technolab
 * Author URI: http://dewtechnolab.com/
 * Requires at least: 4.5
 * Text Domain: dew-registration-login
 * Domain Path: /languages
 * License: GPLv3 or later License
 * URI: http://www.gnu.org/licenses/gpl-3.0.html
**/

class dew_registration_login {

	function __construct() {
		// Execute the action
		add_action( 'init', array( $this, 'dew_register_css' ) );
		// Load our form css
		add_action( 'wp_footer', array( $this, 'dew_print_css' ) );
		// Enable the user with no privileges to run ajax_login() in AJAX
		add_action( 'wp_ajax_nopriv_ajaxlogin', array( $this, 'ajax_login' ) );
		// Enable the user with no privileges to run ajax_register() in AJAX
		add_action( 'wp_ajax_nopriv_ajaxregister', array( $this, 'ajax_register' ) );
		// Enable the user to run ajax_profile() in AJAX
		add_action( 'wp_ajax_ajaxprofile', array( $this, 'ajax_profile' ) );
	}

	// user registration login form
	public function dew_registration_form() {
	 
		// only show the registration form to non-logged-in members
		if(!is_user_logged_in()) {
	 
			global $dew_load_css;
	 
			// set this to true so the CSS is loaded
			$dew_load_css = true;
	 
			// check to make sure user registration is enabled
			$registration_enabled = get_option('users_can_register');
	 
			// only show the registration form if allowed
			if($registration_enabled) {
				$output = $this->dew_registration_form_fields();
			} else {
				$output = __('User registration is not enabled');
			}
		} else {
			// could show some logged in user info here
			$output = '<script>
				document.location.href = "'.home_url().'";
			</script>';
		}
		return $output;
	}

	// user login form
	public function dew_login_form() {

		if(!is_user_logged_in()) {
	 
			global $dew_load_css;
	 
			// set this to true so the CSS is loaded
			$dew_load_css = true;
	 
			$output = $this->dew_login_form_fields();
		} else {
			// could show some logged in user info here
			$output = '<script>
				document.location.href = "'.home_url().'";
			</script>';
		}
		return $output;
	}

	// user profile login form
	public function dew_profile_form() {
	 
		// only show the profile form to non-logged-in members
		if(is_user_logged_in()) {
	 
			global $dew_load_css;
	 
			// set this to true so the CSS is loaded
			$dew_load_css = true;
	 
			// check to make sure user profile is enabled
			$profile_enabled = get_option('users_can_register');
	 
			// only show the profile form if allowed
			if($profile_enabled) {
				$output = $this->dew_profile_form_fields();
			} else {
				$output = __('User profile is not enabled');
			}
		} else {
			// could show some logged in user info here
			$output = '<script>
				document.location.href = "'.home_url().'";
			</script>';
		}
		return $output;
	}

	// registration form fields
	public function dew_registration_form_fields() {
		ob_start();
		// show any error messages after form submission
		$this->dew_show_error_messages(); ?>
		<form id="register" class="ajax-auth border-remove dew_form" action="register" method="post">
			<p class="status"></p>
			<?php wp_nonce_field('ajax-register-nonce', 'signonsecurity'); ?>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="firstname" type="text" name="firstname" class="required border-none" placeholder="First Name *">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="lastname" type="text" name="lastname" class="required border-none" placeholder="Last Name *">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="signonname" type="text" name="username" class="required border-none" placeholder="Username *">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="email" type="text" class="required email" name="email" placeholder="Email *">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="password" type="password" name="password" class="required border-none" maxlength="10" placeholder="Password *">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="password2" type="password" name="password2" class="required border-none" maxlength="10" placeholder="Confirm Password *">
				</div>
			</div>
			<div class="row-colum"> 
				<div class="col-md-12-colum">
					<input class="submit_button" type="submit" value="Submit">
				</div>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	// login form fields
	function dew_login_form_fields() {
		ob_start();
		$options = get_option( 'dew_settings' );
		$dew_select_field_2 = isset($options['dew_select_field_2']) ? $options['dew_select_field_2'] : '';
		// show any error messages after form submission
		$this->dew_show_error_messages(); ?>
		<form id="login" class="ajax-auth login-main dew_form" action="login" method="post">
			<p class="status"></p>  
			<?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
			<div class="row-colum-login"> 
				<div class="col-md-12-colum">
					<input id="username" type="text" class="required email" name="username" placeholder="Email *">
				</div>
			</div>
			<div class="row-colum-login"> 
				<div class="col-md-12-colum">
					<input id="password" type="password" class="required" name="password" placeholder="Password *">
				</div>
			</div>
			<input class="submit_button" type="submit" value="Login">
			<?php if(!empty($dew_select_field_2)) { ?>
			<div class="password-class">
				<a href="<?php echo get_permalink($dew_select_field_2); ?>">Register</a>
			</div>
			<?php } ?>
			<div class="password-class">
				<a href="<?php echo home_url().'wp-login.php?action=lostpassword'; ?>">Lost Passowrd</a>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	// profile form fields
	public function dew_profile_form_fields() {
		ob_start();
		// show any error messages after form submission
		$this->dew_show_error_messages();
		$current_user = wp_get_current_user(); ?>
		<form id="profile" class="ajax-auth border-remove dew_form" action="profile" method="post">
			<p class="status"></p>
			<?php wp_nonce_field('ajax-profile-nonce', 'signonsecurity'); ?>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="firstname" type="text" name="firstname" class="required border-none" placeholder="First Name *" value="<?php echo esc_html( $current_user->user_firstname ); ?>">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="lastname" type="text" name="lastname" class="required border-none" placeholder="Last Name *" value="<?php echo esc_html( $current_user->user_lastname ); ?>">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="email" type="text" class="required email" name="email" placeholder="Email *" value="<?php echo esc_html( $current_user->user_email ); ?>">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="password" type="password" name="password" class="border-none" maxlength="10" placeholder="Password">
				</div>
			</div>
			<div class="row-colum">
				<div class="col-md-12-colum">
					<input id="password2" type="password" name="password2" class="border-none" maxlength="10" placeholder="Confirm Password">
				</div>
			</div>
			<div class="row-colum"> 
				<div class="col-md-12-colum">
					<input class="submit_button" type="submit" value="Submit">
				</div>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	// used for tracking error messages
	function dew_errors(){
		static $wp_error; // Will hold global variable safely
		return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
	}

	// displays error messages from form submissions
	function dew_show_error_messages() {
		if($codes = $this->dew_errors()->get_error_codes()) {
			echo '<div class="dew_errors">';
				// Loop error codes and display errors
			   foreach($codes as $code){
					$message = $this->dew_errors()->get_error_message($code);
					echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
				}
			echo '</div>';
		}
	}

	// register our form css
	function dew_register_css() {
		wp_register_style('dew-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');

		wp_register_script('validate-script', plugin_dir_url( __FILE__ ) . '/js/jquery.validate.js', array('jquery') ); 
		wp_enqueue_script('validate-script');

		wp_register_script('ajax-auth-script', plugin_dir_url( __FILE__ ) . '/js/ajax-auth-script.js', array('jquery') ); 
		wp_enqueue_script('ajax-auth-script');

		$options = get_option( 'dew_settings' );
		$dew_select_field_0 = isset($options['dew_select_field_0']) ? $options['dew_select_field_0'] : '';
		$dew_select_field_1 = isset($options['dew_select_field_1']) ? $options['dew_select_field_1'] : '';
		$register_redirect = empty($dew_select_field_0) ? home_url().'/wp-login.php' : get_permalink($dew_select_field_0);
		$login_redirect = empty($dew_select_field_1) ? home_url() : get_permalink($dew_select_field_1);

		wp_localize_script( 'ajax-auth-script', 'ajax_auth_object', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => $login_redirect,
			'redirecturls' => $register_redirect,
			'loadingmessage' => __('Sending user info, please wait...')
		));
	}

	// load our form css
	function dew_print_css() {
		global $dew_load_css;

		// this variable is set to TRUE if the short code is used on a page/post
		if ( ! $dew_load_css )
			return; // this means that neither short code is present, so we get out of here

		wp_print_styles('dew-form-css');
	}

	function ajax_login(){

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-login-nonce', 'security' );

		// Nonce is checked, get the POST data and sign user on
		// Call auth_user_login
		$this->auth_user_login($_POST['username'], $_POST['password'], 'Login'); 
		
		die();
	}

	function ajax_register(){

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-register-nonce', 'security' );
			
		// Nonce is checked, get the POST data and sign user on
		$info = array();
		$info['user_login'] = sanitize_user($_POST['username']);
		$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = sanitize_user($_POST['firstname']);
		$info['last_name'] = sanitize_user($_POST['lastname']);
		$info['user_pass'] = sanitize_text_field($_POST['password']);
		$info['user_email'] = sanitize_email( $_POST['email']);
		
		// Register the user
		$user_register = $user_id = wp_insert_user( $info );

		if ( is_wp_error($user_register) ){	
			$error  = $user_register->get_error_codes();
			
			if(in_array('empty_user_login', $error))
				echo json_encode(array('loggedin'=>false, 'message'=>__($user_register->get_error_message('empty_user_login'))));
			elseif(in_array('existing_user_login',$error))
				echo json_encode(array('loggedin'=>false, 'message'=>__('This username is already registered.')));
			elseif(in_array('existing_user_email',$error))
			echo json_encode(array('loggedin'=>false, 'message'=>__('This email address is already registered.')));
		} else {
			echo json_encode(array('signedin'=>true, 'message'=>__('Successful, redirecting...')));
		}
		die();
	}

	function auth_user_login($user_login, $password, $login) {
		$info = array();
		$info['user_login'] = $user_login;
		$info['user_password'] = $password;

		// From false to '' since v 4.9
		$user_signon = wp_signon( $info, '' );
		if ( is_wp_error($user_signon) ){
			echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
		} else {
			wp_set_current_user($user_signon->ID); 
			echo json_encode(array('loggedin'=>true, 'message'=>__($login.' successful, redirecting...')));
		}
		die();
	}

	function ajax_profile() {

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-profile-nonce', 'security' );
			
		// Nonce is checked, get the POST data and sign user on
		$info = array();
		$info['ID'] = get_current_user_id();
		$info['first_name'] = sanitize_user($_POST['firstname']);
		$info['last_name'] = sanitize_user($_POST['lastname']);
		$info['user_email'] = sanitize_email( $_POST['email']);
		if (!empty($_POST['password'])) {
			$info['user_pass'] = sanitize_text_field($_POST['password']);
		}
		// Register the user
		$user_register = wp_update_user( $info );

		if ( is_wp_error($user_register) ){	
			//$error  = $user_register->get_error_codes();
			echo json_encode(array('update'=>false, 'message'=>__('$error')));
		} else {
			echo json_encode(array('update'=>true, 'message'=>__('Successful Update')));
		}
		die();
	}

}

$dew_registration_login_obj = new dew_registration_login;

add_shortcode('dew_register_form', array($dew_registration_login_obj, 'dew_registration_form'));
add_shortcode('dew_profile_form', array($dew_registration_login_obj, 'dew_profile_form'));
add_shortcode('dew_signin_form', array($dew_registration_login_obj, 'dew_login_form'));

include( plugin_dir_path( __FILE__ ) . 'includes/dew-settings.php');