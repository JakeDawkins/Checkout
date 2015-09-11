<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
	$deletions = $_POST['delete'];
	if ($deletion_count = deleteUsers($deletions)){
		$successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
	}
	else {
		$errors[] = lang("SQL_ERROR");
	}
}

$userData = fetchAllUsers(); //Fetch information for all users
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Admin Users</title>
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
				  	<li role="presentation"><a href="account.php">Home</a></li>
				  	<li role="presentation"><a href="user_settings.php">User Settings</a></li>
				  	<?php
				  	//Links for permission level 2 (default admin)
					if ($loggedInUser->checkPermission(array(2))): ?>
						<li role='presentation'><a href='admin_configuration.php'>Admin Configuration</a></li>
						<li class="active" role='presentation'><a href='admin_users.php'>Admin Users</a></li>
						<li role='presentation'><a href='admin_permissions.php'>Admin Permissions</a></li>
						<li role='presentation'><a href='admin_pages.php'>Admin Pages</a></li>
					<?php endif; ?>
				</ul>
	    	</div>
	    	<div class="col-sm-9">
				<?php echo resultBlock($errors,$successes); ?>
				
				<?php echo "<form name='adminUsers' action='".$_SERVER['PHP_SELF']."' method='post'>";?>
				<table class="table table-hover" class='admin'>
				<tr>
					<th>Delete</th><th>Username</th><th>Display Name</th><th>Title</th><th>Last Sign In</th>
				</tr>

				<?php 
				//Cycle through users
				foreach ($userData as $v1) {
					echo "
					<tr>
						<td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
						<td><a href='admin_user.php?id=".$v1['id']."'>".$v1['user_name']."</a></td>
						<td>".$v1['display_name']."</td>
						<td>".$v1['title']."</td>
						<td>
							";
							
							//Interprety last login
							if ($v1['last_sign_in_stamp'] == '0'){
								echo "Never";	
							}
							else {
								echo date("j M, Y", $v1['last_sign_in_stamp']);
							}
							echo "
						</td>
					</tr>";
				}

				echo "
				</table>
				<input class=\"btn btn-danger\" type='submit' name='Submit' value='Delete' />
				</form>"; 

				?>
	    	</div><!-- end row -->
		</div>
	</div> <!-- end container -->

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
