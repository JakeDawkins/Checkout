<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	//require_once('model/db.php');
	require_once('models/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/Person.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>New Gear Package</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <?php echo "<a href='inventory.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Inventory</a>";
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
