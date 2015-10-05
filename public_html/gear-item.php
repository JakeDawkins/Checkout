<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/Person.php');

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //get initial gear item for prefilling text fields
        $gear_id = test_input($_GET['gear_id']);
        $name = getGearName($gear_id);
        $type = getGearType($gear_id);
        $qty = getTotalGearQty($gear_id);
        if(isset($_GET['deleteGearItem'])){
            $delGearItem = test_input($_GET['deleteGearItem']);    
            deleteGearItem($delGearItem);
            header("Location: inventory.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Gear Item</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php echo "<h1>$name</h1>"; ?>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
            <?php echo "<a href='inventory.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Inventory</a>"; ?>
            <br /><br />
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Gear Details</div>
                    <div class="panel-body text-center">
                        <p>
                        <?php
                            echo "<strong>Gear ID:</strong> " . $gear_id . "<br /><br />";
                            echo "<strong>Name:</strong> " . $name . "<br /><br />";
                            echo "<strong>Type:</strong> " . gearTypeWithID($type) . "<br /><br />";
                            echo "<strong>Quantity:</strong> " . $qty . "<br /><br />";
                            echo "<a class='btn btn-primary' href='edit-gear.php?gear_id=" . $gear_id . "'>Edit</a> &nbsp;&nbsp;";
                            echo "<a class='btn btn-danger' href='gear-item.php?deleteGearItem=" . $gear_id . "'>Delete</a>"; 
                            //TODO 
                            //echo "<strong>Status:</strong> " . $gear_id . "<br />";
                        ?>
                        </p>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Item History</div>
                    <div class="panel-body text-center">
                    <?php
                        $checkouts = fetchCheckoutsWithGear($gear_id);
                        if(count($checkouts) != 0){
                            foreach($checkouts as $checkout){
                                echo "<a href='checkout.php?co_id=" . $checkout['co_id'] . "'>";
                                echo $checkout['title'] . " (" . $checkout['qty'] . ")" . "<br />"; 
                                echo "</a>";
                            }    
                        } else {
                            echo "<h4>No history to report</h4>";
                        }
                        
                    ?>
                    </div>
                </div>
            </div>
        </div>
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
