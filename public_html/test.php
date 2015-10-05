<?php
	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');

	fetchCheckoutsWithGear(135);
	echo "<br /><br />";	
	fetchCheckoutsWithGear(136);
?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>

</body>
</html>