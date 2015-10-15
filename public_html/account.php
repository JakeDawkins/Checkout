<?php
require_once("models/config.php"); //for usercake
if (!securePage($_SERVER['PHP_SELF'])){die();}

    require_once('models/Checkout.php');
    require_once('models/Form.php');
    require_once('models/Gear.php');
    require_once('models/funcs.php'); //to fetch details about person

    //get checkouts in the next 1 month for this person only
    $date = date('Y-m-d'); //pick first of month
    $start = $date;
    $end = strtotime(date("Y-m-d", strtotime($date)) . "+1 month");
    $end = date('Y-m-d', $end);

    $checkouts = array();
    $checkouts = Checkout::getCheckoutsInRangeForPerson($loggedInUser->user_id, $start, $end);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Account Home</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Account",NULL); ?>

    <div class="container">
    	<div class="row">
	    	<div class="col-sm-12">
	    	<h3>Upcoming Checkouts</h3>
            <br />
            <!-- User has checkouts -->
            <?php if(count($checkouts) != 0): ?>
                <table class="table table-hover">
                    <colgroup>
                        <col class="one-sixth">
                        <col class="one-sixth">
                        <col class="one-sixth">
                        <col class="one-sixth">
                        <col class="one-sixth">
                        <col class="one-sixth">                            
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="hidden-xs">Description</th>
                            <th>Person</th>
                            <th>Start</th>
                            <th>End</th>
                            <th class="hidden-xs hidden-sm">Gear</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($checkouts as $checkout){
                                $person = $checkout->getPerson();
                                $personDetails = fetchUserDetails(NULL,NULL,$person);

                                printf("<tr>");
                                printf("<td><a href='checkout.php?co_id=%s'>%s</a></td>",$checkout->getID(),$checkout->getTitle());
                                printf("<td class=\"hidden-xs\">%s</td>",$checkout->getDescription());
                                printf("<td>%s</td>",$personDetails['display_name']);
                                $co_start = new DateTime($checkout->getStart());
                                $co_end = new DateTime($checkout->getEnd());
                                printf("<td>%s</td>",$co_start->format('m-d g:iA'));
                                printf("<td>%s</td>",$co_end->format('m-d g:iA'));
                                printf("<td class=\"hidden-xs hidden-sm\" style='white-space: nowrap'>");
                                $i = 0; //counter. Only want to show a few items
                                foreach($checkout->getGearList() as $gear){
                                    if ($i > 4){
                                        echo "<div class='hide" . $checkout->getID() . "' style='display:none'>" . getGearName($gear[0]) . "</div>";
                                    } elseif ($i==4){
                                        echo "<div class='hide" . $checkout->getID() . "' style='display:none'>" . getGearName($gear[0]) . "</div>";
                                        echo "<div class='unhide' id='" . $checkout->getID() . "'>...</div>";
                                    } else printf("%s<br />",getGearName($gear[0]));
                                    $i++;
                                }
                                printf("</td></tr>");
                            }
                        ?>
                    </tbody>
                </table>
            <!-- User has no checkouts --> 
            <?php else : ?>
                <div class="alert alert-info">
                    You don't have any checkouts assigned to you in the upcoming month.
                    You can go <a href="checkouts.php">here</a> to browse other checkouts, or 
                    <a href="new-checkout.php">here</a> to make a new checkout.
                </div>
            <?php endif; ?>
            </div>
    	</div>
    </div>

    <br /><br />
    
    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <script>
    //for expanding gear lists
    $("div.unhide").click(function(event){
        //alert(event.target.id);
        $("div.hide" + event.target.id).css("display","block");
        $("#" + event.target.id).css("display","none");
    });

    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>