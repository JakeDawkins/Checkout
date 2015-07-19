<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Gear.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$type = "";

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = test_input($_POST['type']);

		//user provided a new type
		if (!empty($type)){
			if(!in_array($type, $types)) newGearType($type);
			$added = true;
		}

		$typesRemoved = array();
		if(!empty($_POST['deleteTypes'])){
			foreach ($_POST['deleteTypes'] as $deleteType) {
				$typesRemoved[] = gearTypeWithId($deleteType);
				deleteGearType($deleteType);
			}
			$removed = true;
		}
		
		//newGearItem($name,$category);
	}

	//------------------------ Validation ------------------------

	//only check when submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //submitted

	} //if submitted


?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Edit Gear Types
	</title>
</head>
<body>
	<?php //print_r($_POST); ?>

	<?php
		// if (isset($error)){
		// 	echo '<p class="error">';
		// 	if (isset($error['date'])) printf("%s<br />",$error['date']);
		// 	echo '</p>';
		// }
	?>

	<?php if (!$added && !$removed): ?>
		<h1>Edit Gear Types</h1>
		<?php include('templates/nav.php'); ?>
		<hr />

		<h3>Add a New Gear Type</h3>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<label for="type">New Type Name:</label>
			<input name="type" type="text" /><br /><br />

			<input type="submit" name="submit" value="Submit" />
		</form>

		<!-- REMOVE -->
		<h3>Remove Gear Types</h3>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<?php
				$types = getGearTypes();
				foreach($types as $type){
					printf("<input type=\"checkbox\" name=\"deleteTypes[]\" value=\"%s\">%s<br />",$type['gear_type_id'],$type['type']);		
				}
			?>
			<input type="submit" name="submit" value="Submit">
		</form>

	<?php else:
		if($added){
			printf("<h3>New Gear Type, %s, Created!</h3>",$type);
			printf("<a href=\"inventory.php\">Back to Inventory</a>");
		} elseif ($removed) {
			printf("<h3>The following were removed:</h3><p>");
			foreach($typesRemoved as $typeRemoved){
				printf("%s<br />",$typeRemoved);
			}
			echo '</p>';
			printf("<a href=\"inventory.php\">Back to Inventory</a>");
		} else {
			printf("an unknown error occurred.<br />");
		}
		
	endif; ?>

</body>
</html>
