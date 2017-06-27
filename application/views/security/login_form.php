<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

if( ! isset( $optional_login ) )
{
	
}

if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div style="border:1px solid red; width:60%; margin-left:20%;">
				<p>
					Login Error #' . $this->authentication->login_errors_count . '/' . config_item('max_allowed_attempts') . ': Please Check Email Address and Password.
				</p>
				<p>
					Email address and password are both case sensitive.
				</p>
			</div>
		';
	}

	if( $this->input->get('logout') )
	{
	$message = "You have been logged out";
	echo "<script type='text/javascript'>alert('$message');</script>";
	}

	echo form_open( $login_url, ['class' => 'std-form'] ); 
?>

	<div>
	<div class="filler"></div>

		    <div class="row">
		      <div class="col-md-12">
		        <div class="page-header">
		          <img class="header-image" alt="LLPA Logo" src="<?php echo base_url(); ?>images/logo.png" >
		        </div>
		      </div> 
		    </div>


		    <div class="position">
		          <div class = "container">
		            <div class="wrapper">
		              <div class="form-signin"> 
		                  <h3 class="form-signin-heading">Employee Login</h3>

			                <label for="login_string" class="form_label">Email</label>
							<input type="text" name="login_string" id="login_string" class="form-control" autocomplete="off" maxlength="255" placeholder="employee@email.com" required="" autofocus="" />

							<br />

							<label for="login_pass" class="form_label">Password</label>
							<input type="password" name="login_pass" id="login_pass" class="form-control password" <?php 
								if( config_item('max_chars_for_password') > 0 )
									echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
							?> autocomplete="off" placeholder="Password" required="" />
		                  
		                  	<input class="btn" type="submit" name="submit" value="Login" id="submit_button"  />
							<p>
								<?php
									$link_protocol = USE_SSL ? 'https' : NULL;
								?>
								<a class="login1" href="<?php echo site_url('security/recover', $link_protocol); ?>">
									Can't access your account?
								</a>
							</p>

				      </div>     
		            </div>
		          </div>
		    </div>  <!-- end class position -->

	</div>
</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
		<div class="position">
			<div class = "container">
				<div style="border:1px solid red;">
					<p>
						Excessive Login Attempts
					</p>
					<p>
						You have exceeded the maximum number of failed login<br />
						attempts that this website will allow.
					<p>
					<p>
						Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
					</p>
					<p>
						Please use the <a href="/security/recover">Account Recovery</a> after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
						or contact us if you require assistance gaining access to your account.
					</p>
				</div>
			</div>
		</div>
		';
	}

/* End of file login_form.php */
/* Location: /views/security/login_form.php */ 