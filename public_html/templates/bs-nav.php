    <!-- NAVIGATION -->
    <!-- navbar-inverse --> 
    <nav class="navbar navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="checkouts.php"><span class="glyphicon glyphicon-check"></span></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
	                    //Logged in: show all links
	                    //logged out: show login link only
	                   	if(isset($loggedInUser->user_id)){
       	                    echo '<li><a href="checkouts.php">Checkouts</a></li>';
                            echo '<li><a href="inventory.php">Inventory</a></li>';
                            echo '<li><a href="packages.php">Packages</a></li>';
	                   		echo '<li class="dropdown">'; ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">User Options</li>
                                    <li><a href="account.php">Account Home</a></li>
                                    <li><a href="user_settings.php">User Settings</a></li>
                                    <?php
                                    //Links for permission level 2 (default admin)
                                    if ($loggedInUser->checkPermission(array(2))){ ?>
                                    <li role="separator" class="divider"></li>
                                        <li class="dropdown-header">Admin Options</li>
                                        <li><a href='admin_configuration.php'>Site Configuration</a></li>
                                        <li><a href='admin_users.php'>Users</a></li>
                                        <li><a href='admin_permissions.php'>Permissions</a></li>
                                        <li><a href='admin_pages.php'>Pages</a></li>
                                    <?php } ?>
                                  </ul>
                            <?php
                            echo '</li>';
	                   		echo '<li><a href="logout.php">Log Out</a></li>';
						} 
                        else { 
                            echo '<li><a href="register.php">Register</a></li>';
                            echo '<li><a href="login.php">Log In</a></li>'; 
                        }
                    ?>
                </ul>
            </div>
            <!-- end navbar-collapse -->
        </div>
        <!-- end container -->
    </nav>