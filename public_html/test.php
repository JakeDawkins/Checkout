<?php
	require_once('PHP/Gear.php');
	require_once('PHP/Checkout.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		test page!
	</title>
</head>
<body>
	<?php 
		//------------------------ basic getters ------------------------
		/*
		$results = getGearListWithType("Camera");
		$count = count($results);
		echo "<h1>count: $count</h1>";

		foreach ($results as $row){ 
			printf("%s\t%s\t%s",$row['gear_id'],$row['gear_type_id'],$row['name']);
			echo '<br />';
	 	} 
		*/
		//------------------------ single availability ------------------------
	 	/*
	 	//gear id, date start, date end
	 	if(isAvailable("1","2015-06-30 12:00:00","2015-07-01 12:00:00")){
	 		printf("available");
	 	} else {
	 		printf("not available");
	 	}
		*/
	 	//------------------------ multiple availability ------------------------
	 	/*
	 	echo '<br /><h2>available gear</h2><hr /><br />';

	 	$results = getAvailableGear("2015-06-30 12:00:00","2015-07-01 12:00:00");
	 	foreach ($results as $row){ 
			printf("%s\t%s\t%s",$row['gear_id'],$row['gear_type_id'],$row['name']);
			echo '<br />';
	 	} 
		*/
	 	//------------------------ insert ------------------------
	 	/*
	 	echo '<br /><h2>insert gear type</h2><hr /><br />';

	 	//newGearItem("camera4-id","1");
	 	//newGearItem("test-name","Camera");

	 	//newGearType("Lens");

	 	$results = getGearTypes();
		foreach ($results as $row){ 
			printf("%s\t%s",$row['gear_type_id'],$row['type']);
			echo '<br />';
	 	} 	 	

		//$co = new Checkout();
		//$co->retrieveCheckout(1);
		*/
	 	//------------------------ new checkout ------------------------
	 	/*
	 	echo '<br /><h2>New Checkout</h2><hr /><br />';
	 	$newCO = new Checkout();
	 	$newCO2 = new Checkout();

		$newCO->setTitle("jake doing stuff");
		$newCO->setPerson(1);
		$newCO->setStart("2015-10-15 01:00:00");
		$newCO->setEnd("2015-10-15 03:00:00");
		$newCO->setDescription("just wanna check out some fun stuff.");
		$newCO->addToGearList(1);
		$newCO->addToGearList(2);
		$newCO->addToGearList(4);
		$newCO->finalizeCheckout(); //add the new checkout 
		*/
		//------------------------ update existing checkout ------------------------
	 	/*
	 	$newCO2->retrieveCheckout($newCO->getID());

		$newCO2->addToGearList(3);
		$newCO2->addToGearList(4);
		$newCO2->addToGearList(1);
		$newCO2->removeFromGearList(2);
		$newCO2->setTitle("more updates");
		$newCO2->finalizeCheckout();
		*/

		?>
</body>
</html>