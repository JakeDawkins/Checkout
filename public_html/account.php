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
    $checkouts = Checkout::getCheckoutsInRange($start, $end);
    foreach($checkouts as $checkout){
        if($checkout->getPerson() != $loggedInUser->user_id){
            //remove from checkouts array if not checked out by user
            unset($checkouts[$checkout]);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Account Home</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Account</h1>
                <!-- <p class="lead">A system for scheduling gear among a team</p> -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <div class="container">
    	<div class="row">
	    	<div class="col-sm-8 col-sm-offset-2">
	    	<?php //echo "Hey, $loggedInUser->displayname. <br />This is an example secure page designed to demonstrate some of the basic features of UserCake. Just so you know, your title at the moment is $loggedInUser->title, and that can be changed in the admin panel. You registered this account on " . date("M d, Y", $loggedInUser->signupTimeStamp()) ?>
	    	<h3>Upcoming Checkouts</h3>
            <br />
                <table class="table table-hover">
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
                                printf("<td class=\"hidden-xs hidden-sm\">");
                                $i = 0; //counter. Only want to show a few items
                                foreach($checkout->getGearList() as $gear){
                                    if ($i > 4){
                                        echo "...<br />"; 
                                        break;
                                    }
                                    $i++;
                                    printf("%s<br>",getGearName($gear));
                                }
                                printf("</td></tr>");
                            }
                        ?>
                    </tbody>
                </table>
            </div>
    	</div>
    </div>

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>