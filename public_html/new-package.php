<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
	require_once('models/funcs.php');
	require_once('models/Package.php');

	//form submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (empty($_POST['title'])){
		  $errors[] = "No title provided"; 
		} else $title = test_input($_POST['title']);

		if (empty($_POST['description'])){
			  $errors[] = "No description provided"; 
		} else $description = test_input($_POST['description']);

		if (empty($_POST['gear'])){
			$errors[] = "No gear added to package"; 
		} else $gearList = $_POST['gear'];

		if(empty($errors)){
			$pkg = new Package();

			$pkg->setTitle($title);
			$pkg->setDescription($description);
			foreach($gearList as $gear){
				$pkg->addToGearList($gear);
			}
			$pkg->finalizePackage();
			header("Location: package.php?pkg_id=" . $pkg->getID());
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>New Package</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("New Gear Package",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <?php echo "<a href='packages.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Packages</a>";
                echo "<br /><br />";
                echo resultBlock($errors,$successes); ?>

                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                	<h2>Package Details</h2>
					<hr />
					<div class="form-group"> <!-- TITLE -->
						<label class="control-label" for="title">Package Title:</label>  
						<input type="text" class="form-control" name="title">
					</div>
					<div class="form-group"> <!-- DESC -->
						<label class="control-label" for="Description">Description:</label>  
						<textarea class="form-control" name="description" rows="3"></textarea>
					</div>

					<h2>Select Gear</h2>
					<p>Quantities are chosen at checkout based on what is available.</p>
					<hr />

					<?php
						$types = getGearTypes();
						foreach($types as $type){
							$items = getGearListWithType($type['gear_type_id']);
							echo "<h4>" . $type['type'] . "</h4>";
							foreach($items as $item){
								echo "<div class='checkbox'>";
								echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "'> " . $item['name'];
								echo "</label></div>";	
							}
						}
					?>
					<br />
					<input class="btn btn-success" type="submit" name="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
