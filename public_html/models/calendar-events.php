<?php
require_once('Checkout.php'); //for fetching JSON
require_once('funcs.php'); //for testing input

// Short-circuit if the client did not give us a date range.
if (!isset($_GET['start']) || !isset($_GET['end'])) {
	die("Please provide a date range.");
}

// Parse the start/end parameters.
// These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
// Since no timezone will be present, they will parsed as UTC.
$range_start = test_input($_GET['start']);
$range_end = test_input($_GET['end']);

echo Checkout::fetchCalendarEvents($range_start, $range_end);
?>