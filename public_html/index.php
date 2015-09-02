<?php
	//require_once('model/db.php');
	require_once('models/Gear.php');
	require_once('models/Checkout.php');
	require_once('models/Person.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="checkout system">
    <meta name="author" content="Jake Dawkins">

    <title>Welcome!</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/mainstyle.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- NAVIGATION -->
    <!-- navbar-inverse --> 
    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Checkout</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="inventory.php">Inventory</a></li>
                    <li><a href="checkouts.php">Checkouts</a></li>
                    <li><a href="account.php">Account</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- HEADER -->
    <div class="container-fluid gray">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Checkout</h1>
                <p class="lead">A system for scheduling gear among a team</p>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <br /><br />

    <!-- CHECKOUTS TABLE CONCEPT -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                
            </div>
        </div>

        <br /><br />

    </div> <!-- /container -->

    <footer class="footer">
      <div class="container text-center">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- <?php include('templates/nav.php'); ?> -->
</body>
</html>
