<?php
	//require_once('PHP/Gear.php');
	require_once('PHP/Checkout.php');
	require_once('PHP/Form.php');
	require_once('PHP/Gear.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$month = test_input($_POST['month']);
		$year = test_input($_POST['year']);

		//calculate date from m/y
		$date = "$year-$month-01";
	} else $date = date('Y-m-01'); //pick first of month

	$start = $date;
	$end = strtotime(date("Y-m-d", strtotime($date)) . "+1 month");
	$end = date('Y-m-d', $end);

	$checkouts = array();
	$checkouts = Checkout::getCheckoutsInRange($start, $end);

?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Checkouts
	</title>
</head>
<body>
	<h1>Checkouts</h1>

	<!-- SEARCH TOOLS --> 
	<h4>Search Checkouts By Date</h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<select name="year">
			<option value="2015">2015</option>
			<option value="2016">2016</option>
		</select>
		<select name="month">
			<option value="01">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select>
		<input type="submit" name="submit" value="Submit" />
	</form>

	<!-- RESULTS --> 
	<table style="">
		<tr>
			<td>co_id</td>
			<td>Title</td>
			<td>Description</td>
			<td>Person</td>
			<td>Start</td>
			<td>End</td>
			<td>Gear</td>
		</tr>
		<?php
			foreach($checkouts as $checkout){
				printf("<tr>");
				printf("<td>%s</td>",$checkout->getID());
				printf("<td>%s</td>",$checkout->getTitle());
				printf("<td>%s</td>",$checkout->getDescription());
				printf("<td>%s</td>",$checkout->getPerson());
				printf("<td>%s</td>",$checkout->getStart());
				printf("<td>%s</td>",$checkout->getEnd());
				printf("<td>");
				foreach($checkout->getGearList() as $gear){
					printf("%s<br>",getGearName($gear));
				}
				printf("</td></tr>");	
			}
		?>
	</table>





</body>
</html>