<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Checkout.php');
	require_once('models/Package.php');
	require_once('models/funcs.php');

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
			//TITLE
			//$title = test_input($_POST['title']);
			if (empty($_POST['title'])){
			  $errors[] = "No title provided"; 
			  $step = 0; //don't go on
			} else $title = test_input($_POST['title']);

			//DESCRIPTION
			//$description = test_input($_POST['description']);
			if (empty($_POST['description'])){
			  $errors[] = "No description provided"; 
			  $step = 0; //don't go on
			} else $description = test_input($_POST['description']);

			//location
			//LEN 128
			$location = test_input($_POST['location']);
			if(strlen($location) > 128){ 
				$errors[] = "Location can only be 128 characters long";
				$step = 0; //don't go on
			}

			//DR_Number
			//LEN 32
			$dr_number = test_input($_POST['dr_number']);
			if(strlen($dr_number) > 32) {
				$errors[] = "DR Number can only be 32 characters long";
				$step = 0; //don't go on
			}

			//NEW datetime input 
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
				$errors[] = "The start date/time is after the end date/time";
				$step = 0;
			} elseif($formattedStart == $formattedEnd){
				$errors[] = "The start and end date/times are the same";
				$step = 0;
			}

		} else if ($step == 2){ //step 2 submitted
			//time and date string should be constructed.
			//need to process gear list

			//collect vars from step 1
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);
			$location = test_input($_POST['location']);
			$dr_number = test_input($_POST['dr_number']); 

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
					$gearObject = new Gear();
					$gearObject->fetch(test_input($gearItem));
					if($gearObject->availableQty($co_start, $co_end) > 1){
						$gearToGetQtyFor[] = $gearItem;
					}
				}
			}

			//no packages/gear added
			if(!isset($_POST['addedPkgs']) && !isset($_POST['gear'])){
				$errors[] = "No gear added";
				$step = 1; //don't go on
			}
		} else if ($step == 3){ //step 3 submitted
			//collect vars from step 1
			$title = test_input($_POST['title']);
			$description = test_input($_POST['description']);
			$co_start = test_input($_POST['co_start']);	
			$co_end = test_input($_POST['co_end']);
			$location = test_input($_POST['location']);
			$dr_number = test_input($_POST['dr_number']); 

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
				if($gearObject->availableQty($co_start, $co_end) > 1){
					$co->addToGearList($gearObject->getID(), $gearQty[$i]);
					$i++;
				} else {
					$co->addToGearList($gearObject->getID(), 1);	
				}	
			}

			$co->finalizeCheckout();
			$co_id = $co->getID();
			header("Location: checkout.php?co_id=$co_id");
		}
		//increment step
		$step++;
	}//end if POST
	else {
		$step = 1;
	}


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>New Checkout</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("New Checkout",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<?php echo "<a href='checkouts.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Checkouts</a>";
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
						<div class="form-group"> <!-- LOCATION -->
							<label class="control-label" for="location">Location:</label>  
							<input type="text" class="form-control" name="location" placeholder="optional">
						</div>
						<div class="form-group"> <!-- DR NUMBER -->
							<label class="control-label" for="dr_number">DR Number:</label>  
							<input type="text" class="form-control" name="dr_number" placeholder="optional">
						</div>	
						
						<!-- new datetime input. can be disabled by button (TODO) -->
						<div class="form-group">
							<label class="control-label" for="startDateTime">Check Out:</label>  
							<input class="form-control" name="startDateTime" type="text" data-field="datetime" readonly>	
						</div>
						
						<div class="form-group">
							<label class="control-label" for="endDateTime">Return:</label>  
							<input class="form-control" name="endDateTime" type="text" data-field="datetime" readonly>	
						</div>

						<div id="dtBox"></div>

						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step == 2): ?>
					<h2>Step 1: Event Details</h2>
					<div class="alert alert-info" role="alert">
						<?php
							echo "Title: " . $title . "<br />Description: " . $description . "<br />"; 
							echo "Location: " . $location . "<br />DR Number: " . $dr_number . "<br />";
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
						<input type="hidden" name="location" value="<?php echo $location ?>" />
						<input type="hidden" name="dr_number" value="<?php echo $dr_number ?>" />

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
								$items = Gear::getAvailableGearWithType($type['gear_type_id'], $co_start, $co_end);
								if (count($items) > 0){
									printf("<h4>%s</h4>",$type['type']);
									foreach($items as $item){
										$gearObject = new Gear();
										$gearObject->fetch($item['gear_id']);
										$qty = $gearObject->availableQty($co_start, $co_end);
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
								echo "<br />";
							}//foreach
						?>
						<br />
						<input class="btn btn-success" type="submit" name="submit" value="Next">
					</form>
				<?php elseif($step == 3): ?>
					<h2>Step 1: Event Details</h2>
					<div class="alert alert-info" role="alert">
						<?php
							echo "Title: " . $title . "<br />Description: " . $description . "<br />"; 
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
						<input type="hidden" name="step" value="3" />
						<input type="hidden" name="title" value="<?php echo $title ?>" />
						<input type="hidden" name="description" value="<?php echo $description ?>" />
						<input type="hidden" name="co_start" value="<?php echo $co_start ?>" />
						<input type="hidden" name="co_end" value="<?php echo $co_end ?>" />
						<input type="hidden" name="location" value="<?php echo $location ?>" />
						<input type="hidden" name="dr_number" value="<?php echo $dr_number ?>" />
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