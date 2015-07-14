<?php
	require_once('PHP/Checkout.php');
	require_once('PHP/Form.php');
	require_once('PHP/Gear.php');

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
	<a href="newgear">New Item</a><br />
	<a href="newgeartype">New Gear Type</a><br />

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
				printf("<tr>");
				printf("<td>%s</td>",$gear['gear_id']);
				printf("<td>%s</td>",$gear['name']);
				printf("<td>%s</td>",gearTypeWithID($gear['gear_type_id']));
				printf("</tr>");	
			}
		?>
	</table>

</body>
</html>