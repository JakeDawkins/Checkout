<?php
    include('models/Gear2.php');
    $gear = new Gear();
    $gear->retrieveGear(2);
    $gear->printObject();
?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
</head>
<body>

</body>
</html>