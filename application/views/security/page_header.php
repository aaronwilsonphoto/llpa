<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LLPA Employee Network</title>

	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Material Design Theming -->
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.orange-indigo.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet">

    <!-- Custom styles -->
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<?php
		// Add any javascripts
		if( isset( $javascripts ) )
		{
			foreach( $javascripts as $js )
			{
				echo '<script src="' . $js . '"></script>' . "\n";
			}
		}

		if( isset( $final_head ) )
		{
			echo $final_head;
		}
	?>
</head>
<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/login">LLPA Employee Network</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
		<?php
			$link_protocol = USE_SSL ? 'https' : NULL;

			if( $this->router->default_controller == 'security/home' )
			{
		?>
		<li>
			<?php echo anchor( site_url('', $link_protocol ),'Home'); ?>
		</li>
		<?php
			}
		?>
		<li>
		<?php
			if( isset( $auth_user_id ) ){
				echo anchor( site_url('security/logout', $link_protocol ),'Logout');
			}else{
				echo anchor( site_url(LOGIN_PAGE . '?redirect=security', $link_protocol ),'Login','id="login-link"');
			}
		?>
		</li>
          </ul>
        </div>
      </div>
    </nav>

<?php

/* End of file page_header.php */
/* Location: /views/security/page_header.php */