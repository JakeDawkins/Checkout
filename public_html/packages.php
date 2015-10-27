<?php
require_once("models/config.php"); //for usercake
if (!securePage(htmlspecialchars($_SERVER['PHP_SELF']))){die();}

	require_once('models/Gear.php');
    require_once('models/Package.php');
	require_once('models/funcs.php');

    $packages = Package::getAllPackages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Packages</title>
</head>
<body>
    <!-- IMPORT NAVIGATION & HEADER-->
    <?php include('templates/bs-nav.php');
    echo printHeader("Packages","Packages are sets of gear to make similar checkouts easier"); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <a class="btn btn-primary" href="new-package.php">New Package&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a>
                    <br /><br />

                    <table class="table table-hover"> 
                        <colgroup>
                            <col class="one-third">
                            <col class="one-third">
                            <col class="one-third">                            
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th class='text-center'>Gear</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($packages as $pkg){
                                    $gearList = $pkg->getGearList();

                                    echo "<tr>";
                                    echo "<td><a href='package.php?pkg_id=" . $pkg->getID() . "'>". $pkg->getTitle() . "</a></td>";
                                    echo "<td>" . $pkg->getDescription() . "</td><td class='text-center'>";
                                    $i = 0;
                                    foreach($gearList as $gear){
                                        $gearObject = new Gear();
                                        $gearObject->fetch($gear);

                                        if($i>5){
                                            echo "<div class='hide" . $pkg->getID() . "' style='display:none'>" . $gearObject->getName() . "</div>";  
                                        } elseif($i==5){
                                            echo "<div class='hide" . $pkg->getID() . "' style='display:none'>" . $gearObject->getName() . "</div>";
                                            echo "<div class='unhide' id='" . $pkg->getID() . "'>...</div>";
                                        } else { 
                                            echo $gearObject->getName() . "<br />";  
                                        }
                                        $i++;
                                    }
                                    echo "</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <script>
    //for expanding gear lists
    $("div.unhide").click(function(event){
        //alert(event.target.id);
        $("div.hide" + event.target.id).css("display","block");
        $("#" + event.target.id).css("display","none");
    });

    </script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
