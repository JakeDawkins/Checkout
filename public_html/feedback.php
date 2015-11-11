<?php
	//USER CAKE
	require_once("models/config.php");
	if (!securePage($_SERVER['PHP_SELF'])){die();}

	require_once('models/funcs.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $summary = test_input($_POST['summary']);
        $description = test_input($_POST['description']);
        $type = test_input($_POST['type']);
        $user = test_input($_POST['user']);

        //------------------------ process form ------------------------
        if(empty($summary)){
            $errors[] = "You must enter a witty one-liner (it makes my life easy)";
        }
        if(empty($description)){
            $errors[] = "You must enter a description";   
        }
        if(empty($type)){
            $errors[] = "Feedback type error";   
        }
        if(empty($user)){
            $errors[] = "User not identified";
        }   
        if(empty($errors)){
            //form filled out correctly
            //send email to trello board
            $mail = new userCakeMail();

            //Setup our custom hooks
            $hooks = array(
                "searchStrs" => array("#USER#","#DESCRIPTION#"),
                "subjectStrs" => array($user,$description)
                );
            
            if(!$mail->newTemplateMsg("bug-report.txt",$hooks)) {
                $errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
            } else {    
                if(!$mail->sendMail("jakedawkins+wo3pmdmvolaqrivz3zbg@boards.trello.com", $summary . " #red")) {
                    $errors[] = lang("MAIL_ERROR");
                } else {
                    $successes[] = "Thank you for your feedback!";
                }
            }//end else
        }//end if no errors
    } //end submitted
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Welcome!</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Feedback", NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <?php echo resultBlock($errors,$successes); ?>
                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="user" value="<?php echo getPersonName($loggedInUser->user_id); ?>" />

                    <div class="form-group">
                        <label class="control-label" for="type">Type of Feedback:</label>
                        <select class="form-control" name="type">
                            <option value="bug" selected="selected">Bug</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="summary">Give your feedback a witty one-liner:</label>
                        <input class="form-control" name="summary" type="text" />
                    </div>
                    
                    <div class="alert alert-info">
                        For bug reports, try to explain exactly what is happening. If you can reproduce the problem, 
                        explain in detail how.
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="description">Now spill your heart to me:</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                    <br />

                    <input class="btn btn-success btn-block" type="submit" name="submit" value="Submit" />
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
