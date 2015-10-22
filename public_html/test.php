<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
    require_once('models/Gear-class.php');
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



</body>
</html>
