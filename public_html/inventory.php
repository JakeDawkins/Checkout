<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}
	//require_once("models/header.php");

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/Person.php');

	//$gearList = getGearList();
	$types = getGearTypes();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//process deletes
		foreach($_POST['deleteGear'] as $gearItem){
			deleteGearItem($gearItem);
		}
	}


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
	<a href="new-gear.php">New Item</a> / 
	<a href="edit-gear-types.php">Edit Gear Types</a>

	<hr />

	<!--  -->
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<?php
			foreach($types as $type){
				printf("<h3>%s</h3>",$type['type']);
				$gearList = getGearListWithType($type['type']);
				if(count($gearList)==0) {
					//printf("<p>No gear of this type</p>");
				} else {
		?>
			<table>
				<tr>
					<td>ID</td>
					<td>Name</td>
					<td>Last Checkout</td>
					<td>Delete</td>
				</tr>
				<?php
					foreach($gearList as $gear){
						printf("<tr>");
						printf("<td>%s</td>",$gear['gear_id']);
						printf("<td>%s</td>",$gear['name']);

						$co_id = fetchLastCheckout($gear['gear_id']);
						if(!empty($co_id)){
							$co = new Checkout();
		    				$co->retrieveCheckout($co_id);	
		    				printf("<td><a href='checkout.php/?co_id=%s'>%s</a></td>",$co_id,getPersonName($co->getPerson()));
						} else { //no last checkout
							printf("<td>n/a</td>");
						}
						printf("<td><input type=\"checkbox\" name=\"deleteGear[]\" value=\"%s\"></td>",$gear['gear_id']);	
						printf("</tr>");
					}
				?>
			</table>
		<?php } } //foreach ?>
		<input type="submit" name="submit" value="Delete" />
	</form>


</body>
</html>