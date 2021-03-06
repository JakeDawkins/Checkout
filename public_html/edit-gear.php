<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

    require_once('models/Gear.php');
    require_once('models/funcs.php');

    $types = getGearTypes();

    //define variables and set to empty values
    $name = $category = "";

    //process each variable
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $gear_id = test_input($_GET['gear_id']);
        $gearObject = new Gear();
        $gearObject->fetch($gear_id);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $gear_id = test_input($_POST['gear_id']);
        $gearObject = new Gear();
        $gearObject->fetch($gear_id);

        //can be empty. placeholder text in form
        $name = test_input($_POST['name']);
        
        //can be empty. placeholder text in form
        $qty = test_input($_POST['qty']);

        //type cannot be empty. one will be filled at least.
        $type = test_input($_POST['type']);
        $newType = test_input($_POST['newType']);

        $newIsDisabled = test_input($_POST['disabled']);
        $newNotes = test_input($_POST['notes']);

        //------------------------ name changes ------------------------
        if(!empty($name)){//user changed name
            $gearObject->setName($name);
            $successes[] = "Renamed gear item to $name";
        }

        //------------------------ qty changes ------------------------
        if(!empty($qty)){//user changed qty
            if(is_numeric($qty)){
                $gearObject->setQty($qty);
                $successes[] = "Updated gear qty to $qty";    
            } else {
                $errors[] = "Could not set quantity to non-numeric value";
            }
        }

        //------------------------ gear type changes ------------------------
        //user provided a new category that doesn't exist already
        if (!empty($newType)){
            $type = newGearType($newType);
            $successes[] = "Created new gear type, $newType";
        }

        //different type chosen. Just change types
        if($type != $gearObject->getType()){
            $gearObject->setType($type);
            $successes[] = "Updated gear type";
        }

        //------------------------ disable state (always submits) ------------------------
        if($gearObject->isDisabled() && !$newIsDisabled){
            $gearObject->setIsDisabled($newIsDisabled);
            $successes[] = "Gear enabled for checkouts";
        } else if(!$gearObject->isDisabled() && $newIsDisabled){
            $gearObject->setIsDisabled($newIsDisabled);
            $successes[] = "Gear disabled for checkouts";
        }

        //------------------------ Notes Changed ------------------------
        if($gearObject->getNotes() != $newNotes){
            $gearObject->setNotes($newNotes);  
            $successes[] = "Gear notes updated";
        }

        $gearObject->finalize();
    }   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- INCLUDE BS HEADER INFO -->
    <?php include('templates/bs-head.php'); ?>

    <title>Edit Gear</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Edit Gear",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <?php echo "<a href='gear-item.php?gear_id=" . $gear_id . "'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Item Details</a>"; ?>
                <br /><br />
                <?php echo resultBlock($errors,$successes); ?>

                <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="gear_id" value="<?php echo $gear_id; ?>" />
                    <div class="form-group">
                        <label class="control-label" for="name">Name:</label>
                        <input class="form-control" name="name" type="text" placeholder="<?php echo $gearObject->getName(); ?>"/>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="qty">Quantity:</label>
                        <input class="form-control" name="qty" type="text" placeholder="<?php echo $gearObject->getQty(); ?>"/>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="type">Choose a category:</label>
                        <select class="form-control" name="type">
                        <?php
                            $oldType = $gearObject->getType();
                            foreach($types as $type){
                                echo "<option value='" . $type['gear_type_id'] . "' ";
                                if ($type['gear_type_id'] == $oldType) { echo "selected='selected'>"; }
                                else { echo ">"; }
                                echo $type['type'] . "</option>";
                            }
                        ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="newType">Or create a new category:</label>
                        <input class="form-control" name="newType" type="text" />
                    </div>

                    <div class="checkbox">
                        <label class="control-label">
                            <input type="checkbox" name="disabled" value="true" <?php if($gearObject->isDisabled()) echo "checked";?> />
                            &nbsp;&nbsp;Disable</label>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="notes">Notes:</label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo $gearObject->getNotes(); ?></textarea>
                    </div>
                    
                    <br />

                    <input class="btn btn-success" type="submit" name="submit" value="Submit" />
                </form>
            </div> <!-- END COL -->
        </div> <!-- END ROW --> 
    </div><!-- END CONTAINER -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>