<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Gear.php');
	require_once('models/Form.php');
	require_once('models/Package.php');

	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$pkg_id = test_input($_GET['pkg_id']);
		$pkg = new Package();
		$pkg->retrievePackage($pkg_id);
	}

	//form submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){	
		$pkg_id = test_input($_POST['pkg_id']);
		$title = test_input($_POST['title']);
		$description = test_input($_POST['description']);
		$gearList = $_POST['gear'];

		$pkg = new Package();
		$pkg->retrievePackage($pkg_id);

		if(!empty($title) && $title != $pkg->getTitle()){
			$pkg->setTitle($title);	
		}
		if($description != $pkg->getDescription()){
			$pkg->setDescription($description);
		}
		foreach($gearList as $gear){
			$pkg->addToGearList($gear);
		}
		$pkg->finalizePackage();
		$successes[] = "Package updated";
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Edit Package</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Edit Gear Package",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <?php echo "<a href='package.php?pkg_id=" . $pkg_id . "'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Package Details</a>";
                echo "<br /><br />";
                echo resultBlock($errors,$successes); ?>

                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                	<input type="hidden" name="pkg_id" value="<?php echo $pkg_id; ?>" />
                	<h2>Package Details</h2>
					<hr />
					<div class="form-group"> <!-- TITLE -->
						<label class="control-label" for="title">Package Title:</label>  
						<input type="text" class="form-control" name="title" placeholder="<?php echo $pkg->getTitle(); ?>">
					</div>
					<div class="form-group"> <!-- DESC -->
						<label class="control-label" for="Description">Description:</label>  
						<textarea class="form-control" name="description" rows="3"><?php echo $pkg->getDescription(); ?></textarea>
					</div>

					<h2>Select Gear</h2>
					<p>Quantities are chosen at checkout based on what is available.</p>
					<hr />

					<?php
						$currGearList = $pkg->getGearList();

						$types = getGearTypes();
						foreach($types as $type){
							$items = getGearListWithType($type['gear_type_id']);
							echo "<h4>" . $type['type'] . "</h4>";
							foreach($items as $item){
								echo "<div class='checkbox'>";
								if(in_array($item['gear_id'], $currGearList))
									echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "' checked> " . $item['name'];
								else 
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
