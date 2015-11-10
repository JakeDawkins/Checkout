<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/funcs.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Checkouts", NULL); ?>

    <!-- 
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                
            </div>
        </div>
    </div>
    -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
