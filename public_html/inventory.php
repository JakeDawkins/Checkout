<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

require_once('models/Checkout.php');
require_once('models/funcs.php');
require_once('models/Gear.php');

$types = getGearTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

	<title>Inventory</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Inventory",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <?php
                    //only show to admins
                    if ($loggedInUser->checkPermission(array(2))){
                        echo "<a class='btn btn-primary' href='new-gear.php'>New Item&nbsp;&nbsp;<span class='glyphicon glyphicon-plus'></span></a> &nbsp;";
                        echo "<a class='btn btn-primary' href='edit-gear-types.php'>Edit Gear Types&nbsp;&nbsp;<span class='glyphicon glyphicon-pencil'></span></a>";
                    }
					
					foreach($types as $type){
                        echo "<hr class='mobileOnly' />";
                        echo "<h3 class='tableTitle' id='" . $type['gear_type_id'] . "'>" . $type['type'] . "</h3>";
						$gearList = Gear::getGearListWithType($type['gear_type_id']);
						if(count($gearList)==0) {
							printf("<p>No gear of this type</p>");
						} else {
				?>
                    <table class="table table-hover" id='table<?php echo $type['gear_type_id']?>'> 
                        <colgroup>
                            <col class="one-quarter">
                            <col class="one-quarter">
                            <col class="one-quarter">
                            <col class="one-quarter">                            
                        </colgroup>
                        <thead>
                            <tr>
                                <!-- <th class="hidden-xs hidden-sm">ID</th> -->
                                <th>Name</th>
                                <th>Status</th>
                                <th>Last Checkout</th>
                                <th class="text-center">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
								foreach($gearList as $gear){
                                    $gearObject = new Gear();
                                    $gearObject->fetch($gear['gear_id']);

									printf("<tr>");
									printf("<td><a href='gear-item.php?gear_id=%s'>%s</a></td>",$gear['gear_id'],$gear['name']);
									echo "<td>" . $gearObject->status() . "</td>";

									$co_id = $gearObject->lastCheckoutID(); //fetchLastCheckout($gear['gear_id']);
									if(!empty($co_id)){
										$co = new Checkout();
					    				$co->retrieveCheckout($co_id);	
					    				printf("<td><a href='checkout.php?co_id=%s'>%s</a></td>", $co_id, getPersonName($co->getPerson()));
									} else { //no last checkout
										printf("<td>n/a</td>");
									}
									printf("<td class='text-center'>%s</td>", $gearObject->getQty()); //$gear['qty']
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

    <script>
        //for hiding full tables initially on mobile devices
        var width = $(document).width(); // returns width of HTML document
        if (width < 740){
            $(".table").css("display","none"); //hide tables initially
            $(".tableTitle").click(function(event){
                //table currently hidden
                if($("#table" + event.target.id).css('display') == "none"){
                    $("#table" + event.target.id).css("display","block");//show table
                } else { //hide table
                    $("#table" + event.target.id).css("display","none");//show table
                }
                
            });
        } else { //desktop/tablet
            $(".mobileOnly").css("display","none");
        }

    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>