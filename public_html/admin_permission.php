<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

$permissionId = $_GET['id'];

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
	header("Location: admin_permissions.php"); die();	
}

$permissionDetails = fetchPermissionDetails($permissionId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deletePermission($deletions)){
		$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
	} else {
		//Update permission level name
		if($permissionDetails['name'] != $_POST['name']) {
			$permission = trim($_POST['name']);
			
			//Validate new name
			if (permissionNameExists($permission)){
				$errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
			}
			elseif (minMaxRange(1, 50, $permission)){
				$errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updatePermissionName($permissionId, $permission)){
					$successes[] = lang("PERMISSION_NAME_UPDATE", array($permission));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		
		//Remove access to pages
		if(!empty($_POST['removePermission'])){
			$remove = $_POST['removePermission'];
			if ($deletion_count = removePermission($permissionId, $remove)) {
				$successes[] = lang("PERMISSION_REMOVE_USERS", array($deletion_count));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		//Add access to pages
		if(!empty($_POST['addPermission'])){
			$add = $_POST['addPermission'];
			if ($addition_count = addPermission($permissionId, $add)) {
				$successes[] = lang("PERMISSION_ADD_USERS", array($addition_count));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		//Remove access to pages
		if(!empty($_POST['removePage'])){
			$remove = $_POST['removePage'];
			if ($deletion_count = removePage($remove, $permissionId)) {
				$successes[] = lang("PERMISSION_REMOVE_PAGES", array($deletion_count));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		
		//Add access to pages
		if(!empty($_POST['addPage'])){
			$add = $_POST['addPage'];
			if ($addition_count = addPage($add, $permissionId)) {
				$successes[] = lang("PERMISSION_ADD_PAGES", array($addition_count));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
		$permissionDetails = fetchPermissionDetails($permissionId);
	}
}

$pagePermissions = fetchPermissionPages($permissionId); //Retrieve list of accessible pages
$permissionUsers = fetchPermissionUsers($permissionId); //Retrieve list of users with membership
$userData = fetchAllUsers(); //Fetch all users
$pageData = fetchAllPages(); //Fetch all pages
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Permissions</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Permissions",NULL); ?>

    <div class="container">
    	<div class="row">
    		<div class="col-sm-8 col-sm-offset-2">
    			<?php
				echo "<a href='admin_permissions.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Permissions</a>"; 
    			echo "<br /><br />";
				echo resultBlock($errors,$successes);
	    		echo "<form role='form' name='adminPermission' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "?id=".$permissionId."' method='post'>"; ?>
	        		<div class="panel panel-default">
			    		<div class="panel-heading">Permission Information</div>
			    		<div class="panel-body">
							<div class="form-group">
								<label class="control-label">ID:</label>
								<?php echo $permissionDetails['id'] ?>
							</div>
							<div class="form-group">
								<label class="control-label">Name:</label>
								<?php echo "<input type='text' name='name' value='".$permissionDetails['name']."' />"; ?>
							</div>
							<div class="form-group">
								<label class="control-label">Delete:</label>
								<?php echo "<input type='checkbox' name='delete[".$permissionDetails['id']."]' id='delete[".$permissionDetails['id']."]' value='".$permissionDetails['id']."'>"; ?>
							</div>
			    		</div>
		    		</div><!-- end panel -->
	        		<div class="panel panel-default">
			    		<div class="panel-heading">Permission Membership</div>
			    		<div class="panel-body">
			    			<h4>Remove Members</h4>
			    			<?php 
								//List users with permission level
								foreach ($userData as $v1) {
									if(isset($permissionUsers[$v1['id']])){
										echo "<div class=\"checkbox\">";
	  										echo "<label><input type=\"checkbox\" name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name']."</label>";
										echo "</div>";
									}
								}
							?>

							<hr />

							<h4>Add Members</h4>
							<?php
								//List users without permission level
								foreach ($userData as $v1) {
									if(!isset($permissionUsers[$v1['id']])){
										echo "<div class=\"checkbox\">";
	  										echo "<label><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name']."</label>";
										echo "</div>";
										//echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name'];
									}
								}
							?>
			    		</div>
		    		</div><!-- end panel -->
	        		<div class="panel panel-default">
			    		<div class="panel-heading">Permission Access</div>
			    		<div class="panel-body">
							<h4>Public Access:</h4>
							<?php 
							//List public pages
							foreach ($pageData as $v1) {
								if($v1['private'] != 1){
									echo "<div class='checkbox'>".$v1['page']."</div>";
								}
							}
							?>

							<hr />

							<h4>Remove Access:</h4>
							<?php 
							//List pages accessible to permission level
							foreach ($pageData as $v1) {
								if(isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
									echo "<div class=\"checkbox\">";
  										echo "<label><input type='checkbox' name='removePage[".$v1['id']."]' id='removePage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page']."</label>";
									echo "</div>";
									//echo "<br><input type='checkbox' name='removePage[".$v1['id']."]' id='removePage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page'];
								}
							}
							?>

							<hr />

							<h4>Add Access:</h4>
							<?php 
							//List pages inaccessible to permission level
							foreach ($pageData as $v1) {
								if(!isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
									echo "<div class=\"checkbox\">";
  										echo "<label><input type='checkbox' name='addPage[".$v1['id']."]' id='addPage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page']."</label>";
									echo "</div>";									
									//echo "<br><input type='checkbox' name='addPage[".$v1['id']."]' id='addPage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page'];
								}
							}
							?>
			    		</div>
		    		</div><!-- end panel -->
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