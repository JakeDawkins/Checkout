<?php
require_once("models/config.php"); //for usercake
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Account Home</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Account</h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
    	<div class="row">
	    	<div class="col-sm-3">
		    	<ul class="nav nav-pills nav-stacked">
				  	<li role="presentation" class="active"><a href="account.php">Home</a></li>
				  	<li role="presentation"><a href="user_settings.php">User Settings</a></li>
				  	<?php
				  	//Links for permission level 2 (default admin)
					if ($loggedInUser->checkPermission(array(2))): ?>
						<li role='presentation'><a href='admin_configuration.php'>Admin Configuration</a></li>
						<li role='presentation'><a href='admin_users.php'>Admin Users</a></li>
						<li role='presentation'><a href='admin_permissions.php'>Admin Permissions</a></li>
						<li role='presentation'><a href='admin_pages.php'>Admin Pages</a></li>
					<?php endif; ?>
				</ul>
	    	</div>
	    	<!-- Right Side Content -->
	    	<div class="col-sm-9">
	    	<?php echo "Hey, $loggedInUser->displayname. <br />This is an example secure page designed to demonstrate some of the basic features of UserCake. Just so you know, your title at the moment is $loggedInUser->title, and that can be changed in the admin panel. You registered this account on " . date("M d, Y", $loggedInUser->signupTimeStamp()) ?>
	    	</div>
    	</div>
    </div>

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>