<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
	require_once('models/funcs.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$type = "";

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//NEW GEAR TYPE
		if (!empty($_POST['type'])){
			$type = test_input($_POST['type']);	
			if(!in_array($type, $types)){
				newGearType($type);	
				$successes[] = "New gear type, " . $type . ", added";
			} else {
				$errors[] = "Gear type cannot be added. It already exists";
			}
		} elseif(!empty($_POST['deleteTypes'])){
			foreach ($_POST['deleteTypes'] as $deleteType) {
				deleteGearType($deleteType);
			}
			$successes[] = "Gear types removed";
		} elseif(!empty($_POST['rename'])){
			$type = test_input($_POST['rename']);
			if(empty($_POST['newName'])){
				$errors[] = "Cannot rename. No new name provided";
			} else {
				$newName = test_input($_POST['newName']);
				renameGearType($type, $newName);
				$successes[] = "Gear type renamed to " . $newName;	
			}
		} else {
			$errors[] = "Nothing done";
		}
	}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Edit Gear Types</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Edit Gear Types",NULL); ?>

    <div class="container">
    	<div class="row">
    		<div class="col-sm-12">
			    <?php echo resultBlock($errors,$successes); ?>
		    </div>
	    </div>

        <div class="row">
            <div class="col-sm-6">
    			<?php echo "<a href='inventory.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Inventory</a>"; ?>
    			<br /><br />
            	<div class="panel panel-default">
            		<div class="panel-heading">Add a New Gear Type</div>
            		<div class="panel-body">
						<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
							<div class="form-group">
								<label class="control-label" for="type">New Type Name:</label>
								<input class="form-control" name="type" type="text" />
							</div>
							<input class="btn btn-success" type="submit" name="submit" value="Submit" />
						</form>
            		</div>
            	</div><!-- end panel -->     
            	<div class="panel panel-default">
		            <div class="panel-heading">Rename Gear Types</div>
		            <div class="panel-body">
			            <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
							<div class="form-group">
								<label for="rename">Select a type to rename:</label>
								<select class="form-control" id="rename" name="rename">
									<?php
										$types = getGearTypes();
										foreach($types as $type){
											printf("<option value='%s'>%s</option>",$type['gear_type_id'],$type['type']);
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="newName">Rename to:</label>
								<input class="form-control" name="newName" type="text" />
							</div>
							<input class="btn btn-success" type="submit" name="submit" value="Submit" />
						</form>
		            </div>
	            </div><!-- end panel -->
            </div><!-- end col -->
            <div class="col-sm-6">
            	<div class="panel panel-default">
            		<div class="panel-heading">Remove Gear Types</div>
            		<div class="panel-body">
						<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
							<?php
								$types = getGearTypes();
								foreach($types as $type){
									echo "<div class='checkbox'>";
										printf("<label><input type='checkbox' name='deleteTypes[]' value='%s'>%s</label>",$type['gear_type_id'],$type['type']);
									echo "</div>";
								}
							?>
							<input class="btn btn-danger" type="submit" name="submit" value="Delete Selected">
						</form>
            		</div>
        		</div><!-- end panel --> 
            </div><!-- end col --> 
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>