<?php

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


//------------------------ validation ------------------------

function buildDateTime($y, $m, $d, $h, $min, $s){
	return "$y-$m-$d $h:$min:$s";
}


function isDateTime($dateTime) {
	return (bool)strtotime($dateTime);
}


?>