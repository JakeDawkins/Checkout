<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/funcs.php'); //to fetch details about person
	require_once('models/Person.php');

	$deleted = false;

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
	$gearList = $checkout->getGearList();

	//create array of gear types within this checkout
	$gearTypes = array();
	foreach($gearList as $gear){
		$type = gearTypeWithID(getGearType($gear[0]));
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
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1><?php printf("%s",$checkout->getTitle()); ?></h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
    	<div class="row">
    		<div class="col-sm-8 col-sm-offset-2">
    			<?php echo "<a href=\"checkouts.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a>"; ?>
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
			            	if ($loggedInUser->checkPermission(array(2)) || $loggedInUser->user_id == $checkout->getPerson()){
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
										$name = getGearName($gear[0]);
										$qty = $gear[1];
										$type = gearTypeWithID(getGearType($gear[0]));
										if($type == $gearType){
											if ($qty > 1) echo "$name ($qty)<br />";
											else echo "$name<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
					echo "</div>"; //end row
				} else {//elseif (count($gearTypes) == 2){ //2 across
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
										$name = getGearName($gear[0]);
										$qty = $gear[1];
										$type = gearTypeWithID(getGearType($gear[0]));
										if($type == $gearType){
											if ($qty > 1) echo "$name ($qty)<br />";
											else echo "$name<br />";
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
</body>
</html>