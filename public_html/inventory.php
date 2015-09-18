<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}
	//require_once("models/header.php");

	require_once('models/Checkout.php');
	require_once('models/Form.php');
	require_once('models/Gear.php');
	require_once('models/Person.php');

	$types = getGearTypes();

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if(isset($_GET['deleteGearItem'])){
			$gearItem = test_input($_GET['deleteGearItem']);	
			deleteGearItem($gearItem);
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

	<title>Inventory</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Inventory</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
					<a class="btn btn-primary" href="new-gear.php">New Item&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
					<a class="btn btn-primary" href="edit-gear-types.php">Edit Gear Types&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span></a>

					<?php
						foreach($types as $type){
							printf("<h3>%s</h3>",$type['type']);
							$gearList = getGearListWithType($type['type']);
							if(count($gearList)==0) {
								//printf("<p>No gear of this type</p>");
							} else {
					?>
                    <table class="table table-hover"> 
                        <colgroup>
                            <col class="one-quarter">
                            <col class="one-quarter">
                            <col class="one-quarter">
                            <col class="one-quarter">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="hidden-xs hidden-sm">ID</th>
                                <th>Name</th>
                                <th>Last Checkout</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
								foreach($gearList as $gear){
									printf("<tr>");
									printf("<td class=\"hidden-xs hidden-sm\">%s</td>",$gear['gear_id']);
									printf("<td>%s</td>",$gear['name']);

									$co_id = fetchLastCheckout($gear['gear_id']);
									if(!empty($co_id)){
										$co = new Checkout();
					    				$co->retrieveCheckout($co_id);	
					    				printf("<td><a href='checkout.php?co_id=%s'>%s</a></td>",$co_id,getPersonName($co->getPerson()));
									} else { //no last checkout
										printf("<td>n/a</td>");
									}
									printf("<td class=\"text-center\"><a href=\"inventory.php?deleteGearItem=%s\"><span class=\"glyphicon glyphicon-remove\"></span></a></td>",$gear['gear_id']);	
									printf("</tr>");
								}
							?>
                        </tbody>
                    </table>
                    <?php } } //foreach ?>
                </form>
            </div>
        </div>
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