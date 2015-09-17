<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Gear.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$type = "";

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = test_input($_POST['type']);

		//user provided a new type
		if (!empty($type)){
			if(!in_array($type, $types)) newGearType($type);
			$added = true;
		}

		$typesRemoved = array();
		if(!empty($_POST['deleteTypes'])){
			foreach ($_POST['deleteTypes'] as $deleteType) {
				$typesRemoved[] = gearTypeWithId($deleteType);
				deleteGearType($deleteType);
			}
			$removed = true;
		}
		
		//newGearItem($name,$category);
	}

	//------------------------ Validation ------------------------

	//only check when submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //submitted

	} //if submitted


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Edit Gear Types</title>
</head>
<body>

	<?php
		// if (isset($error)){
		// 	echo '<p class="error">';
		// 	if (isset($error['date'])) printf("%s<br />",$error['date']);
		// 	echo '</p>';
		// }
	?>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Edit Gear Types</h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />
    <div class="container">
        <div class="row">
            <div class="col-md-6">
    			<?php echo "<a href=\"inventory.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Inventory</a>"; ?>
    			<br /><br />

				<?php if($added){ //USER ADDED A GROUP
					echo "<div class=\"alert alert-success\" role=\"alert\">";
					printf("New Gear Type, %s, Created!",$type);
					echo "</div>";	
				}?>

            	<div class="panel panel-default">
            		<div class="panel-heading">Add a New Gear Type</div>
            		<div class="panel-body">
						<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
							<div class="form-group">
								<label class="control-label" for="type">New Type Name:</label>
								<input class="form-control" name="type" type="text" />
							</div>
							<br />
							<input class="btn btn-success" type="submit" name="submit" value="Submit" />
						</form>
            		</div>
            	</div><!-- end panel -->     
            </div>
            <div class="col-md-6">
				<?php if($removed){ //USER REMOVED A GROUP
					echo "<div class=\"alert alert-success\" role=\"alert\">";
					printf("The following were removed:<br />",$type);
					foreach($typesRemoved as $typeRemoved){
						printf("- %s<br />",$typeRemoved);
					}
					echo "</div>";	
				}?>
            	<div class="panel panel-default">
            		<div class="panel-heading">Remove Gear Types</div>
            		<div class="panel-body">
						<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
							<?php
								$types = getGearTypes();
								foreach($types as $type){
									echo "<div class=\"checkbox\">";
										printf("<label><input type=\"checkbox\" name=\"deleteTypes[]\" value=\"%s\">%s</label>",$type['gear_type_id'],$type['type']);
									echo "</div>";
								}
							?>
							<br />
							<input class="btn btn-danger" type="submit" name="submit" value="Delete Selected">
						</form>
            		</div>
        		</div><!-- end panel --> 
            </div><!-- end col --> 
        </div><!-- /.row -->
    </div><!-- /.container -->

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
