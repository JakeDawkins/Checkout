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

            $('#calendar').fullCalendar({
                defaultDate: '2015-02-12',
                editable: false, //don't want dragging events
                eventLimit: true, // allow "more" link when too many events
                events: {
                    url: 'php/get-events.php',
                    error: function() {
                        $('#script-warning').show();
                    }
                },
                loading: function(bool) {
                    $('#loading').toggle(bool);
                }

                events: [
                    {
                        title: 'All Day Event',
                        start: '2015-02-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2015-02-07',
                        end: '2015-02-10'
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: '2015-02-09T16:00:00'
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: '2015-02-16T16:00:00'
                    },
                    {
                        title: 'Conference',
                        start: '2015-02-11',
                        end: '2015-02-13'
                    },
                    {
                        title: 'Meeting',
                        start: '2015-02-12T10:30:00',
                        end: '2015-02-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2015-02-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2015-02-12T14:30:00'
                    },
                    {
                        title: 'Happy Hour',
                        start: '2015-02-12T17:30:00'
                    },
                    {
                        title: 'Dinner',
                        start: '2015-02-12T20:00:00'
                    },
                    {
                        title: 'Birthday Party',
                        start: '2015-02-13T07:00:00'
                    },
                    {
                        title: 'Click for Google',
                        url: 'http://google.com/',
                        start: '2015-02-28'
                    }
                ]
            });
            
        });
    </script>

    <style>
        #calendar {
            max-width: 100%;
            min-width: 100%;
            margin: 0 auto;
        }
    </style>

	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Checkouts", NULL); ?>

    <div class="container">
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
