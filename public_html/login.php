<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST)) {
	$errors = array();
	$username = sanitize(trim($_POST["username"]));
	$password = trim($_POST["password"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	if($username == "") {
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	} if($password == "") {
		$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
	}

	if(count($errors) == 0) {
		//A security note here, never tell the user which credential was incorrect
		if(!usernameExists($username)) {
			$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
		} else {
			$userdetails = fetchUserDetails($username);
			//See if the user's account is activated
			if($userdetails["active"]==0) {
				$errors[] = lang("ACCOUNT_INACTIVE");
			} else {
				//Hash the password and use the salt from the database to compare the password.
				$entered_pass = generateHash($password,$userdetails["password"]);
				
				if($entered_pass != $userdetails["password"]) {
					//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
					$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
				} else {
					//Passwords match! we're good to go'
					
					//Construct a new logged in user object
					//Transfer some db data to the session object
					$loggedInUser = new loggedInUser();
					$loggedInUser->email = $userdetails["email"];
					$loggedInUser->user_id = $userdetails["id"];
					$loggedInUser->hash_pw = $userdetails["password"];
					$loggedInUser->title = $userdetails["title"];
					$loggedInUser->displayname = $userdetails["display_name"];
					$loggedInUser->username = $userdetails["user_name"];
					
					//Update last sign in
					$loggedInUser->updateLastSignIn();
					$_SESSION["userCakeUser"] = $loggedInUser;
					
					//Redirect to user account page
					header("Location: account.php");
					die();
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Login</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php//include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Login</h1>
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
				<?php echo resultBlock($errors,$successes);

				echo "<form role='form' name='login' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method='post'>"; ?>
					<div class="form-group">
						<label class="control-label" for="username">Username:</label>
						<input class="form-control" type='text' name='username' />
					</div>
					<div class="form-group">
						<label class="control-label" for="password">Password:</label>
						<input class="form-control" type='password' name='password' />
					</div>
					<input class="btn btn-success btn-block" type='submit' value='Login' class='submit' />
					<br />
					<a class="btn btn-primary btn-block" href="register.php">Register</a>
					<br />
					<div class="full-width text-center">
						<a href="forgot-password.php">I forgot my password</a>
					</div>
				</form>
           </div>
        </div>
    </div>

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php //include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>