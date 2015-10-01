<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');


	$qty = availableQty(136,"2015-01-01 00:00:01", "2015-01-03 00:00:01");
	echo "qty: $qty";

?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
	<link href="css/boostrap.min.css" rel="stylesheet" />
	<link href="css/mainstyle.css" rel="stylesheet" />
</head>
<body>
	<!-- jQuery Version 1.11.1 -->
	<script src="js/jquery.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<script>
		// With JQuery
		$("#ex7").slider();

		// Without JQuery
		//var slider = new Slider("#ex7");

		$("#ex7-enabled").click(function() {
			if(this.checked) {
				// With JQuery
				$("#ex7").slider("enable");

				// Without JQuery
				//slider.enable();
			}
			else {
				// With JQuery
				$("#ex7").slider("disable");

				// Without JQuery
				//slider.disable();
			}
		});
	</script>


	<input id="ex7" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="5" data-slider-enabled="false"/>
	<input id="ex7-enabled" type="checkbox"/> Enabled


</body>
</html>