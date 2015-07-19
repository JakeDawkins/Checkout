<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	//require_once('model/Gear.php');
	//require_once('model/Checkout.php');
	require_once('models/Gear.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$name = $category = "";

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = test_input($_POST['name']);
		$category = test_input($_POST['category']);
		$newCategory = test_input($_POST['newCategory']);

		//user provided a new category
		if (!empty($newCategory)){
			$category = newGearType($newCategory);
		}

		newGearItem($name,$category);
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
	<h1>Add a New Gear Item</h1>
	<?php include('templates/nav.php'); ?>

	<?php //print_r($_POST); ?>

	<?php
		// if (isset($error)){
		// 	echo '<p class="error">';
		// 	if (isset($error['date'])) printf("%s<br />",$error['date']);
		// 	echo '</p>';
		// }
	?>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<label for="name">Name:</label>
		<input name="name" type="text" /><br /><br />

		<label for="category">Choose a category:</label>
		<select name="category">
		<?php
			foreach($types as $type){
				printf("<option value=\"%s\">%s</option>",$type['gear_type_id'],$type['type']);
			}
		?>
		</select><br />

		<label for="newCategory">Or create a new category:</label>
		<input name="newCategory" type="text" />

		<input type="submit" name="submit" value="Submit" />
	</form>

</body>
</html>
