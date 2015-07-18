<?php
	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once("models/config.php"); //needed for funcs
	require_once('models/funcs.php'); //to fetch details about person

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
	<?php include('templates/nav.php'); ?>
	<a href="newcheckout.php">New Checkout</a>

	<!-- SEARCH TOOLS -->
	<hr />
	<h4>Search Checkouts By Date</h4>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<select name="year">
			<option value="2015" <?php if ($year == 2015) echo 'selected="selected"';?>>2015</option>
			<option value="2016" <?php if ($year == 2016) echo 'selected="selected"';?>>2016</option>
		</select>
		<select name="month">
			<option value="01"<?php if ($month == 01) echo 'selected="selected"';?>>January</option>
			<option value="02"<?php if ($month == 02) echo 'selected="selected"';?>>February</option>
			<option value="03"<?php if ($month == 03) echo 'selected="selected"';?>>March</option>
			<option value="04"<?php if ($month == 04) echo 'selected="selected"';?>>April</option>
			<option value="05"<?php if ($month == 05) echo 'selected="selected"';?>>May</option>
			<option value="06"<?php if ($month == 06) echo 'selected="selected"';?>>June</option>
			<option value="07"<?php if ($month == 07) echo 'selected="selected"';?>>July</option>
			<option value="08"<?php if ($month == 08) echo 'selected="selected"';?>>August</option>
			<option value="09"<?php if ($month == 09) echo 'selected="selected"';?>>September</option>
			<option value="10"<?php if ($month == 10) echo 'selected="selected"';?>>October</option>
			<option value="11"<?php if ($month == 11) echo 'selected="selected"';?>>November</option>
			<option value="12"<?php if ($month == 12) echo 'selected="selected"';?>>December</option>
		</select>
		<input type="submit" name="submit" value="Submit" />
	</form>
	<hr />

	<!-- RESULTS -->
	<table style="">
		<tr>
			<!-- <td>co_id</td> -->
			<td>Title</td>
			<td>Description</td>
			<td>Person</td>
			<td>Start</td>
			<td>End</td>
			<td>Gear</td>
		</tr>
		<?php
			foreach($checkouts as $checkout){
				$person = $checkout->getPerson();
				$personDetails = fetchUserDetails(NULL,NULL,$person);

				printf("<tr>");
				//printf("<td>%s</td>",$checkout->getID());
				printf("<td>%s</td>",$checkout->getTitle());
				printf("<td>%s</td>",$checkout->getDescription());
				printf("<td>%s</td>",$personDetails['display_name']);
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
