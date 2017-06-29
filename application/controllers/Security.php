<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Security extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Force SSL
		//$this->force_ssl();

		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
	}

	// -----------------------------------------------------------------------

	/**
	 * Demonstrate being redirected to login.
	 * If you are logged in and request this method,
	 * you'll see the message, otherwise you will be
	 * shown the login form. Once login is achieved,
	 * you will be redirected back to this method.
	 */
	public function index()
	{
			if ( $this->verify_min_level(9) )
			{
				echo $this->load->view('security/page_header', '', TRUE);
				echo $this->load->view('security/admin', '', TRUE);
				echo $this->load->view('security/page_footer', '', TRUE);
			}
			elseif ( $this->verify_min_level(6) )
			{
				echo $this->load->view('security/page_header', '', TRUE);
				echo $this->load->view('security/employee', '', TRUE);
				echo $this->load->view('security/page_footer', '', TRUE);
			}	
			else
			{	
				// Set redirect protocol
				redirect( site_url( 'login') );
			}
	}
	
	// -----------------------------------------------------------------------

	/**
	 * A basic page that shows verification that the user is logged in or not.
	 * If the user is logged in, a link to "Logout" will be in the menu.
	 * If they are not logged in, a link to "Login" will be in the menu.
	 */
	public function home()
	{
		if( $this->verify_min_level(6) )
		{
			echo $this->load->view('security/page_header', '', TRUE);
			echo $this->load->view('security/employee', '', TRUE);
			echo $this->load->view('security/page_footer', '', TRUE);
		}
		else
		{	
			// Set redirect protocol
			$redirect_protocol = USE_SSL ? 'https' : NULL;
			redirect( site_url( LOGIN_PAGE . '?logout=0', $redirect_protocol ) );
		}
	}
	
	
	// -----------------------------------------------------------------------

	/**
	 * Here we simply verify if a user is logged in, but
	 * not enforcing authentication. The presence of auth 
	 * related variables that are not empty indicates 
	 * that somebody is logged in. Also showing how to 
	 * get the contents of the HTTP user cookie.
	 */
	public function simple_verification()
	{
		$this->is_logged_in();

		echo $this->load->view('security/page_header', '', TRUE);

		echo '<p>';
		if( ! empty( $this->auth_role ) )
		{
			echo $this->auth_role . ' logged in!<br />
				User ID is ' . $this->auth_user_id . '<br />
				Auth level is ' . $this->auth_level . '<br />
				Username is ' . $this->auth_username;

			if( $http_user_cookie_contents = $this->input->cookie( config_item('http_user_cookie_name') ) )
			{
				$http_user_cookie_contents = unserialize( $http_user_cookie_contents );
				
				echo '<br />
					<pre>';

				print_r( $http_user_cookie_contents );

				echo '</pre>';
			}

			if( config_item('add_acl_query_to_auth_functions') && $this->acl )
			{
				echo '<br />
					<pre>';

				print_r( $this->acl );

				echo '</pre>';
			}

			/**
			 * ACL usage doesn't require ACL be added to auth vars.
			 * If query not performed during authentication, 
			 * the acl_permits function will query the DB.
			 */
			if( $this->acl_permits('general.secret_action') )
			{
				echo '<p>ACL permission grants action!</p>';
			}
		}
		else
		{
			echo 'Nobody logged in.';
		}

		echo '</p>';

		echo $this->load->view('security/page_footer', '', TRUE);
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Most minimal user creation. You will of course make your
	 * own interface for adding users, and you may even let users
	 * register and create their own accounts.
	 *
	 * The password used in the $user_data array needs to meet the
	 * following default strength requirements:
	 *   - Must be at least 8 characters long
	 *   - Must be at less than 72 characters long
	 *   - Must have at least one digit
	 *   - Must have at least one lower case letter
	 *   - Must have at least one upper case letter
	 *   - Must not have any space, tab, or other whitespace characters
	 *   - No backslash, apostrophe or quote chars are allowed
	 */
	public function create_user()
	{
		if ( $this->verify_min_level(9) )
        {
        // Get Elements from Form
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $to_email = $this->input->post('email');
            $department = $this->input->post('department');
            $auth_level = $this->input->post('auth_level');

        // Customize this array for your user
        $user_data = [
            'username'   => $to_email,
            'passwd'     => 'Temporary1',
            'email'      => $to_email,
            'auth_level' => $auth_level,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'department' => $department,
        ];


        echo $this->load->view('security/page_header', '', TRUE);
        

        // Load resources
        $this->load->helper('auth');
        $this->load->model('security/security_model');
        $this->load->model('security/validation_callables');
        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $validation_rules = [
            [
                'field' => 'username',
                'label' => 'username',
                'rules' => 'max_length[35]|is_unique[' . db_table('user_table') . '.username]',
                'errors' => [
                    'is_unique' => 'Username already in use.'
                ]
            ],

            [
                'field' => 'first_name',
                'label' => 'first_name',
                'rules' => 'max_length[35]',
                'errors' => [
                    'max_length' => 'Name must be under 35 characters long.'
                ]
            ],
            [
                'field' => 'passwd',
                'label' => 'passwd',
                'rules' => [
                    'trim',
                    'required',
                    [ 
                        '_check_password_strength', 
                        [ $this->validation_callables, '_check_password_strength' ] 
                    ]
                ],
                'errors' => [
                    'required' => 'The password field is required.'
                ]
            ],
            [
                'field'  => 'email',
                'label'  => 'email',
                'rules'  => 'trim|required|valid_email|is_unique[' . db_table('user_table') . '.email]',
                'errors' => [
                    'is_unique' => 'Email address already in use.'
                ]
            ],
            [
                'field' => 'auth_level',
                'label' => 'auth_level',
                'rules' => 'required|integer|in_list[1,6,9]'
            ]
        ];

                 $config = Array(        
            // 'protocol' => 'sendmail',
            // 'smtp_host' => 'ssl://smtp.gmail.com',
            // 'smtp_port' => 25,
            // 'smtp_user' => 'aaronwilsonphoto@gmail.com',
            // 'smtp_pass' => '@Cages01',
            // 'smtp_timeout' => '4',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );
        $this->load->library('email', $config);

        $from_email = "aaronwilsonprofessional@gmail.com"; 

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . db_table('user_table') . '.email]');

        $to_email = $this->input->post('email');
        $department = $this->input->post('department');
        $employee_type = $this->input->post('optradio');

        $this->email->from($from_email, 'LLPA Employee Network'); 
        $this->email->to($to_email);
        $this->email->subject('Employee Network Invitation');

        // Get Elements from Form
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $to_email = $this->input->post('email');
            $department = $this->input->post('department');
            $auth_level = $this->input->post('auth_level');

        // Customize this array for your user
        $user_data = [
            'username'   => $to_email,
            'passwd'     => 'Aw907074',
            'email'      => $to_email,
            'auth_level' => $auth_level,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'department' => $department,
            'auth_level' => $auth_level,
        ];

        $body = $this->load->view('emails/email_invite.php',$user_data,TRUE);
        $this->email->message($body);

        $this->email->send();



        if( $this->form_validation->run() == FALSE )
        {
            echo $this->load->view('security/page_header', '', TRUE);
            echo $this->load->view('security/myform', '', TRUE);
            echo $this->load->view('security/page_footer', '', TRUE);
        }
        else
        {
            $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
            $user_data['user_id']    = $this->security_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['username'] ) )
            {
                $user_data['username'] = NULL;
            }

            $this->db->set($user_data)
                ->insert(db_table('user_table'));

            if( $this->db->affected_rows() == 1 ) {
                echo '<div class="position"><div class ="container"><h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p></div></div>';
                echo $this->load->view('security/page_header', '', TRUE);
                echo $this->load->view('security/formsuccess', '', TRUE);
                echo $this->load->view('security/page_footer', '', TRUE);
            }
        }

        echo $this->load->view('security/page_footer', '', TRUE);
        } /*End Admin Check - verify_min_level(9)*/

        elseif( $this->verify_min_level(6) )
            {
                echo $this->load->view('security/page_header', '', TRUE);
                echo $this->load->view('security/employee', '', TRUE);
                echo $this->load->view('security/page_footer', '', TRUE);
            }
        else
            {       
                redirect( site_url( LOGIN_PAGE . '?logout=0', $redirect_protocol ) );
            }
	    }
	
	// -----------------------------------------------------------------------

	/**
	 * This login method only serves to redirect a user to a 
	 * location once they have successfully logged in. It does
	 * not attempt to confirm that the user has permission to 
	 * be on the page they are being redirected to.
	 */
	public function login()
	{
		// Method should not be directly accessible
		if( $this->uri->uri_string() == 'security/login')
			show_404();

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
			$this->require_min_level(1);

		$this->setup_login_form();

		$html = $this->load->view('security/page_header', '', TRUE);
		$html .= $this->load->view('security/login_form', '', TRUE);
		$html .= $this->load->view('security/page_footer', '', TRUE);

		echo $html;
	}

	// --------------------------------------------------------------

	/**
	 * Log out
	 */
	public function logout()
	{
		$this->authentication->logout();

		// Set redirect protocol
		$redirect_protocol = USE_SSL ? 'https' : NULL;

		redirect( site_url( LOGIN_PAGE . '?logout=1', $redirect_protocol ) );
	}

	// --------------------------------------------------------------

	/**
	 * User recovery form
	 */
	public function recover()
	{
		// Load resources
		$this->load->model('security/security_model');

		/// If IP or posted email is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// If the form post looks good
			if( $this->tokens->match && $this->input->post('email') )
			{
				if( $user_data = $this->security_model->get_recovery_data( $this->input->post('email') ) )
				{
					// Check if user is banned
					if( $user_data->banned == '1' )
					{
						// Log an error if banned
						$this->authentication->log_error( $this->input->post('email', TRUE ) );

						// Show special message for banned user
						$view_data['banned'] = 1;
					}
					else
					{
						/**
						 * Use the authentication libraries salt generator for a random string
						 * that will be hashed and stored as the password recovery key.
						 * Method is called 4 times for a 88 character string, and then
						 * trimmed to 72 characters
						 */
						$recovery_code = substr( $this->authentication->random_salt() 
							. $this->authentication->random_salt() 
							. $this->authentication->random_salt() 
							. $this->authentication->random_salt(), 0, 72 );

						// Update user record with recovery code and time
						$this->security_model->update_user_raw_data(
							$user_data->user_id,
							[
								'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
								'passwd_recovery_date' => date('Y-m-d H:i:s')
							]
						);

						// Set the link protocol
						$link_protocol = USE_SSL ? 'https' : NULL;

						// Set URI of link
						$link_uri = 'security/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;

						$view_data['special_link'] = anchor( 
							site_url( $link_uri, $link_protocol ), 
							site_url( $link_uri, $link_protocol ), 
							'target ="_blank"' 
						);

						$view_data['confirmation'] = 1;
					}
				}

				// There was no match, log an error, and display a message
				else
				{
					// Log the error
					$this->authentication->log_error( $this->input->post('email', TRUE ) );

					$view_data['no_match'] = 1;
				}
			}
		}

		echo $this->load->view('security/page_header', '', TRUE);

		echo $this->load->view('security/recover_form', ( isset( $view_data ) ) ? $view_data : '', TRUE );

		echo $this->load->view('security/page_footer', '', TRUE);
	}

	// --------------------------------------------------------------

	/**
	 * Verification of a user by email for recovery
	 * 
	 * @param  int     the user ID
	 * @param  string  the passwd recovery code
	 */
	public function recovery_verification( $user_id = '', $recovery_code = '' )
	{
		/// If IP is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// Load resources
			$this->load->model('security/security_model');

			if( 
				/**
				 * Make sure that $user_id is a number and less 
				 * than or equal to 10 characters long
				 */
				is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

				/**
				 * Make sure that $recovery code is exactly 72 characters long
				 */
				strlen( $recovery_code ) == 72 &&

				/**
				 * Try to get a hashed password recovery 
				 * code and user salt for the user.
				 */
				$recovery_data = $this->security_model->get_recovery_verification_data( $user_id ) )
			{
				/**
				 * Check that the recovery code from the 
				 * email matches the hashed recovery code.
				 */
				if( $recovery_data->passwd_recovery_code == $this->authentication->check_passwd( $recovery_data->passwd_recovery_code, $recovery_code ) )
				{
					$view_data['user_id']       = $user_id;
					$view_data['username']     = $recovery_data->username;
					$view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
				}

				// Link is bad so show message
				else
				{
					$view_data['recovery_error'] = 1;

					// Log an error
					$this->authentication->log_error('');
				}
			}

			// Link is bad so show message
			else
			{
				$view_data['recovery_error'] = 1;

				// Log an error
				$this->authentication->log_error('');
			}

			/**
			 * If form submission is attempting to change password 
			 */
			if( $this->tokens->match )
			{
				$this->security_model->recovery_password_change();
			}
		}

		echo $this->load->view('security/page_header', '', TRUE);

		echo $this->load->view( 'security/choose_password_form', $view_data, TRUE );

		echo $this->load->view('security/page_footer', '', TRUE);
	}

	// --------------------------------------------------------------

	/**
	 * Attempt to login via AJAX
	 */
	public function ajax_login()
	{
		$this->is_logged_in();

		$this->tokens->name = 'login_token';

		$data['javascripts'] = [
			'https://code.jquery.com/jquery-1.12.0.min.js'
		];

		if( $this->authentication->on_hold === TRUE )
		{
			$data['on_hold_message'] = 1;
		}

		// This check for on hold is for normal login attempts
		else if( $on_hold = $this->authentication->current_hold_status() )
		{
			$data['on_hold_message'] = 1;
		}

		$link_protocol = USE_SSL ? 'https' : NULL;

		$data['final_head'] = "<script>
			$(document).ready(function(){
				$(document).on( 'submit', 'form', function(e){
					$.ajax({
						type: 'post',
						cache: false,
						url: '" . site_url('security/ajax_attempt_login', $link_protocol ) . "',
						data: {
							'login_string': $('#login_string').val(),
							'login_pass': $('#login_pass').val(),
							'login_token': $('[name=\"login_token\"]').val()
						},
						dataType: 'json',
						success: function(response){
							$('[name=\"login_token\"]').val( response.token );
							console.log(response);
							if(response.status == 1){
								$('form').replaceWith('<p>You are now logged in.</p>');
								$('#login-link').attr('href','" . site_url('security/logout', $link_protocol ) . "').text('Logout');
								$('#ajax-login-link').parent().hide();
							}else if(response.status == 0 && response.on_hold){
								$('form').hide();
								$('#on-hold-message').show();
								alert('You have exceeded the maximum number of login attempts.');
							}else if(response.status == 0 && response.count){
								alert('Failed login attempt ' + response.count + ' of ' + $('#max_allowed_attempts').val());
							}
						}
					});
					return false;
				});
			});
		</script>";

		$html = $this->load->view('security/page_header', $data, TRUE);
		$html .= $this->load->view('security/ajax_login_form', $data, TRUE);
		$html .= $this->load->view('security/page_footer', '', TRUE);

		echo $html;
	}

	// --------------------------------------------------------------

	/**
	 * Test for login via ajax
	 */
	public function ajax_attempt_login()
	{
		if( $this->input->is_ajax_request() )
		{
			// Allow this page to be an accepted login page
			$this->config->set_item('allowed_pages_for_login', ['security/ajax_attempt_login'] );

			// Make sure we aren't redirecting after a successful login
			$this->authentication->redirect_after_login = FALSE;

			// Do the login attempt
			$this->auth_data = $this->authentication->user_status( 0 );

			// Set user variables if successful login
			if( $this->auth_data )
				$this->_set_user_variables();

			// Call the post auth hook
			$this->post_auth_hook();

			// Login attempt was successful
			if( $this->auth_data )
			{
				echo json_encode([
					'status'   => 1,
					'user_id'  => $this->auth_user_id,
					'username' => $this->auth_username,
					'level'    => $this->auth_level,
					'role'     => $this->auth_role,
					'email'    => $this->auth_email
				]);
			}

			// Login attempt not successful
			else
			{
				$this->tokens->name = 'login_token';

				$on_hold = ( 
					$this->authentication->on_hold === TRUE OR 
					$this->authentication->current_hold_status()
				)
				? 1 : 0;

				echo json_encode([
					'status'  => 0,
					'count'   => $this->authentication->login_errors_count,
					'on_hold' => $on_hold, 
					'token'   => $this->tokens->token()
				]);
			}
		}

		// Show 404 if not AJAX
		else
		{
			show_404();
		}
	}
	
	// -----------------------------------------------------------------------

}

/* End of file Security.php */
