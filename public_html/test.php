<?php
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$year = date('y');
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$day = test_input($_POST['day']);
		$month = test_input($_POST['month']);
		$year = test_input($_POST['year']);
		$hour = test_input($_POST['hour']);
		$min = test_input($_POST['min']);
		$ampm = test_input($_POST['ampm']);
		if ($ampm == "pm") $hour += 12;
		else {
			if ($hour == 12) $hour = 0;
		}
		echo $min . "<br />";
		$timeDateString = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $min . ":00"; 
		//fprintf($timeDateString, "%s-%s-%s %s:%s<br />",$year,$month,$day,$hour,$min);
		echo $timeDateString;
	}



?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<select class="push-bottom form-control" style="display: inline" name="month" id="sel1">
			<option value="01">January</option>
			<option value="02">February</option>
			<option value="03">March</option>
			<option value="04">April</option>
			<option value="05">May</option>
			<option value="06">June</option>
			<option value="07">July</option>
			<option value="08">August</option>
			<option value="09">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
        </select>
		<select class="push-bottom form-control" style="display: inline" name="day" id="sel1">
            <option value"01">01</option>
            <option value"02">02</option>
            <option value"03">03</option>
            <option value"04">04</option>
            <option value"05">05</option>
            <option value"06">06</option>
            <option value"07">07</option>
            <option value"08">08</option>
            <option value"09">09</option>
            <option value"10">10</option>
            <option value"11">11</option>
            <option value"12">12</option>
            <option value"13">13</option>
            <option value"14">14</option>
            <option value"15">15</option>
            <option value"16">16</option>
            <option value"17">17</option>
            <option value"18">18</option>
            <option value"19">19</option>
            <option value"20">20</option>
            <option value"21">21</option>
            <option value"22">22</option>
            <option value"23">23</option>
            <option value"24">24</option>
            <option value"25">25</option>
            <option value"26">26</option>
            <option value"27">27</option>
            <option value"28">28</option>
            <option value"29" id="29">29</option>
            <option value"30" id="30">30</option>
            <option value"31" id="31">31</option>
        </select>
		<select class="push-bottom form-control" style="display: inline" name="year" id="sel1">
            <option value="2015" <?php if ($year == 15) echo 'selected="selected"';?>>2015</option>
			<option value="2016" <?php if ($year == 16) echo 'selected="selected"';?>>2016</option>
        </select><br />
        <select name="hour">
            <option value"01">1</option>
            <option value"02">2</option>
            <option value"03">3</option>
            <option value"04">4</option>
            <option value"05">5</option>
            <option value"06">6</option>
			<option value"07">7</option>
            <option value"08">8</option>
            <option value"09">9</option>
            <option value"10">10</option>
            <option value"11">11</option>
            <option value"12">12</option>
        </select>
        <select name="min">
        	<option value"00">00</option>
            <option value"05">05</option>
            <option value"10">10</option>
            <option value"15">15</option>
            <option value"20">20</option>
            <option value"25">25</option>
            <option value"30">30</option>
			<option value"35">35</option>
            <option value"40">40</option>
            <option value"45">45</option>
            <option value"50">50</option>
            <option value"55">55</option>
        </select>
        <select name="ampm">
        	<option value="am">AM</option>
        	<option value="pm">PM</option>
    	</select>
        <button type="submit" name="submit">submit</button> 
	</form>
</body>
</html>