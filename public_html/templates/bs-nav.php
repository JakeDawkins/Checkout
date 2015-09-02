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
                    <?php
	                    //Logged in: show all links
	                    //logged out: show login link only
	                   	if(isset($loggedInUser)){
       	                    echo '<li><a href="inventory.php">Inventory</a></li>';
                    		echo '<li><a href="checkouts.php">Checkouts</a></li>';
	                   		echo '<li><a href="account.php">Account</a></li>';
	                   		echo '<li><a href="logout.php">Log Out</a></li>';
						} else { echo '<li><a href="login.php">Log In</a></li>'; }
                    ?>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>