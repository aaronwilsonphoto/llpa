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


	<div class="position">
	      <div class = "container">
	        <div class="wrapper">
	          <div class="form-invite"> 
	              <h3 class="form-signin-heading">Employee Network Invitation</h3>

		<?php echo form_open('form'); ?>

        <div class="form-group">
          <h5><label>Employee Name</label></h5>
          <input type="txt" name="first_name" class="form-control" placeholder="First Name" value="<?php echo set_value('first_name'); ?>" />
          <?php echo form_error('first_name'); ?>
          <input type="txt" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo set_value('last_name'); ?>" />
          <?php echo form_error('last_name'); ?>
        </div>

			<div class="form-group">
			<h5><label for="sel1">Department</label></h5>
			<select name="department" class="form-control input-lg" id="sel1" >
		    <option value="Human Resources" <?php echo set_select('department', 'Human Resources', TRUE); ?> >Human Resources</option>
		    <option value="Husbandry" <?php echo set_select('department', 'Husbandry'); ?> >Husbandry</option>
			<option value="Philanthropy" <?php echo set_select('department', 'Philanthropy'); ?> >Philanthropy</option>
            <option value="Education" <?php echo set_select('department', 'Education'); ?> >Education</option>
            <option value="Marketing" <?php echo set_select('department', 'Marketing'); ?> >Marketing</option>
            <option value="Guest Services" <?php echo set_select('department', 'Guest Services'); ?> >Guest Services</option>
			</select>
			</div>

			<div class="form-group">
			<h5><label>Authentication Level</label></h5>
			<select name="auth_level" class="form-control input-lg" id="auth_level" >
		    <option value="6" <?php echo set_select('auth_level', '6', TRUE); ?> >Employee</option>
		    <option value="9" <?php echo set_select('auth_level', '9'); ?> >Admin</option>
			</select>
			</div>


										
			<h5><label>Employee Email Address</label></h5>
			<input type="email" name="email" class="form-control" placeholder="email@example.com" value="<?php echo set_value('email'); ?>" />
 			<?php echo '<br /><br /><div class="position"><div class ="container">' . validation_errors () . '</div></div>'; ?>
			<div><br /><input class="btn btn-action" type="submit" value="SEND MAIL" /></div>

				</form>
	          </div>     
	        </div>
	      </div>
	</div>  <!-- end class position -->