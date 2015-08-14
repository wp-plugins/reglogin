<?php
/*
Plugin Name: RegLogin
Plugin URI: https://frankhost.net/reglogin
Description: Provides Front end registration and login forms
Version: 1.0
Author: Francis Mukobi
Author URI: https://frankhost.net
*/


// user registration login form
function reglogin_registration_form() {
 
	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {
 
		global $reglogin_load_css;
 
		// set this to true so the CSS is loaded
		$reglogin_load_css = true;
 
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
 
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = reglogin_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	}
}
//connect codes with [register_form] short code
add_shortcode('register_form', 'reglogin_registration_form');



// user login form
function reglogin_login_form() {
 
	if(!is_user_logged_in()) {
 
		global $reglogin_load_css;
 
		// set this to true so the CSS is loaded
		$reglogin_load_css = true;
 
		$output = reglogin_login_form_fields();
	} else {
		// could show some logged in user info here
		// $output = 'user info here';
	}
	return $output;
}
//connect codes with [login_form] short code
add_shortcode('login_form', 'reglogin_login_form');




// registration form fields
function reglogin_registration_form_fields() {
 
	ob_start(); ?>	
		<h3 class="reglogin_header"><?php _e('Register New Account'); ?></h3>
 
		<?php 
		// show any error messages after form submission
		reglogin_show_error_messages(); ?>
 
		<form id="reglogin_registration_form" class="reglogin_form" action="" method="POST">
			<fieldset>
				<p>
					<label for="reglogin_user_Login"><?php _e('Username'); ?></label>
					<input name="reglogin_user_login" id="reglogin_user_login" class="required" type="text"/>
				</p>
				<p>
					<label for="reglogin_user_email"><?php _e('Email'); ?></label>
					<input name="reglogin_user_email" id="reglogin_user_email" class="required" type="email"/>
				</p>
				<p>
					<label for="reglogin_user_first"><?php _e('First Name'); ?></label>
					<input name="reglogin_user_first" id="reglogin_user_first" type="text"/>
				</p>
				<p>
					<label for="reglogin_user_last"><?php _e('Last Name'); ?></label>
					<input name="reglogin_user_last" id="reglogin_user_last" type="text"/>
				</p>
				<p>
					<label for="password"><?php _e('Password'); ?></label>
					<input name="reglogin_user_pass" id="password" class="required" type="password"/>
				</p>
				<p>
					<label for="password_again"><?php _e('Password Again'); ?></label>
					<input name="reglogin_user_pass_confirm" id="password_again" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="reglogin_register_nonce" value="<?php echo wp_create_nonce('reglogin-register-nonce'); ?>"/>
					<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
				</p>
			</fieldset>
		</form>
	<?php
	return ob_get_clean();
}



// login form fields
function reglogin_login_form_fields() {
 
	ob_start(); ?>
		<h3 class="reglogin_header"><?php _e('Login'); ?></h3>
 
		<?php
		// show any error messages after form submission
		reglogin_show_error_messages(); ?>
 
		<form id="reglogin_login_form"  class="reglogin_form"action="" method="post">
			<fieldset>
				<p>
					<label for="reglogin_user_Login">Username</label>
					<input name="reglogin_user_login" id="reglogin_user_login" class="required" type="text"/>
				</p>
				<p>
					<label for="reglogin_user_pass">Password</label>
					<input name="reglogin_user_pass" id="reglogin_user_pass" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="reglogin_login_nonce" value="<?php echo wp_create_nonce('reglogin-login-nonce'); ?>"/>
					<input id="reglogin_login_submit" type="submit" value="Login"/>
				</p>
			</fieldset>
		</form>
	<?php
	return ob_get_clean();
}




// logs a member in after submitting a form
function reglogin_login_member() {
 
	if(isset($_POST['reglogin_user_login']) && wp_verify_nonce($_POST['reglogin_login_nonce'], 'reglogin-login-nonce')) {
 
		// this returns the user ID and other info from the user name
		$user = get_userdatabylogin($_POST['reglogin_user_login']);
 
		if(!$user) {
			// if the user name doesn't exist
			reglogin_errors()->add('empty_username', __('Invalid username'));
		}
 
		if(!isset($_POST['reglogin_user_pass']) || $_POST['reglogin_user_pass'] == '') {
			// if no password was entered
			reglogin_errors()->add('empty_password', __('Please enter a password'));
		}
 
		// check the user's login with their password
		if(!wp_check_password($_POST['reglogin_user_pass'], $user->user_pass, $user->ID)) {
			// if the password is incorrect for the specified user
			reglogin_errors()->add('empty_password', __('Incorrect password'));
		}
 
		// retrieve all error messages
		$errors = reglogin_errors()->get_error_messages();
 
		// only log the user in if there are no errors
		if(empty($errors)) {
 
			wp_setcookie($_POST['reglogin_user_login'], $_POST['reglogin_user_pass'], true);
			wp_set_current_user($user->ID, $_POST['reglogin_user_login']);	
			do_action('wp_login', $_POST['reglogin_user_login']);
 
			wp_redirect(home_url()); exit;
		}
	}
}
add_action('init', 'reglogin_login_member');




// register a new user
function reglogin_add_new_member() {
  	if (isset( $_POST["reglogin_user_login"] ) && wp_verify_nonce($_POST['reglogin_register_nonce'], 'reglogin-register-nonce')) {
		$user_login		= $_POST["reglogin_user_login"];	
		$user_email		= $_POST["reglogin_user_email"];
		$user_first 	= $_POST["reglogin_user_first"];
		$user_last	 	= $_POST["reglogin_user_last"];
		$user_pass		= $_POST["reglogin_user_pass"];
		$pass_confirm 	= $_POST["reglogin_user_pass_confirm"];
 
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
 
		if(username_exists($user_login)) {
			// Username already registered
			reglogin_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			// invalid username
			reglogin_errors()->add('username_invalid', __('Invalid username'));
		}
		if($user_login == '') {
			// empty username
			reglogin_errors()->add('username_empty', __('Please enter a username'));
		}
		if(!is_email($user_email)) {
			//invalid email
			reglogin_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($user_email)) {
			//Email address already registered
			reglogin_errors()->add('email_used', __('Email already registered'));
		}
		if($user_pass == '') {
			// passwords do not match
			reglogin_errors()->add('password_empty', __('Please enter a password'));
		}
		if($user_pass != $pass_confirm) {
			// passwords do not match
			reglogin_errors()->add('password_mismatch', __('Passwords do not match'));
		}
 
		$errors = reglogin_errors()->get_error_messages();
 
		// only create the user in if there are no errors
		if(empty($errors)) {
 
			$new_user_id = wp_insert_user(array(
					'user_login'		=> $user_login,
					'user_pass'	 		=> $user_pass,
					'user_email'		=> $user_email,
					'first_name'		=> $user_first,
					'last_name'			=> $user_last,
					'user_registered'	=> date('Y-m-d H:i:s'),
					'role'				=> 'subscriber'
				)
			);
			if($new_user_id) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification($new_user_id);
 
				// log the new user in
				wp_setcookie($user_login, $user_pass, true);
				wp_set_current_user($new_user_id, $user_login);	
				do_action('wp_login', $user_login);
 
				// send the newly created user to the home page after logging them in
				wp_redirect(home_url()); exit;
			}
 
		}
 
	}
}
add_action('init', 'reglogin_add_new_member');




// displays error messages from form submissions
function reglogin_show_error_messages() {
	if($codes = reglogin_errors()->get_error_codes()) {
		echo '<div class="reglogin_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = reglogin_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}




// register our form css
function reglogin_register_css() {
	wp_register_style('reglogin-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
}
add_action('init', 'reglogin_register_css');



// load our form css
function reglogin_print_css() {
	global $reglogin_load_css;
 
	// this variable is set to TRUE if the short code is used on a page/post
	if ( ! $reglogin_load_css )
		return; // this means that neither short code is present, so we get out of here
 
	wp_print_styles('reglogin-form-css');
}
add_action('wp_footer', 'reglogin_print_css');

