<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$userId = $_GET['id'];

//Check if selected user exists
if(!userIdExists($userId)){
	header("Location: admin_users.php"); die();
}

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
if(!empty($_POST)){	
	//Delete selected account
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteUsers($deletions)) {
			$successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}
	else {
		//Update display name
		if ($userdetails['display_name'] != $_POST['display']){
			$displayname = trim($_POST['display']);
			
			//Validate display name
			if(displayNameExists($displayname)) {
				$errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
			} elseif(minMaxRange(5,25,$displayname)) {
				$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
			} elseif(!ctype_alnum($displayname)) {
				$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
			} else {
				if (updateDisplayName($userId, $displayname)){
					$successes[] = lang("ACCOUNT_DISPLAYNAME_UPDATED", array($displayname));
				} else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		} else {
			$displayname = $userdetails['display_name'];
		}
		
		//Activate account
		if(isset($_POST['activate']) && $_POST['activate'] == "activate"){
			if (setUserActive($userdetails['activation_token'])){
				$successes[] = lang("ACCOUNT_MANUALLY_ACTIVATED", array($displayname));
			} else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		//Update email
		if ($userdetails['email'] != $_POST['email']){
			$email = trim($_POST["email"]);
			
			//Validate email
			if(!isValidEmail($email)) {
				$errors[] = lang("ACCOUNT_INVALID_EMAIL");
			} elseif(emailExists($email)) {
				$errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
			} else {
				if (updateEmail($userId, $email)){
					$successes[] = lang("ACCOUNT_EMAIL_UPDATED");
				} else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		
		//Update title
		if ($userdetails['title'] != $_POST['title']){
			$title = trim($_POST['title']);
			
			//Validate title
			if(minMaxRange(1,50,$title)) {
				$errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT",array(1,50));
			} else {
				if (updateTitle($userId, $title)){
					$successes[] = lang("ACCOUNT_TITLE_UPDATED", array ($displayname, $title));
				} else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		
		//Remove permission level
		if(!empty($_POST['removePermission'])){
			$remove = $_POST['removePermission'];
			if ($deletion_count = removePermission($remove, $userId)){
				$successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
			} else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		if(!empty($_POST['addPermission'])){
			$add = $_POST['addPermission'];
			if ($addition_count = addPermission($add, $userId)){
				$successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
			} else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		$userdetails = fetchUserDetails(NULL, NULL, $userId);
	}
}

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Users</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Users</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
    	<div class="row">
	    	<div class="col-sm-8 col-sm-offset-2">
				<?php 
				echo resultBlock($errors,$successes);

				echo "<a href=\"admin_users.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Users</a>"; 
    			echo "<br /><br />";

				echo "<form class='' role='form' name='adminUser' action='".$_SERVER['PHP_SELF']."?id=".$userId."' method='post'>"; ?>			
		    		<div class="panel panel-default">
		    			<div class="panel-heading">
							User Information
		    			</div>
		    			<div class="panel-body">
							<div class="form-group">
								<label class="control-label">ID:</label>
								<?php echo $userdetails['id']; ?>
							</div>
							<div class="form-group">
								<label class="control-label">Username:</label>
								<?php echo $userdetails['user_name']; ?>
							</div>
							<div class="form-group">
								<label class="control-label">Display Name:</label>
								<?php echo "<input class='form-control' type='text' name='display' value='".$userdetails['display_name']."' />"; ?>
							</div>
							<div class="form-group">
								<label class="control-label">Email:</label>
								<?php echo "<input class='form-control' type='text' name='email' value='".$userdetails['email']."' />"; ?>
							</div>
							<div class="form-group">
								<label class="control-label">Title:</label>
								<?php echo "<input class='form-control' type='text' name='title' value='".$userdetails['title']."' />"; ?> 
							</div>
							<div class="form-group">
								<label class="control-label">Active:</label>

								<?php 
								//Display activation link, if account inactive
								if ($userdetails['active'] == '1'){
									echo "Yes";	
								}
								else{
									echo "No";
								?>
							</div>
							<div class="form-group">
								<label class="control-label">Activate:</label>
								<?php 
								echo "<div class=\"checkbox\">";
  									echo "<label><input type='checkbox' name='activate' id='activate' value='activate'> Activate</label>";
								echo "</div>";

								//echo "<input type='checkbox' name='activate' id='activate' value='activate'>";								
								} //end else ?>
							</div>
							<div class="form-group">
								<label class="control-label">Sign Up:</label>
								<?php echo date("j M, Y", $userdetails['sign_up_stamp']); ?>
							</div>
							<div class="form-group">
								<label class="control-label">Last Sign In:</label>
								<?php 
								//Last sign in, interpretation
								if ($userdetails['last_sign_in_stamp'] == '0'){
									echo "Never";	
								}
								else {
									echo date("j M, Y", $userdetails['last_sign_in_stamp']);
								}
								?>
							</div>
							<div class="form-group">
								<label class="control-label">Delete:</label>
								<?php echo "<input type='checkbox' name='delete[".$userdetails['id']."]' id='delete[".$userdetails['id']."]' value='".$userdetails['id']."'>"; ?>
							</div>
		    			</div> <!-- End panel body --> 
	    			</div><!-- End panel --> 
	    			<div class="panel panel-default">
	    				<div class="panel-heading">
	    					Permission Membership
	    				</div>
	    				<div class="panel-body">
	    					<div class="form-group">
	    					<label class="control-label">Remove Permission:</label>
								<?php
								//List of permission levels user is apart of
								foreach ($permissionData as $v1) {
									if(isset($userPermission[$v1['id']])){
										echo "<div class=\"checkbox\">";
		  									echo "<label><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</label>";
										echo "</div>";						
										//echo "<br><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
									}
								} 
								?>
	    					</div>
	    					<div class="form-group">
		    					<label class="control-label">Add Permission:</label>
								<?php
									//List of permission levels user is not apart of
									foreach ($permissionData as $v1) {
										if(!isset($userPermission[$v1['id']])){
										echo "<div class=\"checkbox\">";
		  									echo "<label><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</label>";
										echo "</div>";	
											//echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
										}
									}
								?>
	    					</div>
	    				</div> <!-- end panel body --> 
	    			</div> <!-- end panel --> 	
	    			<input class="btn btn-success btn-block" type='submit' value='Update' class='submit' />
				</form>
	    	</div> <!-- end col --> 
    	</div> <!-- end row -->
	</div> <!-- end container --> 
	
	<br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>