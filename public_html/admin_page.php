<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$pageId = $_GET['id'];

//Check if selected pages exist
if(!pageIdExists($pageId)){
	header("Location: admin_pages.php"); die();
}

$pageDetails = fetchPageDetails($pageId); //Fetch information specific to page

//Forms posted
if(!empty($_POST)){
	$update = 0;

	if(!empty($_POST['private'])){ $private = $_POST['private']; }

	//Toggle private page setting
	if (isset($private) AND $private == 'Yes'){
		if ($pageDetails['private'] == 0){
			if (updatePrivate($pageId, 1)){
				$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("private"));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}
	elseif ($pageDetails['private'] == 1){
		if (updatePrivate($pageId, 0)){
			$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("public"));
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}

	//Remove permission level(s) access to page
	if(!empty($_POST['removePermission'])){
		$remove = $_POST['removePermission'];
		if ($deletion_count = removePage($pageId, $remove)){
			$successes[] = lang("PAGE_ACCESS_REMOVED", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}

	//Add permission level(s) access to page
	if(!empty($_POST['addPermission'])){
		$add = $_POST['addPermission'];
		if ($addition_count = addPage($pageId, $add)){
			$successes[] = lang("PAGE_ACCESS_ADDED", array($addition_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}

	$pageDetails = fetchPageDetails($pageId);
}

$pagePermissions = fetchPagePermissions($pageId);
$permissionData = fetchAllPermissions();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Pages</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Pages</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />
    
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
				<?php 
				echo resultBlock($errors,$successes);
				echo "<a href=\"admin_pages.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Pages</a>"; 
    			echo "<br /><br />";				

				echo"<form role='form' name='adminPage' action='".$_SERVER['PHP_SELF']."?id=".$pageId."' method='post'>"; ?>
					<input type='hidden' name='process' value='1'>
							
						<div class="panel panel-default">
							<div class="panel-heading">Page Information</div>
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label">ID:</label>
									<?php echo $pageDetails['id']; ?>
								</div>
								<div class="form-group">
									<label class="control-label">Name:</label>
									<?php echo $pageDetails['page']; ?>
								</div>
								<div class="form-group">
									<label class="control-label">Private:</label>
									<?php 
									//Display private checkbox
									if ($pageDetails['private'] == 1){
										echo "<input type='checkbox' name='private' id='private' value='Yes' checked>";
									}
									else {
										echo "<input type='checkbox' name='private' id='private' value='Yes'>";
									}
									?>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">Page Access</div>
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label">Remove Access:</label>
									<?php 
									//Display list of permission levels with access
									foreach ($permissionData as $v1) {
										if(isset($pagePermissions[$v1['id']])){
											echo "<div class='checkbox'>";
												echo "<label><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</label>";
											echo "</div>";
										}
									}
									?>
									
								</div>
								<div class="form-group">
									<label class="control-label">Add Access:</label>
									<?php 
									//Display list of permission levels without access
									foreach ($permissionData as $v1) {
										if(!isset($pagePermissions[$v1['id']])){
											echo "<div class='checkbox'>";
												echo "<label><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name']."</label>";
											echo "</div>";
										}
									}
									?>
								</div>
							</div>
						</div>
					<input class="btn btn-success btn-block" type='submit' value='Update' class='submit' />
				</form>
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>