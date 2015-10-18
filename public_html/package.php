<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Form.php');
	require_once('models/Gear.php');
    require_once('models/Package.php'); 

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $pkg_id = test_input($_GET['pkg_id']);

        $del = test_input($_GET['delete']);
        if($del == true){
            Package::removePackage($pkg_id);
            header("Location: packages.php");
        }

        $pkg = new Package();
        $pkg->retrievePackage($pkg_id);



        //pull pkg and gearlist
        $gearList = $pkg->getGearList();

        //create array of gear types within this checkout
        $gearTypes = array();
        foreach($gearList as $gear){
            $type = gearTypeWithID(getGearType($gear));
            if (!in_array($type, $gearTypes)){
                $gearTypes[] = $type;
            }
        }//foreach
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Package Details</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader($pkg->getTitle(),NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
            <?php echo "<a href='packages.php'><span class='glyphicon glyphicon-chevron-left'></span>&nbsp;&nbsp;Back to Packages</a>"; ?>
            <br /><br />
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Package Details</div>
                    <div class="panel-body text-center">
                        <p>
                        <?php
                            echo "<strong>Package ID:</strong> " . $pkg_id . "<br /><br />";
                            echo "<strong>Title:</strong> " . $pkg->getTitle() . "<br /><br />";
                            echo "<strong>Description:</strong> " . $pkg->getDescription() . "<br /><br />";

                            echo "<a class='btn btn-primary' href='edit-package.php?pkg_id=" . $pkg_id . "'>Edit</a> &nbsp;&nbsp;";
                            echo "<a class='btn btn-danger' href='package.php?pkg_id=" . $pkg_id . "&delete=true'>Delete</a>"; 
                        ?>
                        </p>
                    </div>
                </div>
            </div><!-- end col -->
        </div><!-- end row -->

        <?php
            if(count($gearTypes) == 1){ //1 across
                echo "<div class='row'>";
                foreach($gearTypes as $gearType){
                    echo "<div class='col-sm-8 col-sm-offset-2'>";
                        echo "<div class='panel panel-default'>";
                            echo "<div class='panel-heading text-center'>" . $gearType . "</div>";
                            echo "<div class='panel-body text-center'>";
                                echo "<p>";
                                foreach($gearList as $gear){
                                    $name = getGearName($gear);
                                    $type = gearTypeWithID(getGearType($gear));
                                    if($type == $gearType){
                                        echo "$name<br />";
                                    }
                                }
                                echo "</p>";
                    echo "</div></div></div>"; //panel-body //panel //col
                }
                echo "</div>"; //end row
            } else { //2 across
                $i = 0;
                foreach($gearTypes as $gearType){
                    if($i % 2 == 0){ //start row
                        echo "<div class='row'>";
                        echo "<div class='col-sm-4 col-sm-offset-2'>";  
                    } 
                    else echo "<div class='col-sm-4'>";
                        echo "<div class='panel panel-default'>";
                            echo "<div class='panel-heading text-center'>" . $gearType . "</div>";
                            echo "<div class='panel-body text-center'>";
                                echo "<p>";
                                foreach($gearList as $gear){
                                    $name = getGearName($gear);
                                    $type = gearTypeWithID(getGearType($gear));
                                    if($type == $gearType){
                                        echo "$name<br />";
                                    }
                                }
                                echo "</p>";
                    echo "</div></div></div>"; //panel-body //panel //col

                    //if second col on row or last col, end the row
                    if($i % 2 == 1 || $i == count($gearTypes)-1){
                        echo "</div>"; //end row
                    }
                    $i++;
                }
            }
        ?>





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
