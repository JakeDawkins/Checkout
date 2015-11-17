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
    <!-- CALENDAR ASSETS -->
    <link href='vendor/calendar/fullcalendar.css' rel='stylesheet' />
    <link href='vendor/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='vendor/calendar/lib/moment.min.js'></script>
    <script src='vendor/calendar/lib/jquery.min.js'></script>
    <script src='vendor/calendar/fullcalendar.min.js'></script>

    <!-- CALENDAR SETUP -->
    <script>
        $(document).ready(function() {
            var today = new Date();
            today.toISOString().substring(0, 10);

            $('#calendar').fullCalendar({
                defaultDate: today,
                editable: false, //don't want dragging events
                eventLimit: true, // allow "more" link when too many events
                events: {
                    url: 'models/calendar-events.php'
                },
            });
            
        });
    </script>


	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Checkouts</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Checkouts", NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="new-checkout.php">New Checkout&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
                <a class="btn btn-success" href="checkouts-table.php">View as Table</a>
                <br /><br />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id='calendar'></div>          
            </div>
        </div>
    </div>

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <!-- DONT LOAD TWICE -->
    <!-- <script src="js/jquery.js"></script>-->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
