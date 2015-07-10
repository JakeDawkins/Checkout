<?php
	//require_once('PHP/Gear.php');
	require_once('PHP/Checkout.php');

	$types = getGearTypes();

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	//define variables and set to empty values
	$co_start = $co_end = $title = $description = "";
	$displayGear = false;

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$co_start = test_input($_POST['co_start']);
		$co_end = test_input($_POST['co_end']);
		$title = test_input($_POST['title']);
		$description = test_input($_POST['description']);
	}

	//need both start and end in order to list available gear
	if(!empty($co_start) && !empty($co_end)) $displayGear = true;

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		New Checkout
	</title>
</head>
<body>
	<h1>New Checkout</h1>

	<?php print_r($_POST); ?>

	<?php if($displayGear == false): ?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

			<h2>Checkout Details</h2>

			<label for="title">Event Title:</label>
			<input type="text" name="title" /><br />

			<!-- <textarea rows="3" cols="50">Event Description</textarea><br /> -->
			<label for ="description">Description:</label>
			<input type="text" name="description" /><br />

			<label for="co_start">Begin:</label>
			<input type="text" name="co_start" /><br />

			<label for="co_end">End:</label>
			<input type="text" name="co_end" /><br />

			<input class="" type="submit" name="submit" value="Next">
		</form>	

	<?php else: ?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

			<label for="title">Event Title:</label>
			<input type="text" placeholder="<?php echo $title ?>" disabled /><br />
			<input type="hidden" name="title" value="<?php echo $title ?>" />

			<!-- <textarea rows="3" cols="50">Event Description</textarea><br /> -->
			<label for ="description">Description:</label>
			<input type="text" placeholder="<?php echo $description ?>" disabled /><br />
			<input type="hidden" name="description" value="<?php echo $description ?>" />

			<label for="co_start">Begin:</label>
			<input type="text" placeholder="<?php echo $co_start ?>" disabled /><br />
			<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />

			<label for="co_end">End:</label>
			<input type="text" placeholder="<?php echo $co_end ?>" disabled /><br />
			<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />

			<br />
			<h2>Select Gear</h2>
			<hr />

			<?php
				foreach($types as $type){
					printf("<h3>%s</h3>",$type['type']);
					$items = getAvailableGearWithType($type['gear_type_id'], $co_start, $co_end);
					foreach($items as $item){
						printf("<input type=\"checkbox\" name=\"gear[]\" value=\"%s\">%s<br />",$item['gear_id'],$item['name']);
					}
				}
			?>

			<input type="submit" name="submit" value="Finish">
		</form>	

	<?php endif; ?>

</body>
</html>


<!-- 

RESULTS FROM TYPES
[0] => Array 
	[gear_type_id] => 1 
	[type] => Camera 
[1] => Array 
	[gear_type_id] => 2 
	[type] => Lens


RESULTS FROM AVAILABLE GEAR
[0] => Array ( 
	[gear_id] => 1 
	[gear_type_id] => 1 
	[name] => cam1 ) 
[1] => Array ( 
	[gear_id] => 2 
	[gear_type_id] => 1 
	[name] => cam2 ) 
[2] => Array ( 
	[gear_id] => 3 
	[gear_type_id] => 1 
	[name] => cam3 ) 
[3] => Array ( 
	[gear_id] => 4 
	[gear_type_id] => 1 
	[name] => cam4 ) 
[4] => Array ( 
	[gear_id] => 5 
	[gear_type_id] => 1 
	[name] => camera4-id ) 
[5] => Array ( 
	[gear_id] => 6 
	[gear_type_id] => 1 
	[name] => test-name ) 

FORM SUBMIT
Array ( 
	[title] => title 
	[description] => desc 
	[co_start] => 2015-12-12 12:00:00 
	[co_end] => 2015-12-12 12:00:01 
	[gear] => Array ( 
		[0] => 1 
		[1] => 2 
		[2] => 6 ) 
	[next] => Next )


-->