<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	//require_once('model/Gear.php');
	//require_once('model/Checkout.php');
	require_once('models/Gear.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$name = $category = "";

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = test_input($_POST['name']);
		$category = test_input($_POST['category']);
		$newCategory = test_input($_POST['newCategory']);

		//user provided a new category
		if (!empty($newCategory)){
			$category = newGearType($newCategory);
		}

		newGearItem($name,$category);
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

	<title>New Gear Item</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>New Gear Item</h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

	<?php
		// if (isset($error)){
		// 	echo '<p class="error">';
		// 	if (isset($error['date'])) printf("%s<br />",$error['date']);
		// 	echo '</p>';
		// }
	?>

    <div class="container">
	    <div class="row">
	        <div class="col-lg-12">
				<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
					
					<div class="form-group">
						<label class="control-label" for="name">Name:</label>
						<input class="form-control" name="name" type="text" />
					</div>

					<div class="form-group">
						<label class="control-label" for="category">Choose a category:</label>
						<select class="form-control" name="category">
						<?php
							foreach($types as $type){
								printf("<option value=\"%s\">%s</option>",$type['gear_type_id'],$type['type']);
							}
						?>
						</select>
					</div>

					<div class="form-group">
						<label class="control-label" for="newCategory">Or create a new category:</label>
						<input class="form-control" name="newCategory" type="text" />
					</div>

					<input class="btn btn-success" type="submit" name="submit" value="Submit" />
				</form>
	        </div> <!-- END COL -->
        </div> <!-- END ROW --> 
    </div><!-- END CONTAINER -->
</body>
</html>
