<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Checkout.php');
	require_once('models/funcs.php');

	$types = getGearTypes();

	//define variables and set to empty values
	$co_start = $co_end = $title = $description = $location = $dr_number = "";

	//get initial checkout info
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);

		$co = new Checkout();
		$co->retrieveCheckout($co_id);

		//user does not have permission to edit this checkout
		if (!$loggedInUser->checkPermission(array(2)) && $loggedInUser->user_id != $co->getPerson()){
			header("Location: checkout.php?co_id=" . $co_id);
		}

		$co_start = $co->getStart();
		$co_end = $co->getEnd();
		$co_start = new DateTime($co_start);
		$co_end = new DateTime($co_end);

		//error message handling from step 1 submission
		if(!empty($_GET['errors'])){
			$errorCode = test_input($_GET['errors']);
			if($errorCode == "dates_order"){
				$errors[] = "The start date/time is after the end date/time";
			} elseif($errorCode == "dates_same"){
				$errors[] = "The start and end date/times are the same";
			}
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
			//placeholder text. allowed empty
			$title = test_input($_POST['title']);

			//allowed null
			$description = test_input($_POST['description']);

			//location
			//LEN 128
			$location = test_input($_POST['location']);
			if(strlen($location) > 128){ 
				$errors[] = "Location can only be 128 characters long";
				$validLocation = false;
				$step = 0; //don't go on
			} $validLocation = true;

			//DR_Number
			//LEN 32
			$dr_number = test_input($_POST['dr_number']);
			if(strlen($dr_number) > 32) {
				$errors[] = "DR Number can only be 32 characters long";
				$validDR = false;
				$step = 0; //don't go on
			} else $validDR = true;

			//cannot be null
			if (empty($_POST['co_id'])){
				$errors[] = "Checkout ID invalid"; 
			} else $co_id = test_input($_POST['co_id']);

			//NEW datetime entry
			$co_start = test_input($_POST['startDateTime']);
			$co_start = new DateTime($co_start);
			$co_start = $co_start->format('Y-m-d H:i:s');
			$co_end = test_input($_POST['endDateTime']);
			$co_end = new DateTime($co_end);
			$co_end = $co_end->format('Y-m-d H:i:s');

			//check to make sure dates in order 
			$formattedStart = new DateTime($co_start);
			$formattedEnd = new DateTime($co_end);
			if($formattedStart > $formattedEnd){
				header("Location: edit-checkout.php?co_id=" . $co_id . "&errors=dates_order");
			} elseif($formattedStart == $formattedEnd){
				header("Location: edit-checkout.php?co_id=" . $co_id . "&errors=dates_same");
			} else $validDates = true;

			//see if we need to change anything
			$co = new Checkout();
			$co->retrieveCheckout($co_id);

			//construct a simple gearList
			$simpleGearList = array();
			foreach($co->getGearList() as $gear){
				$simpleGearList[] = $gear[0];
			}

			if(!empty($title) && $co->getTitle() != $title){ //title change
				$co->setTitle($title);
				$successes[] = "Title updated successfully";
			} else $title = $co->getTitle();

			if($co->getDescription() != $description){ //description change
				$co->setDescription($description);
				$successes[] = "Description updated successfully";
			} else $description = $co->getDescription();

			if($co->getLocation() != $location){//location change
				if($validLocation){
					$co->setLocation($location);
					$successes[] = "Location updated successfully";	
				} else $location = $co->getLocation();
			} else $location = $co->getLocation();

			if($co->getDRNumber() != $dr_number){//DR change
				if($validDR){
					$co->setDRNumber($dr_number);
					$successes[] = "DR Number updated successfully";	
				} else $dr_number = $co->getDRNumber();
			} else $dr_number = $co->getDRNumber();

			if(!empty($co_start) && $co->getStart() != $co_start){ //start change
				if($validDates){
					$co->setStart($co_start);
					$successes[] = "Start time/date updated successfully";
				} else $co_start = $co->getStart();
			} else $co_start = $co->getStart();

			if(!empty($co_end) && $co->getEnd() != $co_end){// end change
				if($validDates){
					$co->setEnd($co_end);
					$successes[] = "End time/date updated successfully";
				} else $co_end = $co->getEnd();
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
			$location  = test_input($_POST['location']);
			$dr_number  = test_input($_POST['dr_number']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);

			//construct a clean gear list
			if(empty($_POST['gear'])){
				$errors[] = "The checkout has no gear";
				$step = 1;
			} else {
				$gearList = array();
				$gearToGetQtyFor = array();
				foreach ($_POST['gear'] as $gearItem) {
					$gearList[] = test_input($gearItem);

					$gearObject = new Gear();
					$gearObject->fetch(test_input($gearItem));

					//if the available qty is > 1, we need to find out
					//what qty the user wants to check out
					if($gearObject->availableQtyExcludingCheckout($co_id , $co_start, $co_end) > 1){
						$gearToGetQtyFor[] = test_input($gearItem);
					}
				}				
			}

		} //------------------------ STEP 3 SUBMITTED ------------------------ 
		else if ($step == 3){
			//collect vars from step 1
			$co_id = test_input($_POST['co_id']);
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$location  = test_input($_POST['location']);
			$dr_number  = test_input($_POST['dr_number']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);

			//construct a clean gear list
			$gearList = array();
			foreach ($_POST['gear'] as $gearItem) {
				$gearList[] = test_input($gearItem);
			}
			
			//clean up gear qty array from post
			$gearQty = array();
			if(!empty($_POST['gearQty'])){
				foreach ($_POST['gearQty'] as $qty) {
					$gearQty[] = test_input($qty);
				}				
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
			$co->setLocation($location);
			$co->setDRNumber($dr_number);

			foreach ($gearList as $gearItem) {
				$gearObject = new Gear();
				$gearObject->fetch($gearItem);
				//echo ">1";
				if($gearObject->availableQty($co_start, $co_end) > 1){
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
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Edit Checkout",NULL); ?>

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
							<input type="text" class="form-control" name="title" value="<?php echo $co->getTitle(); ?>">
						</div>
						<div class="form-group"> <!-- DESC -->
							<label class="control-label" for="description">Description:</label>  
							<textarea class="form-control" name="description" rows="3"><?php echo $co->getDescription(); ?></textarea>
						</div>
						<div class="form-group"> <!-- LOCATION -->
							<label class="control-label" for="location">Location:</label>  
							<input type="text" class="form-control" name="location" value="<?php echo $co->getLocation(); ?>">
						</div>
						<div class="form-group"> <!-- DR NUMBER -->
							<label class="control-label" for="dr_number">DR Number:</label>  
							<input type="text" class="form-control" name="dr_number" value="<?php echo $co->getDRNumber(); ?>">
						</div>
						<div class="alert alert-warning">
						Changing the date of a checkout may change what gear is available for checkout.
						</div>

						<!-- new dateTime input -->
						<div class="form-group">
							<label class="control-label" for="startDateTime">Check Out:</label>  
							<input class="form-control" name="startDateTime" type="text" data-field="datetime" value="<?php echo $co_start->format('d-m-Y H:i'); ?>" readonly>	
						</div>
						
						<div class="form-group">
							<label class="control-label" for="endDateTime">Return:</label>  
							<input class="form-control" name="endDateTime" type="text" data-field="datetime" value="<?php echo $co_end->format('d-m-Y H:i'); ?>" readonly>	
						</div>

						<div id="dtBox"></div>

						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step==2): ?>
					<h2>Step 1: Event Details</h2>
					<div class="alert alert-info" role="alert">
						<?php
							echo "Title: " . $co->getTitle() . "<br /> Description: " . $co->getDescription() . "<br />"; 
							echo "Location: " . $location . "<br />DR Number: " . $dr_number . "<br />";
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
						<input type="hidden" name="location" value="<?php echo $location ?>" />
						<input type="hidden" name="dr_number" value="<?php echo $dr_number ?>" />
						<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />
						<?php
							foreach($types as $type){
								$items = Gear::getAvailableGearWithTypeAndExclusions($type['gear_type_id'], $co_id, $co_start, $co_end);
								if (count($items) > 0){
									printf("<h4>%s</h4>",$type['type']);
									foreach($items as $item){
										$gearObject = new Gear();
										$gearObject->fetch($item['gear_id']);

										$qty = $gearObject->availableQtyExcludingCheckout($co_id, $co_start, $co_end);
										echo "<div class='checkbox'>";
										echo "<label><input type='checkbox' name='gear[]' value='" . $gearObject->getID() . "'";
										//if the gear item is already assigned to checkout, check it by default
										if(isset($simpleGearList) && in_array($gearObject->getID(), $simpleGearList)) {
											echo "checked > " . $gearObject->getName();
										} else {
											echo "> " . $gearObject->getName();
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
							echo "Location: " . $location . "<br />DR Number: " . $dr_number . "<br />";
							$formattedStart = new DateTime($co_start);
							$formattedEnd = new DateTime($co_end);
							echo $formattedStart->format('m-d-y g:iA') . " <span class='glyphicon glyphicon-arrow-right'></span> " . $formattedEnd->format('m-d-y g:iA');
						?>
					</div>
					<h2>Step 2: Select Gear</h2>
					<div class="alert alert-info" role="alert">
						<?php
							foreach($gearList as $gear){
								$gearObject = new Gear();
								$gearObject->fetch($gear);
								echo $gearObject->getName() . "<br />";
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
						<input type="hidden" name="location" value="<?php echo $location ?>" />
						<input type="hidden" name="dr_number" value="<?php echo $dr_number ?>" />
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
									$gearObject = new Gear();
									$gearObject->fetch($gear);

									echo $gearObject->getName() . "&nbsp;&nbsp;&nbsp;";
									echo "<select name='gearQty[]'>";
									$qty = $gearObject->availableQty($co_start, $co_end);
									$currQty = $co->qtyOfItem($gearObject->getID());
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
            </div> <!-- end col -->
        </div><!-- end row --> 
    </div> <!-- end container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- imports for datetime -->
	<link rel="stylesheet" type="text/css" href="vendor/DateTimePicker/dist/DateTimePicker.css" />
	<script type="text/javascript" src="vendor/DateTimePicker/dist/DateTimePicker.js"></script>
		
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="DateTimePicker-ltie9.css" />
		<script type="text/javascript" src="DateTimePicker-ltie9.js"></script>
	<![endif]-->

	<!-- For i18n Support -->
	<script type="text/javascript" src="vendor/DateTimePicker/dist/i18n/DateTimePicker-i18n.js"></script>
	
	<!-- custom js for working with the picker -->
	<script type="text/javascript" src="vendor/DateTimePickerLoader.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>