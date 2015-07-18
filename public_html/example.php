<?php
require_once("models/config.php");
if(!securePage($_SERVER['PHP_SELF'])){die();}

//includes the HTML header
require_once("models/header.php");
?>

<body>
    <div id="wrapper">
        <div id='top'><div id='logo'></div></div>
            <div id='content'>
                <h1>UserCake</h1>
                <h2>2.00</h2>
                <div id='left-nav'>
                    <?php include("left-nav.php"); ?>
                </div>
                <div id="main">
                    <p>CONTENT OF FIRST PAGE</p>
                </div>
                <div id="bottom"></div>
            </div>
    </div> <!-- close wrapper -->
</body>
</html>
