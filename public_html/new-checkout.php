<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Package.php');
	require_once('models/Form.php');

	$types = getGearTypes();
	$packages = Package::getAllPackages();

	//define variables and set to empty values
	$co_start = $co_end = $title = $description = "";

	//form pt. 1 submitted
	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//check what step we're on.
		if(isset($_POST['step'])){
			$step = test_input($_POST['step']);
		}

		//step 1 submitted.
		//time and date should be submitted, but datestring not formed.
		if($step == 1){
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);

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
				if ($start_hour == 12) $start_hour = 0;
			}

			$co_start = $start_year . "-" . $start_month . "-" . $start_day . " " . $start_hour . ":" . $start_min . ":00"; 	
			$co_end = $end_year . "-" . $end_month . "-" . $end_day . " " . $end_hour . ":" . $end_min . ":00"; 
		} else if ($step == 2){ //step 2 submitted
			//time and date string should be constructed.
			//need to process gear list

			//collect vars from step 1
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);

			//look and see what pkgs are added already
			$preCheck = array(); //items to precheck if possible based on pkgs
			if(isset($_POST['addedPkgs'])){
				$addedPkgs = array();
				foreach($_POST['addedPkgs'] as $pkg){
					$addedPkgs[] = test_input($pkg);
					$newPkg = new Package();
					$newPkg->retrievePackage($pkg);
					$preCheck = array_merge($preCheck, $newPkg->getGearList());
					unset($newPkg);
				}	
				if ($_POST['submit'] != "Next") $step = 1; //don't move onto step 3 yet.
			}

			//construct a clean gear list
			$gearList = array();
			$gearToGetQtyFor = array();
			if(isset($_POST['gear'])){
				foreach ($_POST['gear'] as $gearItem) {
					$gearList[] = test_input($gearItem);

					//if the available qty is > 1, we need to find out
					//what qty the user wants to check out
					if(availableQty($gearItem, $co_start, $co_end) > 1){
						$gearToGetQtyFor[] = $gearItem;
					}
				}
			}
		} else if ($step == 3){ //step 3 submitted
			//collect vars from step 1
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

	//------------------------ Validation ------------------------
	//only check when submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //submitted
		if(empty($title)){
			$errors[] = "Please enter a title";
		}
		if (empty($description)){
			$errors[] = "Please enter a description";
		}
		if($co_start != $co_end){ //user modified start/end fields
			if ($co_start > $co_end) {
				//dates not in right order
				$errors[] = "Date Error: The start date is after the end date";
			}
		} else {
			$errors[] = "Date Error: The start and end dates & times are the same";
		}
	} //if submitted
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>New Checkout</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>New Checkout</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<?php echo "<a href=\"checkouts.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a>";
                echo "<br /><br />";
                echo resultBlock($errors,$successes); 
				if($step == 1): ?>

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<input type="hidden" name="step" value="1" />
						<h2>Step 1: Event Details</h2>
						<hr />
						<div class="form-group"> <!-- TITLE -->
							<label class="control-label" for="title">Event Title:</label>  
							<input type="text" class="form-control" name="title">
						</div>
						<div class="form-group"> <!-- DESC -->
							<label class="control-label" for="Description">Description:</label>  
							<textarea class="form-control" name="description" rows="3"></textarea>
						</div>
						<div class="form-inline form-group"><!-- START -->
							<label class="control-label" for="start_date">Start date:</label>
							<select class="form-control" style="display: inline" name="start_month">
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
							<select class="form-control" style="display: inline" name="start_day">
					            <option value"01">01</option>
					            <option value"02">02</option>
					            <option value"03">03</option>
					            <option value"04">04</option>
					            <option value"05">05</option>
					            <option value"06">06</option>
					            <option value"07">07</option>
					            <option value"08">08</option>
					            <option value"09">09</option>
					            <option value"10">10</option>
					            <option value"11">11</option>
					            <option value"12">12</option>
					            <option value"13">13</option>
					            <option value"14">14</option>
					            <option value"15">15</option>
					            <option value"16">16</option>
					            <option value"17">17</option>
					            <option value"18">18</option>
					            <option value"19">19</option>
					            <option value"20">20</option>
					            <option value"21">21</option>
					            <option value"22">22</option>
					            <option value"23">23</option>
					            <option value"24">24</option>
					            <option value"25">25</option>
					            <option value"26">26</option>
					            <option value"27">27</option>
					            <option value"28">28</option>
					            <option value"29" id="29">29</option>
					            <option value"30" id="30">30</option>
					            <option value"31" id="31">31</option>
							</select>
							<select class="form-control" style="display: inline" name="start_year">
								<option value="2015" <?php if ($year == 15) echo 'selected="selected"';?>>2015</option>
								<option value="2016" <?php if ($year == 16) echo 'selected="selected"';?>>2016</option>
							</select>
							<label class="control-label" for="start_time">&nbsp;&nbsp;Start Time </label>
							<select class="form-control" style="display: inline" name="start_hour">
					            <option value"01">1</option>
					            <option value"02">2</option>
					            <option value"03">3</option>
					            <option value"04">4</option>
					            <option value"05">5</option>
					            <option value"06">6</option>
								<option value"07">7</option>
					            <option value"08">8</option>
					            <option value"09">9</option>
					            <option value"10">10</option>
					            <option value"11">11</option>
					            <option value"12">12</option>
							</select>
							<select class="form-control" style="display: inline" name="start_min">
					        	<option value"00">00</option>
					            <option value"05">05</option>
					            <option value"10">10</option>
					            <option value"15">15</option>
					            <option value"20">20</option>
					            <option value"25">25</option>
					            <option value"30">30</option>
								<option value"35">35</option>
					            <option value"40">40</option>
					            <option value"45">45</option>
					            <option value"50">50</option>
					            <option value"55">55</option>
							</select>
							<select class="form-control" style="display: inline" name="start_ampm">
					        	<option value="am">AM</option>
					        	<option value="pm">PM</option>
							</select>
						</div>
						<div class="form-inline form-group"> <!-- END -->
							<label class="control-label" for="co_end">End Date:</label>
							<!-- <input type="text" class="form-control" name="co_end" placeholder="yyyy-mm-dd hh:mm:ss"> -->
							<select class="form-control" style="display: inline" name="end_month">
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
							<select class="form-control" style="display: inline" name="end_day">
					            <option value"01">01</option>
					            <option value"02">02</option>
					            <option value"03">03</option>
					            <option value"04">04</option>
					            <option value"05">05</option>
					            <option value"06">06</option>
					            <option value"07">07</option>
					            <option value"08">08</option>
					            <option value"09">09</option>
					            <option value"10">10</option>
					            <option value"11">11</option>
					            <option value"12">12</option>
					            <option value"13">13</option>
					            <option value"14">14</option>
					            <option value"15">15</option>
					            <option value"16">16</option>
					            <option value"17">17</option>
					            <option value"18">18</option>
					            <option value"19">19</option>
					            <option value"20">20</option>
					            <option value"21">21</option>
					            <option value"22">22</option>
					            <option value"23">23</option>
					            <option value"24">24</option>
					            <option value"25">25</option>
					            <option value"26">26</option>
					            <option value"27">27</option>
					            <option value"28">28</option>
					            <option value"29" id="29">29</option>
					            <option value"30" id="30">30</option>
					            <option value"31" id="31">31</option>
							</select>
							<select class="form-control" style="display: inline" name="end_year">
								<option value="2015" <?php if ($year == 15) echo 'selected="selected"';?>>2015</option>
								<option value="2016" <?php if ($year == 16) echo 'selected="selected"';?>>2016</option>
							</select>
							<label class="control-label" for="co_start">&nbsp;&nbsp;End Time </label>
							<select class="form-control" style="display: inline" name="end_hour">
					            <option value"01">1</option>
					            <option value"02">2</option>
					            <option value"03">3</option>
					            <option value"04">4</option>
					            <option value"05">5</option>
					            <option value"06">6</option>
								<option value"07">7</option>
					            <option value"08">8</option>
					            <option value"09">9</option>
					            <option value"10">10</option>
					            <option value"11">11</option>
					            <option value"12">12</option>
							</select>
							<select class="form-control" style="display: inline" name="end_min">
					        	<option value"00">00</option>
					            <option value"05">05</option>
					            <option value"10">10</option>
					            <option value"15">15</option>
					            <option value"20">20</option>
					            <option value"25">25</option>
					            <option value"30">30</option>
								<option value"35">35</option>
					            <option value"40">40</option>
					            <option value"45">45</option>
					            <option value"50">50</option>
					            <option value"55">55</option>
							</select>
							<select class="form-control" style="display: inline" name="end_ampm">
					        	<option value="am">AM</option>
					        	<option value="pm">PM</option>
							</select>
						</div>
						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step == 2): ?>
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
					<p>Quantities (where applicable) are shown next to the name.<br />They will be chosen in step 3.</p>
					<hr />

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<input type="hidden" name="step" value="2" />
						<input type="hidden" name="title" value="<?php echo $title ?>" />
						<input type="hidden" name="description" value="<?php echo $description ?>" />
						<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />

						<?php
							if(count($packages) > 0 && count($packages) != count($addedPkgs)){
								// input -- added packages
								if(isset($addedPkgs)){
									foreach($addedPkgs as $addedPkg){
										echo "<input type='hidden' name='addedPkgs[]' value='" . $addedPkg . "' />";
									}
								}

								echo "<h4>Packages</h4>";

								// Buttons for adding packages 
								foreach($packages as $package){
									// only show the ones not added already
									if(!isset($addedPkgs) || !in_array($package->getID(), $addedPkgs)){
										echo "<button style='margin-bottom:10px' class='btn btn-success' type='submit' name='addedPkgs[]' value='" . $package->getID() . "'>" . $package->getTitle() . "</button> &nbsp;";
									}
								}
								echo "<hr />";
							}
	

							foreach($types as $type){
								$items = getAvailableGearWithType($type['gear_type_id'], $co_start, $co_end);
								if (count($items) > 0){
									printf("<h4>%s</h4>",$type['type']);
									foreach($items as $item){
										$qty = availableQty($item['gear_id'], $co_start, $co_end);
										echo "<div class='checkbox'>";
										echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "' ";
										if(isset($preCheck) && in_array($item['gear_id'], $preCheck)) echo "checked"; //added from packages
										echo "> " . $item['name'];
										if($qty > 1){
											echo " (" . $qty . ")";
										}
										echo "</label></div>";	
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
									for($i = 1; $i <= $qty; $i++){
										echo "<option value='$i'>$i</option>";
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