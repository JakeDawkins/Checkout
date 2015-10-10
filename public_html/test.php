<?php
	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');

	//gear id = 135
	//exclude co 65
	//type = 17 (audio)
	/*
	$items = getAvailableGearWithTypeAndExclusions(17, 65, "2015-03-01 00:00:01", "2015-03-02 00:00:01");

	if (count($items) > 0){
		foreach($items as $item){
			$qty = availableQtyExcludingCheckout($item['gear_id'], 65, "2015-03-01 00:00:01", "2015-03-02 00:00:01");
			echo "<div class='checkbox'>";
			echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "'> " . $item['name'];
			if($qty > 1){
				echo " (" . $qty . ")";
			}
			echo "</label></div>";	
		}
	}//if
	*/

	//136 41 2015-01-01 01:00:00 2015-01-03 01:00:00
	$aQty = availableQtyExcludingCheckout(135, 41, "2015-01-01 01:00:00", "2015-01-03 01:00:00");
	echo $aQty;
?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>

</body>
</html>