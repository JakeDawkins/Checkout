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

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);

		if($_GET['delete']){
			Checkout::removeCheckout($co_id);
			$deleted = true;
		}
	}

	$checkout = new Checkout();
	$checkout->retrieveCheckout($co_id);
	$gearList = $checkout->getGearList();

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
                <h1>Checkout Details</h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br />

    <div class="container">
	    <div class="row">
    	    <div class="col-lg-12">
				<?php 
					if($deleted){
						echo "<h3>Checkout Deleted</h3>";
						echo "<a href='checkouts.php'><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a><br />";
					}
					else {
						echo "<a href=\"checkouts.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a>";
						printf("<h3>%s</h3>",$checkout->getTitle()); ?>
						<p>
						<?php
							printf("ID: %s<br />",$checkout->getID());
							printf("Description: %s<br />",$checkout->getDescription());

							$personName = getPersonName($checkout->getPerson());
							printf("Person: %s<br />",$personName);
							printf("Start Time: %s<br />",$checkout->getStart());
							printf("End Time: %s<br />",$checkout->getEnd());
						?>

						<h3>Associated Gear</h3>
						<?php
							foreach($gearList as $gear){
								$name = getGearName($gear);
								$type = getGearType($gear);
								$type = gearTypeWithID($type);
								printf("%s - %s<br />",$name,$type);
							}
						?>
						</p>

						<a class ="btn btn-danger" href='<?php printf("checkout.php?co_id=%s&delete=true",$co_id); ?>'>Delete</a>
				<?php } //end else ?>
    	    </div>
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