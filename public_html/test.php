<?php
	require_once('PHP/Gear.php');
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
		$results = getGearListWithType("Camera");
		$count = count($results);
		echo "<h1>count: $count</h1>";

		foreach ($results as $row){ 
			printf("%s\t%s\t%s",$row['gear_id'],$row['gear_type_id'],$row['name']);
			echo '<br />';
	 } ?>
</body>
</html>