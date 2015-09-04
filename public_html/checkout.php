<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');

	$deleted = false;

	//check to see what checkout and if it needs to be deleted
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);
		if($_GET['delete']){
			Checkout::removeCheckout($co_id);
			$deleted = true;
		}
	}

	//pull checkout and gearlist
	$checkout = new Checkout();
	$checkout->retrieveCheckout($co_id);
	$gearList = $checkout->getGearList();

	//create array of gear types within this checkout
	$gearTypes = array();
	foreach($gearList as $gear){
		$type = gearTypeWithID(getGearType($gear));
		if (!in_array($type, $gearTypes)){
			$gearTypes[] = $type;
		}
	}//foreach

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

	<title>Checkout</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1><?php printf("%s",$checkout->getTitle()); ?></h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br />

    <div class="container">
	    <div class="row">
    	    <div class="col-lg-4 col-md-4 col-sm-4 text-center">
				<?php 
					if($deleted){
						echo "<h3>Checkout Deleted</h3>";
						echo "<a href='checkouts.php'><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a><br />";
					}
					else {
						echo "<a href=\"checkouts.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a>";
						printf("<h3>Details</h3><hr />"); ?>
						<p>
						<?php
							printf("<strong>Checkout ID:</strong> %s<br /><br />",$checkout->getID());
							printf("<strong>Description:</strong> %s<br /><br />",$checkout->getDescription());

							$personName = getPersonName($checkout->getPerson());
							printf("<strong>Person:</strong> %s<br /><br />",$personName);
							printf("<strong>Start Time:</strong> %s<br /><br />",$checkout->getStart());
							printf("<strong>End Time:</strong> %s<br /><br />",$checkout->getEnd());
							printf("<a href=\"checkout.php?co_id=%s&delete=true\">Delete This Checkout</a>",$co_id);
						?>
						<!-- <a class ="btn btn-danger btn-block" href='<?php printf("checkout.php?co_id=%s&delete=true",$co_id); ?>'>Delete</a>-->
						</p>
						
				<?php } //end else ?>
    	    </div>
    	    <!-- SECOND COL -->
    	    <div class="col-lg-8 col-md-8 col-sm-8 text-center">
				<h3>Associated Gear</h3>
				<hr />
				<?php
					foreach($gearTypes as $gearType){
						echo "<h3>$gearType</h3>";
						echo "<p>";
						foreach($gearList as $gear){
							$name = getGearName($gear);
							$type = gearTypeWithID(getGearType($gear));
							if($type == $gearType){
								echo "$name<br />";
							}
						}
						echo "</p><hr />";
					}
				?>
    	    </div>
	    </div><!-- END ROW -->
    </div>

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>