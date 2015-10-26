<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
	require_once('models/funcs.php');

	$types = getGearTypes();

	//process each variable
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$submitted = true;

		//NAME
		if (empty($_POST['name'])){
		  $errors[] = "No name provided"; 
		} else $name = test_input($_POST['name']);

		//QTY
		//allowed empty.
		if (empty($_POST['qty'])){
			$qty = 1; //default qty
		} else {
			$qty = test_input($_POST['qty']);
			if(!is_numeric($qty) || $qty < 1) $errors[] = "Quantity must be a number larger than 0";
		}
		
		// check if Category only contains letters and whitespace
		if(!empty($_POST['newCategory'])){ //user provided a new category
			$newCategory = test_input($_POST['newCategory']);
			if (!preg_match("/^[a-zA-Z ]*$/",$newCategory)) {
				$errors[] = "Category name can only contain letters, numbers, and spaces"; 
			} else $category = newGearType($newCategory); //create category in DB
		} else { //new category empty. Use previous category
			$category = test_input($_POST['category']);
		}
		
		if(!empty($_POST['notes'])){
			$notes = test_input($_POST['notes']);	
		}

		if(empty($errors)){
			$gearObject = new Gear();
			$gearObject->setName($name);
			$gearObject->setType($category);
			$gearObject->setQty($qty);
			$gearObject->setIsDisabled(false);
			$gearObject->setNotes($notes);
			$gearObject->finalize();

			$successes[] = "New gear item, <a href='gear-item.php?gear_id=" . $gearObject->getID() . "'>" . $name . "</a>, added!" ;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

	<title>New Gear Item</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("New Item",NULL); ?>

    <div class="container">
	    <div class="row">
	        <div class="col-sm-6 col-sm-offset-3">
    			<?php echo "<a href='inventory.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Inventory</a>"; ?>
    			<br /><br />

	        	<?php echo resultBlock($errors,$successes); ?>
				
				<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
					
					<div class="form-group">
						<label class="control-label" for="name">Name:</label>
						<input class="form-control" name="name" type="text" />
					</div>

					<div class="form-group">
						<label class="control-label" for="qty">Quantity:</label>
						<input class="form-control" name="qty" type="text" placeholder="1"/>
					</div>

					<div class="form-group">
						<label class="control-label" for="category">Choose a category:</label>
						<select class="form-control" name="category">
						<?php
							foreach($types as $type){
								printf("<option value='%s'>%s</option>",$type['gear_type_id'],$type['type']);
							}
						?>
						</select>
					</div>

					<div class="form-group">
						<label class="control-label" for="newCategory">Or create a new category:</label>
						<input class="form-control" name="newCategory" type="text" />
					</div>

					<div class="form-group">
						<label class="control-label" for="notes">Add Notes:</label>  
						<textarea class="form-control" name="notes" rows="3"></textarea>
					</div>

					<input class="btn btn-success" type="submit" name="submit" value="Submit" />
				</form>
	        </div> <!-- END COL -->
        </div> <!-- END ROW --> 
    </div><!-- END CONTAINER -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>