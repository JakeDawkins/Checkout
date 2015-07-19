<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	//require_once("models/config.php"); //needed for funcs
	require_once('models/funcs.php'); //to fetch details about person


	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);
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

	<p>
	<?php
		printf("ID: %s<br />",$checkout->getID());
		printf("Title: <strong>%s</strong><br />",$checkout->getTitle());
		printf("Description: %s<br />",$checkout->getDescription());
		printf("Person: %s<br />",$checkout->getPerson());
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

</body>
</html>