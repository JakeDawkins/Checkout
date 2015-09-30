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
		$type = gearTypeWithID(getGearType($gear));
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
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
    	<div class="row">
    		<div class="col-lg-12">
    			<?php echo "<a href=\"checkouts.php\"><span class=\"glyphicon glyphicon-chevron-left\"></span>&nbsp;&nbsp;Back to Checkouts</a>"; ?>
    			<br /><br />
    			<div class="panel panel-default">
    				<div class="panel-heading text-center">
    					<h3>Checkout Details</h3>
    				</div>
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
							//printf("<a href=\"checkout.php?co_id=%s&delete=true\">Delete This Checkout</a>",$co_id);

    					?>
    					</p>
    					<a class ="btn btn-danger" href='<?php printf("checkout.php?co_id=%s&delete=true",$co_id); ?>'>Delete This Checkout</a>
    				</div>
    			</div>
    		</div>
    	</div>

    	<!-- PRINT OUT CHECKOUT GEAR --> 
    	<div class="row">
			<?php
				//if #types == 1, 1 across
				//			== 2, 2 across
				//			% 3 == 0, 3 across
				//			% 4 == 0, 4 across
				//			else 3 across

				if(count($gearTypes) == 1){ //1 across
					foreach($gearTypes as $gearType){
						echo "<div class=\"col-lg-12\">";
							echo "<div class=\"panel panel-default\">";
								echo "<div class=\"panel-heading text-center\">";
									echo "$gearType";
								echo "</div>"; //end heading
								echo "<div class=\"panel-body text-center\">";
									echo "<p>";
									foreach($gearList as $gear){
										$name = getGearName($gear);
										$type = gearTypeWithID(getGearType($gear));
										if($type == $gearType){
											echo "$name<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
				} elseif (count($gearTypes) == 2){ //2 across
					foreach($gearTypes as $gearType){
						echo "<div class=\"col-lg-6 col-md-6 col-sm-6\">";
							echo "<div class=\"panel panel-default\">";
								echo "<div class=\"panel-heading text-center\">";
									echo "$gearType";
								echo "</div>"; //end heading
								echo "<div class=\"panel-body text-center\">";
									echo "<p>";
									foreach($gearList as $gear){
										$name = getGearName($gear);
										$type = gearTypeWithID(getGearType($gear));
										if($type == $gearType){
											echo "$name<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
				} elseif (count($gearTypes) % 4 == 0){ //4 across
					foreach($gearTypes as $gearType){
						echo "<div class=\"col-lg-3 col-md-3 col-sm-6\">";
							echo "<div class=\"panel panel-default\">";
								echo "<div class=\"panel-heading text-center\">";
									echo "$gearType";
								echo "</div>"; //end heading
								echo "<div class=\"panel-body text-center\">";
									echo "<p>";
									foreach($gearList as $gear){
										$name = getGearName($gear);
										$type = gearTypeWithID(getGearType($gear));
										if($type == $gearType){
											echo "$name<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
				} else { //3 across
					foreach($gearTypes as $gearType){
						echo "<div class=\"col-lg-4 col-md-4 col-sm-6\">";
							echo "<div class=\"panel panel-default\">";
								echo "<div class=\"panel-heading text-center\">";
									echo "$gearType";
								echo "</div>"; //end heading
								echo "<div class=\"panel-body text-center\">";
									echo "<p>";
									foreach($gearList as $gear){
										$name = getGearName($gear);
										$type = gearTypeWithID(getGearType($gear));
										if($type == $gearType){
											echo "$name<br />";
										}
									}
									echo "</p>";
						echo "</div></div></div>"; //panel-body //panel //col
					}
				}
			?>
    	</div><!-- END ROW -->
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