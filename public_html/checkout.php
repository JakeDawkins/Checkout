<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	//require_once("models/config.php"); //needed for funcs
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');

	$deleted = false;

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);

		if($_GET['delete']){
			Checkout::removeCheckout($co_id);
			$deleted = true;
		}
		//check to see if we need to delete the checkout

	}

	$checkout = new Checkout();
	$checkout->retrieveCheckout($co_id);
	$gearList = $checkout->getGearList();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Checkout</title>
</head>
<body>
	<h1>Checkout Details</h1>

	<?php 
		if($deleted){
			echo "<h3>Checkout Deleted</h3>";
			echo "<a href='checkouts.php'>Back to Checkouts</a><br />";
		}
		else {
			echo '<a href="checkouts.php">Back to Checkouts</a>';
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

			<a href='<?php printf("checkout.php?co_id=%s&delete=true",$co_id); ?>'>Delete</a>
		<?php } //end else ?>
	<!-- <a href='<?php printf("edit-checkout.php?co_id=%s",$co_id); ?>'>Edit</a> -->


</body>
</html>