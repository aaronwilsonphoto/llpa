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

<h1>Admin Dashboard</h1>

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
	          <div class="form-invite"> 
	              <h3 class="form-signin-heading">Employee Network Invitation</h3>

					<?php echo form_open('form'); ?>

        <div class="form-group">
          <h5><label>Employee Name</label></h5>
          <input type="txt" name="firstname" class="form-control" placeholder="First Name" value="<?php echo set_value('firstname'); ?>" />
          <?php echo form_error('firstname'); ?>
          <input type="txt" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo set_value('lastname'); ?>" />
          <?php echo form_error('lastname'); ?>
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
            <h5><label>Access Level</label></h5>
              <label class="radio-inline"><input type="radio" name="optradio" value="Employee" >Employee</label>
              <label class="radio-inline"><input type="radio" name="optradio" value="Admin" >Admin</label>
              <?php echo form_error('optradio'); ?>
          </div>
										
					<h5><label>Employee Email Address</label></h5>
					<input type="email" name="email" class="form-control" placeholder="email@example.com" value="<?php echo set_value('email'); ?>" />
					<?php echo form_error('email'); ?>

          <h5><label>Confirm Email Address</label></h5>
          <input type="email" name="emailcnf" class="form-control" placeholder="email@example.com" value="<?php echo set_value('emailcnf'); ?>" />
          <?php echo form_error('emailcnf'); ?> 

					<div><br /><input class="btn btn-action" type="submit" value="SEND MAIL" /></div>

					</form>
	          </div>     
	        </div>
	      </div>
	</div>  <!-- end class position -->
