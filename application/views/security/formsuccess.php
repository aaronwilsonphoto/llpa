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

?>

<br />
    <div class="position">
	      <div class = "container">
	        <div class="wrapper">
	          <div class="form-invite">

				<!-- Media middle -->
				<div class="media">
				  <div class="media-left media-top">
				    <img src="<?php echo base_url(); ?>images/email.png" class="media-object" style="width:50px">
				  </div>
				  <div class="media-body">
				    <h4 class="media-heading" style="margin-top: 10px; margin-bottom:30px">Email Invitation Sent To: <?php echo $_POST["email"]; ?></h4>
				  </div>
				</div>

				<p class="accountdetails-heading">Account Summary</p>
				<p class="accountdetails">Employee Name: <?php echo $_POST["first_name"]; ?> <?php echo $_POST["last_name"]; ?>
				<br />Email Address: <?php echo $_POST["email"]; ?>
				<br />Department: <?php echo $_POST["department"]; ?>
				</p>

				<p><?php echo anchor('security/create_user', 'Send Another Invite?'); ?><br /><?php echo anchor('security', 'Return to Dashboard'); ?></p>
	          </div>     
	        </div>
	      </div>
	</div>  <!-- end class position -->


