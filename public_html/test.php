<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');


	$qty = availableQty(136,"2015-01-01 00:00:01", "2015-01-03 00:00:01");
	echo "qty: $qty";

?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>

</body>
</html>