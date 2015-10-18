<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/Person.php');

    $co = new Checkout();
    $co->retrieveCheckout(60);
    echo $co->printObject();
    $co->setReturned("2015-10-14 07:50:00");
    echo $co->printObject();
    $co->finalizeCheckout();
    //$co->finalizeCheckout();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>
</head>
<body>
<p id="demo"></p>
<input type="hidden" id="curr" name="returned" />
    <script>
        var d = new Date();
        //var monthString = ('0' + (d.getMonth()+1)).slice(-2);
        //var dayString = ('0' + d.getDate()).slice(-2);
        //var hourString = ('0' + d.getHours()).slice(-2);
        //var minString = ('0' + d.getMinutes()).slice(-2);
        //var secString = ('0' + d.getSeconds()).slice(-2); 
        var dateTime = "" + d.getFullYear() + "-" + ('0' + (d.getMonth()+1)).slice(-2) + "-" + ('0' + d.getDate()).slice(-2) + " ";
        dateTime += ('0' + d.getHours()).slice(-2) + ":" + ('0' + d.getMinutes()).slice(-2) + ":" + ('0' + d.getSeconds()).slice(-2);
        document.getElementById("demo").innerHTML = dateTime;
        document.getElementById("curr").setAttribute("value",dateTime);

    </script>


</body>
</html>
