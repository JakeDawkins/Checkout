<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Checkout.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person

	$deleted = false;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$co_id = test_input($_POST['co_id']);
		$dateTime = test_input($_POST['dateTime']);
	}

	//check to see what checkout and if it needs to be deleted
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$co_id = test_input($_GET['co_id']);
		if($_GET['delete']){
			Checkout::removeCheckout($co_id);
			$deleted = true;
			header("Location: checkouts.php?co_del=true");
		}
	}

	//pull checkout and gearlist
	$checkout = new Checkout();
	$checkout->retrieveCheckout($co_id);

	if(isset($dateTime)){
		$checkout->setReturned($dateTime);
		$checkout->finalizeCheckout();
	}

	$gearList = $checkout->getGearList();

	//create array of gear types within this checkout
	$gearTypes = array();
	foreach($gearList as $gear){
		$gearObject = new Gear();
		$gearObject->fetch($gear[0]);
		$type = gearTypeWithID($gearObject->getType()); //gearTypeWithID(getGearType($gear[0]));
		if (!in_array($type, $gearTypes)){
			$gearTypes[] = $type;
		}
	}//foreach
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

	<title>Checkout</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader($checkout->getTitle(),NULL); ?>

    <div class="container">
    	<div class="row">
    		<div class="col-sm-8 col-sm-offset-2">
    			<?php echo "<a href='checkouts.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Checkouts</a>"; ?>
    			<br /><br />
    			<div class="panel panel-default">
    				<div class="panel-heading text-center">Checkout Details</div>
    				<div class="panel-body text-center">
    					<p>
    					<?php
    						printf("<strong>Checkout ID:</strong> %s<br /><br />",$checkout->getID());
							printf("<strong>Description:</strong> %s<br /><br />",$checkout->getDescription());

							$personName = getPersonName($checkout->getPerson());
							printf("<strong>Person:</strong> %s<br /><br />",$personName);
							$co_start = new DateTime($checkout->getStart());
							$co_end = new DateTime($checkout->getEnd());
							printf("<strong>Start Time:</strong> %s<br /><br />",$co_start->format('m-d-y g:iA'));
							printf("<strong>End Time:</strong> %s<br /><br />",$co_end->format('m-d-y g:iA'));
							if($checkout->getReturned()){
								$retDate = new DateTime($checkout->getReturned());
								echo "<strong>Returned:</strong> " . $retDate->format('m-d-y g:iA') . "<br /><br />";
							} 
			            	if ($loggedInUser->checkPermission(array(2)) || $loggedInUser->user_id == $checkout->getPerson()){
			            		echo "<form id='retForm' role='form' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>";
			            			echo "<input type='hidden' name='co_id' value='" . $co_id . "' />";
			            			echo "<input id='now' type='hidden' name='dateTime' />"; //fill with JS on button click
			            		echo "</form>";
				            	echo "<a class='btn btn-primary' href='edit-checkout.php?co_id=" . $co_id . "'>Edit</a> &nbsp;&nbsp;";
	                            echo "<a class='btn btn-danger' href='checkout.php?co_id=" . $co_id . "&delete=true'>Delete</a>";	
			            	} 
    					?>
    					</p>
    				</div>
    			</div>
    		</div>
    	</div>

    	<!-- PRINT OUT CHECKOUT GEAR --> 
    	<!-- <div class="row"> -->
			<?php
				if(count($gearTypes) == 1){ //1 across
					echo "<div class='row'>";
					foreach($gearTypes as $gearType){
						echo "<div class='col-sm-8 col-sm-offset-2'>";
							echo "<div class='panel panel-default'>";
								echo "<div class='panel-heading text-center'>" . $gearType . "</div>";
								echo "<div class='panel-body text-center'>";
									echo "<p>";
									foreach($gearList as $gear){
										$gearObject = new Gear();
										$gearObject->fetch($gear[0]);
										$qty = $gear[1];
										$type = gearTypeWithID($gearObject->getType()); //gearTypeWithID(getGearType($gear[0]));
										if($type == $gearType){
											if ($qty > 1) echo $gearObject->getName() . " ($qty)<br />";
											else echo $gearObject->getName() . "<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
					echo "</div>"; //end row
				} else { // 2 across
					$i = 0;
					foreach($gearTypes as $gearType){
						if($i % 2 == 0){ //start row
							echo "<div class='row'>";
							echo "<div class='col-sm-4 col-sm-offset-2'>";	
						} 
						else echo "<div class='col-sm-4'>";
							echo "<div class='panel panel-default'>";
								echo "<div class='panel-heading text-center'>" . $gearType . "</div>";
								echo "<div class='panel-body text-center'>";
									echo "<p>";
									foreach($gearList as $gear){
										$gearObject = new Gear();
										$gearObject->fetch($gear[0]);
										$qty = $gear[1];
										$type = gearTypeWithID($gearObject->getType()); //gearTypeWithID(getGearType($gear[0]));
										if($type == $gearType){
											if ($qty > 1) echo $gearObject->getName() . " ($qty)<br />";
											else echo $gearObject->getName() . "<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col

						//if second col on row or last col, end the row
						if($i % 2 == 1 || $i == count($gearTypes)-1){
							echo "</div>"; //end row
						}
						$i++;
					}
				}
			?>
    	<!-- </div> END ROW -->
    </div>

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- handle early check-in. Only available with JS -->
    <?php 
	    $co_start = new DateTime($checkout->getStart());
		$co_end = new DateTime($checkout->getEnd());
		if(!$checkout->getReturned()) echo "<span id='show' style='display:none;'>true</span>";
	    echo "<span id='co_start' style='display:none'>" . $co_start->getTimestamp() . "</span>";
	    echo "<span id='co_end' style='display:none'>" . $co_end->getTimestamp() . "</span>";
    ?>
    <script>
	    //fill the form if js available.
	    //don't show if not within checkout time
	    if ($("#show").text() == "true"){
	   		var start = $("#co_start").text();
		    var end = $("#co_end").text();
		    var now = new Date().getTime();
		    var strNow = String(now).substring(0, String(now).length-3);
		    now = Number(strNow);
	    }
	    //document.write(start + " " + end + " " + now + "<br />")

	    if(start < now && now < end){
		    $("#retForm").append("<input id='retBtn' class='btn btn-success' type='submit' name='submit' value='Check In Early'><br /><br />");
		    $("#retBtn").click(function(){
		    	//generate now() string
		        var d = new Date();
		        var dateTime = "" + d.getFullYear() + "-" + ('0' + (d.getMonth()+1)).slice(-2) + "-" + ('0' + d.getDate()).slice(-2) + " ";
		        dateTime += ('0' + d.getHours()).slice(-2) + ":" + ('0' + d.getMinutes()).slice(-2) + ":" + ('0' + d.getSeconds()).slice(-2);

		        //fill hidden input
		    	$("#now").val(dateTime);
		    });
	    }
    </script>




</body>
</html>