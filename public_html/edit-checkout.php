<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$co_start = $co_end = $title = $description = "";

	//get initial checkout info
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);

		$co = new Checkout();
		$co->retrieveCheckout($co_id);

		$co_start = $co->getStart();
		$co_end = $co->getEnd();
		$formattedStart = new DateTime($co_start);
		$formattedEnd = new DateTime($co_end);
		
		$s_year = $formattedStart->format('Y');
		$s_month = $formattedStart->format('m');
		$s_day = $formattedStart->format('d'); 
		$s_hour = $formattedStart->format('H') % 12;
		echo $s_hour . "<br />";
		$s_min = $formattedStart->format('i');
		if($formattedStart->format('H') >= 12){
			$s_ampm = "pm";
		} else $s_ampm = "am";

		if($s_hour == 0){
			$s_hour = 12;
			$s_ampm = "am";
		}

		$e_year = $formattedEnd->format('Y');
		$e_month = $formattedEnd->format('m');
		$e_day = $formattedEnd->format('d'); 
		$e_hour = $formattedEnd->format('H') % 12;
		echo $e_hour . "<br />";
		$e_min = $formattedEnd->format('i');
		if($formattedEnd->format('H') >= 12){
			$e_ampm = "pm";
		} else $e_ampm = "am";

		if($e_hour == 0){
			$e_hour = 12;
			$e_ampm = "am";
		}

	}

	//form submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//check what step we're on.
		if(isset($_POST['step'])){
			$step = test_input($_POST['step']);
		}

		//------------------------ STEP 1 SUBMITTED ------------------------
		//time and date should be submitted, but datestring not formed.
		if($step == 1){
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_id = test_input($_POST['co_id']);

			//start timedate
			$start_day = test_input($_POST['start_day']);
			$start_month = test_input($_POST['start_month']);
			$start_year = test_input($_POST['start_year']);
			$start_hour = test_input($_POST['start_hour']);
			$start_min = test_input($_POST['start_min']);
			$start_ampm = test_input($_POST['start_ampm']);
			if ($start_ampm == "pm") $start_hour += 12;
			else {
				if ($start_hour == 12) $start_hour = 0;
			}

			//end timedate
			$end_day = test_input($_POST['end_day']);
			$end_month = test_input($_POST['end_month']);
			$end_year = test_input($_POST['end_year']);
			$end_hour = test_input($_POST['end_hour']);
			$end_min = test_input($_POST['end_min']);
			$end_ampm = test_input($_POST['end_ampm']);
			if ($end_ampm == "pm") $end_hour += 12;
			else {
				if ($end_hour == 12) $end_hour = 0;
			}

			$co_start = $start_year . "-" . $start_month . "-" . $start_day . " " . $start_hour . ":" . $start_min . ":00"; 	
			$co_end = $end_year . "-" . $end_month . "-" . $end_day . " " . $end_hour . ":" . $end_min . ":00"; 

			//see if we need to change anything
			$co = new Checkout();
			$co->retrieveCheckout($co_id);

			//construct a simple gearList
			$simpleGearList = array();
			foreach($co->getGearList() as $gear){
				$simpleGearList[] = $gear[0];
			}

			if(!empty($title) && $co->getTitle() != $title){ //title change
				$successes[] = "Title updated successfully";
			} else $title = $co->getTitle();

			if($co->getDescription() != $description){ //description change
				$successes[] = "Description updated successfully";
			} else $description = $co->getDescription();

			if(!empty($co_start) && $co->getStart() != $co_start){ //start change
				//$successes[] = "Start time/date updated successfully";
			} else $co_start = $co->getStart();

			if(!empty($co_end) && $co->getEnd() != $co_end){// end change
				//$successes[] = "End time/date updated successfully";
			} else $co_end = $co->getEnd();

		} //------------------------ STEP 2 SUBMITTED ------------------------
		else if ($step == 2){
			//need to process gear list

			//collect vars from step 1
			$co_id = test_input($_POST['co_id']);
			$co = new Checkout();
			$co->retrieveCheckout($co_id); //used for listing curr qty
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);

			//construct a clean gear list
			$gearList = array();
			$gearToGetQtyFor = array();
			foreach ($_POST['gear'] as $gearItem) {
				$gearList[] = test_input($gearItem);

				//if the available qty is > 1, we need to find out
				//what qty the user wants to check out
				if(availableQtyExcludingCheckout($gearItem, $co_id , $co_start, $co_end) > 1){
					$gearToGetQtyFor[] = $gearItem;
				}
			}
		} //------------------------ STEP 3 SUBMITTED ------------------------ 
		else if ($step == 3){
			//collect vars from step 1
			$co_id = test_input($_POST['co_id']);
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);

			//construct a clean gear list
			$gearList = array();
			foreach ($_POST['gear'] as $gearItem) {
				$gearList[] = test_input($gearItem);
			}

			//clean up gear qty array from post
			$gearQty = array();
			foreach ($_POST['gearQty'] as $qty) {
				$gearQty[] = test_input($qty);
			}

			$i = 0; // to iterate thru gear qty array

			//need to process quantities & finalize
			//create checkout object
			$co = new Checkout();

			$co->setID($co_id);
			$co->setTitle($title);
			$co->setPerson($loggedInUser->user_id);
			$co->setStart($co_start);
			$co->setEnd($co_end);
			$co->setDescription($description);

			foreach ($gearList as $gearItem) {
				if(availableQty($gearItem, $co_start, $co_end) > 1){
					$co->addToGearList($gearItem,$gearQty[$i]);
					$i++;
				} else {
					$co->addToGearList($gearItem,1);	
				}	
			}

			$co->finalizeCheckout();
			$co_id = $co->getID();
			header("Location: checkout.php?co_id=$co_id");
		}

	}//end if POST

	//increment step
	$step++;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Edit Checkout</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Edit Checkout</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<?php echo "<a href='checkout.php?co_id=" . $co_id . "'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Checkout Details</a>";
                echo "<br /><br />";
                echo resultBlock($errors,$successes); 
                if($step == 1): ?>

	                <h2>Step 1: Edit Details</h2>
	                <hr />

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<input type="hidden" name="step" value="1" />
						<input type="hidden" name="co_id" value="<?php echo $co_id ?>" />

						<div class="form-group"> <!-- TITLE -->
							<label class="control-label" for="title">Event Title:</label>  
							<input type="text" class="form-control" name="title" placeholder="<?php echo $co->getTitle(); ?>">
						</div>
						<div class="form-group"> <!-- DESC -->
							<label class="control-label" for="description">Description:</label>  
							<textarea class="form-control" name="description" rows="3"><?php echo $co->getDescription(); ?></textarea>
						</div>
						<div class="alert alert-warning">
						Changing the date of a checkout may change what gear is available for checkout.
						</div>
						<div class="form-inline form-group"><!-- START -->
							<label class="control-label" for="start_month">Start date:</label>
							<select class="form-control" style="display: inline" name="start_month">
								<option value="01" <?php if ($s_month == 1) echo 'selected="selected"';?>>January</option>
								<option value="02" <?php if ($s_month == 2) echo 'selected="selected"';?>>February</option>
								<option value="03" <?php if ($s_month == 3) echo 'selected="selected"';?>>March</option>
								<option value="04" <?php if ($s_month == 4) echo 'selected="selected"';?>>April</option>
								<option value="05" <?php if ($s_month == 5) echo 'selected="selected"';?>>May</option>
								<option value="06" <?php if ($s_month == 6) echo 'selected="selected"';?>>June</option>
								<option value="07" <?php if ($s_month == 7) echo 'selected="selected"';?>>July</option>
								<option value="08" <?php if ($s_month == 8) echo 'selected="selected"';?>>August</option>
								<option value="09" <?php if ($s_month == 9) echo 'selected="selected"';?>>September</option>
								<option value="10" <?php if ($s_month == 10) echo 'selected="selected"';?>>October</option>
								<option value="11" <?php if ($s_month == 11) echo 'selected="selected"';?>>November</option>
								<option value="12" <?php if ($s_month == 12) echo 'selected="selected"';?>>December</option>
							</select>
							<select class="form-control" style="display: inline" name="start_day">
					            <option value"01" <?php if ($s_day == 1) echo 'selected="selected"';?>>01</option>
					            <option value"02" <?php if ($s_day == 2) echo 'selected="selected"';?>>02</option>
					            <option value"03" <?php if ($s_day == 3) echo 'selected="selected"';?>>03</option>
					            <option value"04" <?php if ($s_day == 4) echo 'selected="selected"';?>>04</option>
					            <option value"05" <?php if ($s_day == 5) echo 'selected="selected"';?>>05</option>
					            <option value"06" <?php if ($s_day == 6) echo 'selected="selected"';?>>06</option>
					            <option value"07" <?php if ($s_day == 7) echo 'selected="selected"';?>>07</option>
					            <option value"08" <?php if ($s_day == 8) echo 'selected="selected"';?>>08</option>
					            <option value"09" <?php if ($s_day == 9) echo 'selected="selected"';?>>09</option>
					            <option value"10" <?php if ($s_day == 10) echo 'selected="selected"';?>>10</option>
					            <option value"11" <?php if ($s_day == 11) echo 'selected="selected"';?>>11</option>
					            <option value"12" <?php if ($s_day == 12) echo 'selected="selected"';?>>12</option>
					            <option value"13" <?php if ($s_day == 13) echo 'selected="selected"';?>>13</option>
					            <option value"14" <?php if ($s_day == 14) echo 'selected="selected"';?>>14</option>
					            <option value"15" <?php if ($s_day == 15) echo 'selected="selected"';?>>15</option>
					            <option value"16" <?php if ($s_day == 16) echo 'selected="selected"';?>>16</option>
					            <option value"17" <?php if ($s_day == 17) echo 'selected="selected"';?>>17</option>
					            <option value"18" <?php if ($s_day == 18) echo 'selected="selected"';?>>18</option>
					            <option value"19" <?php if ($s_day == 19) echo 'selected="selected"';?>>19</option>
					            <option value"20" <?php if ($s_day == 20) echo 'selected="selected"';?>>20</option>
					            <option value"21" <?php if ($s_day == 21) echo 'selected="selected"';?>>21</option>
					            <option value"22" <?php if ($s_day == 22) echo 'selected="selected"';?>>22</option>
					            <option value"23" <?php if ($s_day == 23) echo 'selected="selected"';?>>23</option>
					            <option value"24" <?php if ($s_day == 24) echo 'selected="selected"';?>>24</option>
					            <option value"25" <?php if ($s_day == 25) echo 'selected="selected"';?>>25</option>
					            <option value"26" <?php if ($s_day == 26) echo 'selected="selected"';?>>26</option>
					            <option value"27" <?php if ($s_day == 27) echo 'selected="selected"';?>>27</option>
					            <option value"28" <?php if ($s_day == 28) echo 'selected="selected"';?>>28</option>
					            <option value"29" id="29" <?php if ($s_day == 29) echo 'selected="selected"';?>>29</option>
					            <option value"30" id="30" <?php if ($s_day == 30) echo 'selected="selected"';?>>30</option>
					            <option value"31" id="31" <?php if ($s_day == 31) echo 'selected="selected"';?>>31</option>
							</select>
							<select class="form-control" style="display: inline" name="start_year">
								<option value="2015" <?php if ($s_year == 15) echo 'selected="selected"';?>>2015</option>
								<option value="2016" <?php if ($s_year == 16) echo 'selected="selected"';?>>2016</option>
							</select>
							<label class="control-label" for="start_hour">&nbsp;&nbsp;Start Time </label>
							<select class="form-control" style="display: inline" name="start_hour">
								<option value="01" <?php if ($s_hour == 1) echo 'selected="selected"';?>>01</option>
								<option value="02" <?php if ($s_hour == 2) echo 'selected="selected"';?>>02</option>
								<option value="03" <?php if ($s_hour == 3) echo 'selected="selected"';?>>03</option>
								<option value="04" <?php if ($s_hour == 4) echo 'selected="selected"';?>>04</option>
								<option value="05" <?php if ($s_hour == 5) echo 'selected="selected"';?>>05</option>
								<option value="06" <?php if ($s_hour == 6) echo 'selected="selected"';?>>06</option>
								<option value="07" <?php if ($s_hour == 7) echo 'selected="selected"';?>>07</option>
								<option value="08" <?php if ($s_hour == 8) echo 'selected="selected"';?>>08</option>
								<option value="09" <?php if ($s_hour == 9) echo 'selected="selected"';?>>09</option>
								<option value="10" <?php if ($s_hour == 10) echo 'selected="selected"';?>>10</option>
								<option value="11" <?php if ($s_hour == 11) echo 'selected="selected"';?>>11</option>
								<option value="12" <?php if ($s_hour == 12) echo 'selected="selected"';?>>12</option>
							</select>
							<select class="form-control" style="display: inline" name="start_min">
					        	<option value"00" <?php if ($s_min == 0) echo 'selected="selected"';?>>00</option>
					            <option value"05" <?php if ($s_min == 5) echo 'selected="selected"';?>>05</option>
					            <option value"10" <?php if ($s_min == 10) echo 'selected="selected"';?>>10</option>
					            <option value"15" <?php if ($s_min == 15) echo 'selected="selected"';?>>15</option>
					            <option value"20" <?php if ($s_min == 20) echo 'selected="selected"';?>>20</option>
					            <option value"25" <?php if ($s_min == 25) echo 'selected="selected"';?>>25</option>
					            <option value"30" <?php if ($s_min == 30) echo 'selected="selected"';?>>30</option>
								<option value"35" <?php if ($s_min == 35) echo 'selected="selected"';?>>35</option>
					            <option value"40" <?php if ($s_min == 40) echo 'selected="selected"';?>>40</option>
					            <option value"45" <?php if ($s_min == 45) echo 'selected="selected"';?>>45</option>
					            <option value"50" <?php if ($s_min == 50) echo 'selected="selected"';?>>50</option>
					            <option value"55" <?php if ($s_min == 55) echo 'selected="selected"';?>>55</option>
							</select>
							<select class="form-control" style="display: inline" name="start_ampm">
					        	<option value="am" <?php if ($s_ampm == "am") echo 'selected="selected"';?>>AM</option>
					        	<option value="pm" <?php if ($s_ampm == "pm") echo 'selected="selected"';?>>PM</option>
							</select>
						</div>
						<div class="form-inline form-group"> <!-- END -->
							<label class="control-label" for="end_month">End date:</label>
							<select class="form-control" style="display: inline" name="end_month">
								<option value="01" <?php if ($e_month == 1) echo 'selected="selected"';?>>January</option>
								<option value="02" <?php if ($e_month == 2) echo 'selected="selected"';?>>February</option>
								<option value="03" <?php if ($e_month == 3) echo 'selected="selected"';?>>March</option>
								<option value="04" <?php if ($e_month == 4) echo 'selected="selected"';?>>April</option>
								<option value="05" <?php if ($e_month == 5) echo 'selected="selected"';?>>May</option>
								<option value="06" <?php if ($e_month == 6) echo 'selected="selected"';?>>June</option>
								<option value="07" <?php if ($e_month == 7) echo 'selected="selected"';?>>July</option>
								<option value="08" <?php if ($e_month == 8) echo 'selected="selected"';?>>August</option>
								<option value="09" <?php if ($e_month == 9) echo 'selected="selected"';?>>September</option>
								<option value="10" <?php if ($e_month == 10) echo 'selected="selected"';?>>October</option>
								<option value="11" <?php if ($e_month == 11) echo 'selected="selected"';?>>November</option>
								<option value="12" <?php if ($e_month == 12) echo 'selected="selected"';?>>December</option>
							</select>
							<select class="form-control" style="display: inline" name="end_day">
					            <option value"01" <?php if ($e_day == 1) echo 'selected="selected"';?>>01</option>
					            <option value"02" <?php if ($e_day == 2) echo 'selected="selected"';?>>02</option>
					            <option value"03" <?php if ($e_day == 3) echo 'selected="selected"';?>>03</option>
					            <option value"04" <?php if ($e_day == 4) echo 'selected="selected"';?>>04</option>
					            <option value"05" <?php if ($e_day == 5) echo 'selected="selected"';?>>05</option>
					            <option value"06" <?php if ($e_day == 6) echo 'selected="selected"';?>>06</option>
					            <option value"07" <?php if ($e_day == 7) echo 'selected="selected"';?>>07</option>
					            <option value"08" <?php if ($e_day == 8) echo 'selected="selected"';?>>08</option>
					            <option value"09" <?php if ($e_day == 9) echo 'selected="selected"';?>>09</option>
					            <option value"10" <?php if ($e_day == 10) echo 'selected="selected"';?>>10</option>
					            <option value"11" <?php if ($e_day == 11) echo 'selected="selected"';?>>11</option>
					            <option value"12" <?php if ($e_day == 12) echo 'selected="selected"';?>>12</option>
					            <option value"13" <?php if ($e_day == 13) echo 'selected="selected"';?>>13</option>
					            <option value"14" <?php if ($e_day == 14) echo 'selected="selected"';?>>14</option>
					            <option value"15" <?php if ($e_day == 15) echo 'selected="selected"';?>>15</option>
					            <option value"16" <?php if ($e_day == 16) echo 'selected="selected"';?>>16</option>
					            <option value"17" <?php if ($e_day == 17) echo 'selected="selected"';?>>17</option>
					            <option value"18" <?php if ($e_day == 18) echo 'selected="selected"';?>>18</option>
					            <option value"19" <?php if ($e_day == 19) echo 'selected="selected"';?>>19</option>
					            <option value"20" <?php if ($e_day == 20) echo 'selected="selected"';?>>20</option>
					            <option value"21" <?php if ($e_day == 21) echo 'selected="selected"';?>>21</option>
					            <option value"22" <?php if ($e_day == 22) echo 'selected="selected"';?>>22</option>
					            <option value"23" <?php if ($e_day == 23) echo 'selected="selected"';?>>23</option>
					            <option value"24" <?php if ($e_day == 24) echo 'selected="selected"';?>>24</option>
					            <option value"25" <?php if ($e_day == 25) echo 'selected="selected"';?>>25</option>
					            <option value"26" <?php if ($e_day == 26) echo 'selected="selected"';?>>26</option>
					            <option value"27" <?php if ($e_day == 27) echo 'selected="selected"';?>>27</option>
					            <option value"28" <?php if ($e_day == 28) echo 'selected="selected"';?>>28</option>
					            <option value"29" id="29" <?php if ($e_day == 29) echo 'selected="selected"';?>>29</option>
					            <option value"30" id="30" <?php if ($e_day == 30) echo 'selected="selected"';?>>30</option>
					            <option value"31" id="31" <?php if ($e_day == 31) echo 'selected="selected"';?>>31</option>
							</select>
							<select class="form-control" style="display: inline" name="end_year">
								<option value="2015" <?php if ($e_year == 15) echo 'selected="selected"';?>>2015</option>
								<option value="2016" <?php if ($e_year == 16) echo 'selected="selected"';?>>2016</option>
							</select>
							<label class="control-label" for="end_hour">&nbsp;&nbsp;End Time </label>
							<select class="form-control" style="display: inline" name="end_hour">
								<option value="01" <?php if ($e_hour == 1) echo 'selected="selected"';?>>01</option>
								<option value="02" <?php if ($e_hour == 2) echo 'selected="selected"';?>>02</option>
								<option value="03" <?php if ($e_hour == 3) echo 'selected="selected"';?>>03</option>
								<option value="04" <?php if ($e_hour == 4) echo 'selected="selected"';?>>04</option>
								<option value="05" <?php if ($e_hour == 5) echo 'selected="selected"';?>>05</option>
								<option value="06" <?php if ($e_hour == 6) echo 'selected="selected"';?>>06</option>
								<option value="07" <?php if ($e_hour == 7) echo 'selected="selected"';?>>07</option>
								<option value="08" <?php if ($e_hour == 8) echo 'selected="selected"';?>>08</option>
								<option value="09" <?php if ($e_hour == 9) echo 'selected="selected"';?>>09</option>
								<option value="10" <?php if ($e_hour == 10) echo 'selected="selected"';?>>10</option>
								<option value="11" <?php if ($e_hour == 11) echo 'selected="selected"';?>>11</option>
								<option value="12" <?php if ($e_hour == 12) echo 'selected="selected"';?>>12</option>
							</select>
							<select class="form-control" style="display: inline" name="end_min">
					        	<option value"00" <?php if ($e_min == 0) echo 'selected="selected"';?>>00</option>
					            <option value"05" <?php if ($e_min == 5) echo 'selected="selected"';?>>05</option>
					            <option value"10" <?php if ($e_min == 10) echo 'selected="selected"';?>>10</option>
					            <option value"15" <?php if ($e_min == 15) echo 'selected="selected"';?>>15</option>
					            <option value"20" <?php if ($e_min == 20) echo 'selected="selected"';?>>20</option>
					            <option value"25" <?php if ($e_min == 25) echo 'selected="selected"';?>>25</option>
					            <option value"30" <?php if ($e_min == 30) echo 'selected="selected"';?>>30</option>
								<option value"35" <?php if ($e_min == 35) echo 'selected="selected"';?>>35</option>
					            <option value"40" <?php if ($e_min == 40) echo 'selected="selected"';?>>40</option>
					            <option value"45" <?php if ($e_min == 45) echo 'selected="selected"';?>>45</option>
					            <option value"50" <?php if ($e_min == 50) echo 'selected="selected"';?>>50</option>
					            <option value"55" <?php if ($e_min == 55) echo 'selected="selected"';?>>55</option>
							</select>
							<select class="form-control" style="display: inline" name="end_ampm">
					        	<option value="am" <?php if ($e_ampm == "am") echo 'selected="selected"';?>>AM</option>
					        	<option value="pm" <?php if ($e_ampm == "pm") echo 'selected="selected"';?>>PM</option>
							</select>
						</div>
						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step==2): ?>
					<h2>Step 1: Event Details</h2>
					<div class="alert alert-info" role="alert">
						<?php
							echo "Title: " . $co->getTitle() . "<br /> Description: " . $co->getDescription() . "<br />"; 
							$formattedStart = new DateTime($co_start);
							$formattedEnd = new DateTime($co_end);
							echo $formattedStart->format('m-d-y g:iA') . " <span class='glyphicon glyphicon-arrow-right'></span> " . $formattedEnd->format('m-d-y g:iA');
						?>
					</div>

					<h2>Step 2: Edit Gear</h2>
					<p>Quantities (where applicable) are shown next to the name.<br />They will be chosen in the next step.</p>
					<hr />

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<input type="hidden" name="co_id" value="<?php echo $co_id; ?>" />
						<input type="hidden" name="step" value="2" />
						<input type="hidden" name="title" value="<?php echo $title ?>" />
						<input type="hidden" name="description" value="<?php echo $description ?>" />
						<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />
						<?php
							foreach($types as $type){
								$items = getAvailableGearWithTypeAndExclusions($type['gear_type_id'], $co_id, $co_start, $co_end);
								if (count($items) > 0){
									printf("<h4>%s</h4>",$type['type']);
									foreach($items as $item){
										$qty = availableQtyExcludingCheckout($item['gear_id'], $co_id, $co_start, $co_end);
										echo "<div class='checkbox'>";
										echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "'";
										//if the gear item is already assigned to checkout, check it by default
										if(in_array($item['gear_id'], $simpleGearList)) {
											echo "checked > " . $item['name'];
										} else {
											echo "> " . $item['name'];
										}
										//list quantity next to gear items.
										if($qty > 1){
											echo " (" . $qty . ")";
										}
										echo "</label></div>";
										//break;	
									}
								}//if
							}//foreach
						?>
						<br />
						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step == 3): ?>
					<h2>Step 1: Event Details</h2>
					<div class="alert alert-info" role="alert">
						<?php
							echo "Title: " . $title . "<br /> Description: " . $description . "<br />"; 
							$formattedStart = new DateTime($co_start);
							$formattedEnd = new DateTime($co_end);
							echo $formattedStart->format('m-d-y g:iA') . " <span class='glyphicon glyphicon-arrow-right'></span> " . $formattedEnd->format('m-d-y g:iA');
						?>
					</div>
					<h2>Step 2: Select Gear</h2>
					<div class="alert alert-info" role="alert">
						<?php
							foreach($gearList as $gear){
								$gearName = getGearName($gear);
								echo $gearName . "<br />";
							}
						?>
					</div>

					<h2>Step 3: Choose Quantities</h2>
					<hr />
					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<input type="hidden" name="co_id" value="<?php echo $co_id ?>" />
						<input type="hidden" name="step" value="3" />
						<input type="hidden" name="title" value="<?php echo $title ?>" />
						<input type="hidden" name="description" value="<?php echo $description ?>" />
						<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />
						<?php
							//make sure gear list is resubmitted
							foreach ($gearList as $gear) {
								echo "<input type='hidden' name='gear[]' value='$gear' />";
							}

							//get quantities...
							if(count($gearToGetQtyFor) == 0){
								echo "<p><strong>There are no items to get quantity for. Click below to finish</strong></p>";
							} else {
								foreach($gearToGetQtyFor as $gear) {
									$gearName = getGearName($gear);
									echo $gearName . "&nbsp;&nbsp;&nbsp;";
									echo "<select name='gearQty[]'>";
									$qty = availableQty($gear, $co_start, $co_end);
									$currQty = $co->qtyOfItem($gear);
									for($i = 1; $i <= $qty; $i++){
										if($i == $currQty) echo "<option value='$i' selected='selected'>$i</option>";
										else echo "<option value='$i'>$i</option>";
									}
									echo "</select><br /><br />";
								}
							}
						?>
						<br />
						<input class="btn btn-success" type="submit" name="submit" value="Submit">
					</form>
				<?php endif; ?>
            </div> <!-- /col -->
        </div><!-- /row --> 
    </div> <!-- /container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>