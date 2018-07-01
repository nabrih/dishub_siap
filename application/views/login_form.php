<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon" />
    <title>SIAP :: Login</title>
        
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	body {
    		padding: 40px 0;
    		background: #eee;
    	}
    	.form-signin {
    		max-width: 330px;
    		padding: 15px;
    		margin: 0 auto;
    	}
  		.form-signin .form-signin-heading{
  			margin-bottom: 10px;
  			text-align: center;
  		}
  		.form-signin .form-control {
  			position: relative;
  			height: auto;
  			-webkit-box-sizing: border-box;
  			-moz-box-sizing: border-box;
  			box-sizing: border-box;
  			padding: 12px;
  			font-size: 16px;
  		}
  		.form-signin .form-control:focus {
  			z-index: 2;
  		}
    	.form-signin input[type="text"] {
    		margin-bottom: -1px;
    		border-bottom-right-radius: 0;
    		border-bottom-left-radius: 0;
    	}
    	.form-signin input[type="password"] {
    		margin-bottom: -1px;
    		border-top-left-radius: 0;
    		border-top-right-radius: 0;
    	}
    	button {margin-top: 20px}
    </style>
</head>
<body>
	<div class="container" style="max-width:340px">

      <!-- <form class="form-signin" method="post" action="<?php echo site_url(); ?>/home/login" role="form"> -->
      <?php echo form_open("auth/login", "class='form-signin'");?>

        <h2 class="form-signin-heading"><b>SIAP - Pulogadung</b></h2>
        <h3 class="form-signin-heading">LOGIN</h3>
        <?php echo validation_errors(); ?>
        <?php echo isset($message)?$message:"" ?>
        <input type="text" name="identity" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><b>LOGIN</b>&nbsp;&nbsp;<span class="glyphicon glyphicon-log-in"></span></button>
      </form>

    </div>
    <div style="text-align:center;margin-top:50px">
        <img src="<?php echo base_url(); ?>assets/img/dishubx.png" height="160px" style="margin-right:50px"/>
        <img src="<?php echo base_url(); ?>assets/img/jakarta.png" height="140px" />
    </div>

    <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>
