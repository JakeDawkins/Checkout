<?php
	//require_once('PHP/Gear.php');
	require_once('PHP/Checkout.php');
	require_once('PHP/Person.php');

	//get list of checkouts
	$checkouts = Checkout::getCheckoutsInMonth();


?>

<!DOCTYPE html>
<html>
<head>
	<title>
		View Checkouts
	</title>
</head>
<body>
	<h1>Checkouts</h1>

	<table>
	<tr>
		<td>co_id</td>
		<td>name</td>
		<td>description</td>
		<td>person</td>
		<td>start</td>
		<td>end</td>
		<td>gear</td>
	</tr>

	<?php



	?>
	</table>





</body>
</html>