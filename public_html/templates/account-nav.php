	    	<div class="col-sm-3">
		    	<ul class="nav nav-pills nav-stacked">
				  	<li role="presentation" class="active"><a href="#">Home</a></li>
				  	<li role="presentation"><a href="user_settings.php">User Settings</a></li>
				  	<?php
				  	//Links for permission level 2 (default admin)
					if ($loggedInUser->checkPermission(array(2))): ?>
						<li role='presentation'><a href='admin_configuration.php'>Admin Configuration</a></li>
						<li role='presentation'><a href='admin_users.php'>Admin Users</a></li>
						<li role='presentation'><a href='admin_permissions.php'>Admin Permissions</a></li>
						<li role='presentation'><a href='admin_pages.php'>Admin Pages</a></li>
					<?php endif; ?>
				</ul>
	    	</div>