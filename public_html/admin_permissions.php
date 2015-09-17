<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST)) {
	//Delete permission levels
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deletePermission($deletions)){
		$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
	}

	//Create new permission level
	if(!empty($_POST['newPermission'])) {
		$permission = trim($_POST['newPermission']);

		//Validate request
		if (permissionNameExists($permission)){
			$errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
		}
		elseif (minMaxRange(1, 50, $permission)){
			$errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));
		}
		else{
			if (createPermission($permission)) {
			$successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
		}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}//if
}//form posted

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Permissions</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Permissions</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
    	<div class="row">
    		<div class="col-sm-8 col-sm-offset-2">
				<?php echo resultBlock($errors,$successes);
				echo "<form role='form' name='adminPermissions' action='".$_SERVER['PHP_SELF']."' method='post'>"; ?>
					<table class='table table-hover'>
					<tr>
						<th>Delete</th><th>Permission Name</th>
					</tr>

					<?php
						//List each permission level
						foreach ($permissionData as $v1) {
							echo "
							<tr>
							<td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
							<td><a href='admin_permission.php?id=".$v1['id']."'>".$v1['name']."</a></td>
							</tr>";
						}
					?>

					</table>
					<div class="form-group"> 
						<label class="control-label">New Permission:</label>
						<input class="form-control" type='text' name='newPermission' placeholder='Name'/>
					</div>

					<input class="btn btn-primary" type='submit' name='Submit' value='Submit' />

				</form>
    		</div> <!-- end col -->
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


