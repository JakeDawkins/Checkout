<?php
	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');

	$gearList = getGearList();
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Inventory
	</title>
</head>
<body>
	<h1>Inventory</h1>
	<?php include('templates/nav.php'); ?>
	<a href="newgear.php">New Item</a> / 
	<a href="newgeartype.php">New Gear Type</a>

	<hr />

	<!-- RESULTS -->
	<table>
		<tr>
			<td>gear_id</td>
			<td>Name</td>
			<td>Type</td>
		</tr>
		<?php
			foreach($gearList as $gear){
				$type = gearTypeWithID($gear['gear_type_id']);
				printf("<tr>");
				printf("<td>%s</td>",$gear['gear_id']);
				printf("<td>%s</td>",$gear['name']);
				printf("<td>%s</td>",$type);
				printf("</tr>");
			}
		?>
	</table>

</body>
</html>
