<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/funcs.php');
	require_once('models/Gear.php');
	require_once('models/Checkout.php');

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //get initial gear item for prefilling text fields
        $gear_id = test_input($_GET['gear_id']);
        $gearObject = new Gear();
        $gearObject->fetch($gear_id);

        if(isset($_GET['deleteGearItem'])){
            $gearObject->delete();
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
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader($gearObject->getName(), NULL); ?>

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
                            echo "<strong>Name:</strong> " . $gearObject->getName() . "<br /><br />";
                            echo "<strong>Type:</strong> " . gearTypeWithID($gearObject->getType()) . "<br /><br />";
                            echo "<strong>Quantity:</strong> " . $gearObject->getQty() . "<br /><br />";
                            if(!$gearObject->isDisabled()) {
                                echo "<strong>Enabled </strong><span class='glyphicon glyphicon-ok'></span><br /><br />";
                            } else {
                                echo "<strong>Disabled </strong><span class='glyphicon glyphicon-remove'></span><br /><br />";
                            }
                            if(!empty($gearObject->getNotes())) echo "<strong>Notes:</strong> <pre>" . $gearObject->getNotes() . "</pre><br />";

                            //only show to admins
                            if ($loggedInUser->checkPermission(array(2))){
                                echo "<a class='btn btn-primary' href='edit-gear.php?gear_id=" . $gear_id . "'>Edit</a> &nbsp;&nbsp;";
                                echo "<a class='btn btn-danger' href='gear-item.php?gear_id=" . $gear_id . "&deleteGearItem=" . $gear_id . "'>Delete</a>"; 
                            }

                        ?>
                        </p>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Item History</div>
                    <div class="panel-body text-center">
                    <?php
                        $checkouts = $gearObject->fetchCheckoutsWithGear();
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
