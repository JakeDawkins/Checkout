<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//User has confirmed they want their password changed 
if(!empty($_GET["confirm"])) {
	$token = trim($_GET["confirm"]);
	
	if($token == "" || !validateActivationToken($token,TRUE)) {
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	} else {
		$rand_pass = getUniqueCode(15); //Get unique code
		$secure_pass = generateHash($rand_pass); //Generate random hash
		$userdetails = fetchUserDetails(NULL,$token); //Fetchs user details
		$mail = new userCakeMail();		
		
		//Setup our custom hooks
		$hooks = array(
			"searchStrs" => array("#GENERATED-PASS#","#USERNAME#"),
			"subjectStrs" => array($rand_pass,$userdetails["display_name"])
			);
		
		if(!$mail->newTemplateMsg("your-lost-password.txt",$hooks)) {
			$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
		} else {	
			if(!$mail->sendMail($userdetails["email"],"Your new password")) {
				$errors[] = lang("MAIL_ERROR");
			} else {
				if(!updatePasswordFromToken($secure_pass,$token)) {
					$errors[] = lang("SQL_ERROR");
				} else {	
					if(!flagLostPasswordRequest($userdetails["user_name"],0)) {
						$errors[] = lang("SQL_ERROR");
					} else {
						$successes[]  = lang("FORGOTPASS_NEW_PASS_EMAIL");
					}
				}
			}
		}
	}
}

//User has denied this request
if(!empty($_GET["deny"])) {
	$token = trim($_GET["deny"]);
	
	if($token == "" || !validateActivationToken($token,TRUE)) {
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	} else {
		$userdetails = fetchUserDetails(NULL,$token);
		
		if(!flagLostPasswordRequest($userdetails["user_name"],0)) {
			$errors[] = lang("SQL_ERROR");
		} else {
			$successes[] = lang("FORGOTPASS_REQUEST_CANNED");
		}
	}
}

//Forms posted
if(!empty($_POST)) {
	$email = $_POST["email"];
	$username = sanitize($_POST["username"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	
	if(trim($email) == "") {
		$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
	}
	//Check to ensure email is in the correct format / in the db
	else if(!isValidEmail($email) || !emailExists($email)) {
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	
	if(trim($username) == "") {
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	} else if(!usernameExists($username)) {
		$errors[] = lang("ACCOUNT_INVALID_USERNAME");
	}
	
	if(count($errors) == 0) {
		
		//Check that the username / email are associated to the same account
		if(!emailUsernameLinked($email,$username)) {
			$errors[] =  lang("ACCOUNT_USER_OR_EMAIL_INVALID");
		} else {
			//Check if the user has any outstanding lost password requests
			$userdetails = fetchUserDetails($username);
			if($userdetails["lost_password_request"] == 1) {
				$errors[] = lang("FORGOTPASS_REQUEST_EXISTS");
			} else {
				//Email the user asking to confirm this change password request
				//We can use the template builder here
				
				//We use the activation token again for the url key it gets regenerated everytime it's used.
				
				$mail = new userCakeMail();
				$confirm_url = lang("CONFIRM")."\n".$websiteUrl."forgot-password.php?confirm=".$userdetails["activation_token"];
				$deny_url = lang("DENY")."\n".$websiteUrl."forgot-password.php?deny=".$userdetails["activation_token"];
				
				//Setup our custom hooks
				$hooks = array(
					"searchStrs" => array("#CONFIRM-URL#","#DENY-URL#","#USERNAME#"),
					"subjectStrs" => array($confirm_url,$deny_url,$userdetails["user_name"])
					);
				
				if(!$mail->newTemplateMsg("lost-password-request.txt",$hooks)) {
					$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
				} else {
					if(!$mail->sendMail($userdetails["email"],"Lost password request")) {
						$errors[] = lang("MAIL_ERROR");
					} else {
						//Update the DB to show this account has an outstanding request
						if(!flagLostPasswordRequest($userdetails["user_name"],1)) {
							$errors[] = lang("SQL_ERROR");
						} else {	
							$successes[] = lang("FORGOTPASS_REQUEST_SUCCESS");
						}
					}
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

    <title>Forgot Password</title>
</head>
<body>
	<!-- IMPORT NAVIGATION -->
	<?php //include('templates/bs-nav.php'); ?>

    <!-- HEADER -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Forgot Password</h1>
                <!-- <p class="lead">Enter your email and username and we'll send a password reset email.</p> -->
            </div>
        </div><!-- end row -->
    </div><!-- end container -->

    <br /><br />

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
    			<?php echo resultBlock($errors,$successes);
				echo "<form name='newLostPass' action='".$_SERVER['PHP_SELF']."' method='post'>"; ?>
					<div class='form-group'>
						<label class='control-label'>Username:</label>
						<input class='form-control' type='text' name='username' />
					</div>
					<div class='form-group'>    
						<label class='control-label'>Email:</label>
						<input class='form-control' type='text' name='email' />
					</div>
					<input class='btn btn-success btn-block' type='submit' value='Send Reset Email' class='submit' />
				</form>
            </div>
        </div>
    </div>

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>