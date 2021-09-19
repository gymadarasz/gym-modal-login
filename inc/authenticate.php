<?php
//ajax init

function gym_modal_ajax_login_init()
{
    if (!is_admin()) :
    wp_register_script(
        'ajax-login-script',
        plugins_url('/assets/js/scripts.js', dirname(__FILE__)),
        array( 'jquery' )
    );
    wp_enqueue_script('ajax-login-script');
    endif;

    wp_localize_script('ajax-login-script', 'ajax_login_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'loginRedirectURL' => get_option('gym_login_redirect')=='' ? '' : get_option('gym_login_redirect'),
        'registerRedirectURL' => get_option('gym_register_redirect')=='' ? '' : get_option('gym_register_redirect'),
    ));

    add_action('wp_ajax_nopriv_ajaxregister', 'gym_modal_ajax_registration');
    add_action('wp_ajax_nopriv_ajaxlogin', 'gym_modal_ajax_login');
    add_action('wp_ajax_nopriv_ajaxlostpass', 'gym_modal_ajax_lostPassword');
}
add_action('init', 'gym_modal_ajax_login_init');

// Login Process

function gym_modal_ajax_login()
{
    check_ajax_referer('ajax-login-nonce', 'security');

    $credentials = array();
    $credentials['user_login'] = $_POST['username'];
    $credentials['user_password'] = $_POST['password'];
    $rememberme = $_POST['rememberme'];
    
    if ($rememberme=="forever"):
    $credentials['remember'] = true; else:
    $credentials['remember'] = false;
    endif;
    
    if ($credentials['user_login'] == null || $credentials['user_password'] == null) {
        echo json_encode(array('loggedin'=>false, 'message'=>__('<p class="alert alert-info" data-alert="alert">Please fill all the fields.</p>', 'gym')));
    } else {
        if ($credentials['user_login'] != null && $credentials['user_password'] != null) {
            $errors = wp_signon($credentials, false);
        }
        if (is_wp_error($errors)) {
            $display_errors = __('<p class="alert alert-error" data-alert="alert"><strong>ERROR</strong>: Wrong username or password.</p>', 'gym');
            echo json_encode(array('loggedin'=>false, 'message'=>$display_errors));
        } else {
            echo json_encode(array('loggedin'=>true, 'message'=>__('<p class="alert alert-success" data-alert="alert">Login successful, redirecting...</p>', 'gym')));
        }
    }
    die();
}


/**
 * gym_is_pwned
 *
 * @param string $pwd 
 * @return bool
 */
function gym_is_pwned($pwd)
{
	ob_start();
	$sha1 = sha1($pwd);
	$ch = curl_init();
	if (!$ch) {
		throw new Exception('CURL is not initialized');
	}
	$key = substr($sha1, 0, 5);
	if (!curl_setopt($ch, CURLOPT_URL, 'https://api.pwnedpasswords.com/range/' . $key)) {
		throw new Exception('CURL GET failed');
	}
	if (curl_exec($ch) === false) {
		throw new Exception('CURL exec failed');
	}
	if ($errno = curl_errno()) {
		throw new Exception('CURL error: ' . curl_error() . " ($errno)");
	}
	curl_close($ch);
	$contents = strtolower(ob_get_clean());
	$rest = preg_replace('/^' . $key . '/', '', $sha1);
	return strpos($contents, $rest) !== false;
}


/**
 * Handles registering a new user. (pwned password check extension)
 *
 * @since 2.5.0
 *
 * @param string $user_login User's username for logging in
 * @param string $user_email User's email address to send password and add
 * @param string $user_password User's password
 * @return int|WP_Error Either user's ID or error on failure.
 */
function gym_register_new_user( $user_login, $user_email, $user_password ) {
	$errors = new WP_Error();

	$sanitized_user_login = sanitize_user( $user_login );
	/**
	 * Filters the email address of a user being registered.
	 *
	 * @since 2.1.0
	 *
	 * @param string $user_email The email address of the new user.
	 */
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username.
	if ( '' === $sanitized_user_login ) {
		$errors->add( 'empty_username', __( '<strong>Error</strong>: Please enter a username.' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>Error</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>Error</strong>: This username is already registered. Please choose another one.' ) );

	} else {
		/** This filter is documented in wp-includes/user.php */
		$illegal_user_logins = (array) apply_filters( 'illegal_user_logins', array() );
		if ( in_array( strtolower( $sanitized_user_login ), array_map( 'strtolower', $illegal_user_logins ), true ) ) {
			$errors->add( 'invalid_username', __( '<strong>Error</strong>: Sorry, that username is not allowed.' ) );
		}
	}

	// Check the email address.
	if ( '' === $user_email ) {
		$errors->add( 'empty_email', __( '<strong>Error</strong>: Please type your email address.' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( '<strong>Error</strong>: The email address isn&#8217;t correct.' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( '<strong>Error</strong>: This email is already registered. Please choose another one.' ) );
	}


	// Check password pwned
	if (gym_is_pwned($user_password)) {
		$errors->add('password_reset_pwned', __( '<strong>Error</strong>: This password is not secure, please choose an other one.' ));
	}

	/**
	 * Fires when submitting registration form data, before the user is created.
	 *
	 * @since 2.1.0
	 *
	 * @param string   $sanitized_user_login The submitted username after being sanitized.
	 * @param string   $user_email           The submitted email.
	 * @param WP_Error $errors               Contains any errors with submitted username and email,
	 *                                       e.g., an empty field, an invalid username or email,
	 *                                       or an existing username or email.
	 */
	do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

	/**
	 * Filters the errors encountered when a new user is being registered.
	 *
	 * The filtered WP_Error object may, for example, contain errors for an invalid
	 * or existing username or email address. A WP_Error object should always be returned,
	 * but may or may not contain errors.
	 *
	 * If any errors are present in $errors, this will abort the user's registration.
	 *
	 * @since 2.1.0
	 *
	 * @param WP_Error $errors               A WP_Error object containing any errors encountered
	 *                                       during registration.
	 * @param string   $sanitized_user_login User's username after it has been sanitized.
	 * @param string   $user_email           User's email.
	 */
	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->has_errors() ) {
		return $errors;
	}

	$user_id   = wp_create_user( $sanitized_user_login, $user_password, $user_email );
	if ( ! $user_id || is_wp_error( $user_id ) ) {
		$errors->add(
			'registerfail',
			sprintf(
				/* translators: %s: Admin email address. */
				__( '<strong>Error</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">site admin</a>!' ),
				get_option( 'admin_email' )
			)
		);
		return $errors;
	}

	update_user_meta( $user_id, 'default_password_nag', true ); // Set up the password change nag.

	/**
	 * Fires after a new user registration has been recorded.
	 *
	 * @since 4.4.0
	 *
	 * @param int $user_id ID of the newly registered user.
	 */
	do_action( 'register_new_user', $user_id );

	return $user_id;
}

// Register Process

function gym_modal_ajax_registration()
{
    check_ajax_referer('ajax-form-nonce', 'security2');

    $user_login = $_POST['user_login'];
    $user_email = $_POST['user_email'];
	$user_password = $_POST['user_password'];
    
    if ($user_login == null || $user_email == null) {
        echo json_encode(array('registered'=>false, 'message'=>__('<p class="alert alert-info" data-alert="alert">Please fill all the fields.</p>', 'gym')));
    } else {
        $errors = gym_register_new_user($user_login, $user_email, $user_password);
        if (is_wp_error($errors)) {
            $registration_error_messages = $errors->errors;
            $display_errors = '<div class="alert alert-error" data-alert="alert">';
            foreach ($registration_error_messages as $error) {
                $display_errors .= '<div>'.$error[0].'</div>';
            }
            $display_errors .= '</div>';
            echo json_encode(array(
				'registered' => false,
				'message'  => $display_errors,
			));
        } else {
            echo json_encode(array(
            'registered' => true,
            'message'  => __('<p class="alert alert-success" data-alert="alert">Registration complete. Please check your e-mail.</p>', 'gym'),
        ));
        }
    }
    die();
}

// Lost Password

function gym_modal_ajax_lostPassword()
{
    check_ajax_referer('ajax-form-nonce', 'security3');

    $lost_pass = $_POST['lost_pass'];
    
    if ($lost_pass == null) {
        echo json_encode(array('reset'=>false, 'message'=>__('<p class="alert alert-info" data-alert="alert">Please fill all the fields.</p>', 'gym')));
    } else {
        if (is_email($lost_pass)) {
            $username = sanitize_email($lost_pass);
        } else {
            $username = sanitize_user($lost_pass);
        }

        $user_forgotten = gym_modal_ajax_lostPassword_retrieve($username);
    
        if (is_wp_error($user_forgotten)) {
            $lostpass_error_messages = $user_forgotten->errors;
            $display_errors = '<div class="alert alert-error" data-alert="alert">';
            foreach ($lostpass_error_messages as $error) {
                $display_errors .= '<div>'.$error[0].'</div>';
            }
            $display_errors .= '</div>';
        
            echo json_encode(array(
            'reset' 	 => false,
            'message' => $display_errors,
        ));
        } else {
            echo json_encode(array(
            'reset'   => true,
            'message' => __('<p class="alert alert-success" data-alert="alert">Password Reset. Please check your email.</p>', 'gym'),
        ));
        }
    }
    
    die();
}

function gym_modal_ajax_lostPassword_retrieve($user_data)
{
    global $wpdb, $current_site, $wp_hasher;

    $errors = new WP_Error();

    if (empty($user_data)) {
        $errors->add('empty_username', __('Please enter a username or e-mail address.', 'gym'));
    } elseif (strpos($user_data, '@')) {
        $user_data = get_user_by('email', trim($user_data));
        if (empty($user_data)) {
            $errors->add('invalid_email', __('There is no user registered with that email address.', 'gym'));
        }
    } else {
        $login = trim($user_data);
        $user_data = get_user_by('login', $login);
    }

    if ($errors->get_error_code()) {
        return $errors;
    }

    if (! $user_data) {
        $errors->add('invalidcombo', __('Invalid username or e-mail.', 'gym'));
        return $errors;
    }

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    do_action('retrieve_password', $user_login);

    $allow = apply_filters('allow_password_reset', true, $user_data->ID);

    if (! $allow) {
        return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'gym'));
    } elseif (is_wp_error($allow)) {
        return $allow;
    }

    $key = wp_generate_password(20, false);
    do_action('retrieve_password_key', $user_login, $key);
    if (empty($wp_hasher)) {
        require_once ABSPATH . 'wp-includes/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
    }
    $hashed = $wp_hasher->HashPassword($key);
    $wpdb->update($wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ));
    
    $message = __('Someone requested that the password be reset for the following account:', 'gym') . "\r\n\r\n";
    $message .= network_home_url('/') . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'gym') . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:', 'gym') . "\r\n\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n\r\n";
    
    if (is_multisite()) {
        $blogname = $GLOBALS['current_site']->site_name;
    } else {
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }

    $title   = sprintf(__('[%s] Password Reset'), $blogname);
    $title   = apply_filters('retrieve_password_title', $title);
    $message = apply_filters('retrieve_password_message', $message, $key);

    if ($message && ! wp_mail($user_email, $title, $message)) {
        $errors->add('noemail', __('The e-mail could not be sent.<br />Possible reason: your host may have disabled the mail() function.', 'gym'));

        return $errors;

        wp_die();
    }

    return true;
}
