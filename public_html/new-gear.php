<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

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
		$added = true;
	}
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
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
	    <div class="row">
	        <div class="col-sm-6 col-sm-offset-3">
    			<?php echo "<a href=\"inventory.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Inventory</a>"; ?>
    			<br /><br />

	        	<?php if($added){ //USER ADDED AN ITEM
					echo "<div class=\"alert alert-success\" role=\"alert\">";
					printf("New Item, %s, Added!",$name);
					echo "</div>";	
				}?>
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

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>