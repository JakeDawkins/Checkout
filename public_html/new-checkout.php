<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	//require_once('model/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/Form.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$co_start = $co_end = $title = $description = "";
	$displayGear = false;

	//form pt. 1 submitted
	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$title = test_input($_POST['title']);
		$description = test_input($_POST['description']);
		$co_start = test_input($_POST['co_start']);	
		$co_end = test_input($_POST['co_end']);

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

		if(!isset($_POST['gear'])){ //pt 1 submitted
			$co_start = $start_year . "-" . $start_month . "-" . $start_day . " " . $start_hour . ":" . $start_min . ":00"; 	
			$co_end = $end_year . "-" . $end_month . "-" . $end_day . " " . $end_hour . ":" . $end_min . ":00"; 
		} else { //pt 2 submitted
			//create checkout object
			$co = new Checkout();

			$co->setTitle($title);
			$co->setPerson($loggedInUser->user_id);
			$co->setStart($co_start);
			$co->setEnd($co_end);
			$co->setDescription($description);

			foreach ($_POST['gear'] as $gearItem) {
				$cleanGearItem = test_input($gearItem);
				$co->addToGearList($cleanGearItem);
			}

			$co->finalizeCheckout();
			$co_id = $co->getID();
			header("Location: checkout.php?co_id=$co_id");
		}
	}

	//need both start and end in order to list available gear
	if(!empty($co_start) && !empty($co_end)) $displayGear = true;

	//------------------------ Validation ------------------------
	//only check when submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //submitted
		if(empty($title)){
			$errors[] = "Please enter a title";
			$displayGear = false;
		}
		if (empty($description)){
			$errors[] = "Please enter a description";
			$displayGear = false;
		}
		if($co_start != $co_end){ //user modified start/end fields
			if ($co_start > $co_end) {
				//dates not in right order
				$errors[] = "Date Error: The start date is after the end date";
				$displayGear = false;
			}
		} else {
			$errors[] = "Date Error: The start and end dates & times are the same";
			$displayGear = false;
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
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <?php echo resultBlock($errors,$successes); ?>
				<?php if($displayGear == false): ?>

					<?php
						if (isset($error)){
							echo '<p class="error">';
							if (isset($error['date'])) printf("%s<br />",$error['date']);
							echo '</p>';
						}
					?>

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<h2>Checkout Details</h2>
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
							<!-- <input type="text" class="form-control" name="co_start" placeholder="yyyy-mm-dd hh:mm:ss"> -->
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
				<?php else: ?>
					<h2>Checkout Details</h2>
					<hr />

					<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<div class="form-group"> <!-- TITLE -->
							<label class="control-label" for="title">Event Title:</label>  
							<input type="text" class="form-control" name="title" placeholder="<?php echo $title ?>" disabled />
								<input type="hidden" name="title" value="<?php echo $title ?>" />
						</div>
						<div class="form-group"> <!-- DESC -->
							<label class="control-label" for="Description">Description:</label>  
							<textarea class="form-control" name="description" rows="3" disabled=""><?php echo $description ?></textarea>
								<input type="hidden" name="description" value="<?php echo $description ?>" />
						</div>
						<div class="form-group"><!-- START -->
							<label class="control-label" for="co_start">Start:</label>  
							<input type="text" class="form-control" name="co_start" placeholder="<?php echo $co_start ?>" disabled />
								<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						</div>
						<div class="form-group"> <!-- END -->
							<label class="control-label" for="co_end">End:</label>  
							<input type="text" class="form-control" name="co_end" placeholder="<?php echo $co_end ?>" disabled />
								<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />
						</div>

						<br />
						<h2>Select Gear</h2>
						<hr />

						<?php
							foreach($types as $type){
								$items = getAvailableGearWithType($type['gear_type_id'], $co_start, $co_end);
								if (count($items) > 0){
									printf("<h3>%s</h3>",$type['type']);
									foreach($items as $item){
										echo "<div class='checkbox'>";
										//printf("<input type=\"checkbox\" name=\"gear[]\" value=\"%s\"> %s<br />",$item['gear_id'],$item['name']);
										echo "<label><input type='checkbox' name='gear[]' value='" . $item['gear_id'] . "'> " . $item['name'] . "</label>";
										echo "</div>";
									}
								}//if
							}//foreach
						?>
						<br />
						<input class="btn btn-success" type="submit" name="submit" value="Finish">
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