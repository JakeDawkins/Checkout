<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>

    <!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

	<!-- imports for datetime -->
	<link rel="stylesheet" type="text/css" href="vendor/DateTimePicker/dist/DateTimePicker.css" />
	<script type="text/javascript" src="vendor/DateTimePicker/dist/DateTimePicker.js"></script>
		
	 <!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="DateTimePicker-ltie9.css" />
		<script type="text/javascript" src="DateTimePicker-ltie9.js"></script>
	 <![endif]-->
	 
	 <!-- For i18n Support -->
	 <script type="text/javascript" src="vendor/DateTimePicker/dist/i18n/DateTimePicker-i18n.js"></script>
</head>
<body>


<!--  Date Input  --> 
<p>Date : </p>
<input type="text" data-field="date" readonly>

<!--  Time Input  -->
<p>Time : </p>
<input type="text" data-field="time" readonly>

<!--  DateTime Input  -->
<p>DateTime : </p>
<input type="text" data-field="datetime" readonly>



<div id="dtBox"></div>

<script>
	$(document).ready(function()
	{

	 $("#dtBox").DateTimePicker({
	 	isPopup: false,
	 	animationDuration: 200
	 });

	});
</script>




</body>
</html>
