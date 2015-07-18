<?php
	require_once('models/Gear.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$type = "";

	$submitted = false;
	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = test_input($_POST['type']);

		//user provided a new type
		if (!empty($type)){
			if(!in_array($type, $types)) newGearType($type);
			$submitted = true;
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
		New Gear Item
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

	<?php if (!$submitted): ?>
		<h1>Add a New Gear Item</h1>
		<?php include('templates/nav.php'); ?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
			<label for="type">New Type Name:</label>
			<input name="type" type="text" /><br /><br />

			<input type="submit" name="submit" value="Submit" />
		</form>

	<?php else:
		include('templates/nav.php');
		printf("<h1>New Gear Type, %s, Created!</h1>",$type);
		printf("<a href=\"inventory\">Back to Inventory</a>");

	endif; ?>

</body>
</html>
