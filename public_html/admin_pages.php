<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

$pages = getPageFiles(); //Retrieve list of pages in root usercake folder
$dbpages = fetchAllPages(); //Retrieve list of pages in pages table
$creations = array();
$deletions = array();

//Check if any pages exist which are not in DB
foreach ($pages as $page){
	if(!isset($dbpages[$page])){
		$creations[] = $page;	
	}
}

//Enter new pages in DB if found
if (count($creations) > 0) {
	createPages($creations);
}

if (count($dbpages) > 0){
	//Check if DB contains pages that don't exist
	foreach ($dbpages as $page){
		if(!isset($pages[$page['page']])){
			$deletions[] = $page['id'];	
		}
	}
}

//Delete pages from DB if not found
if (count($deletions) > 0) {
	deletePages($deletions);
}

//Update DB pages
$dbpages = fetchAllPages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- INCLUDE BS HEADER INFO -->
	<?php include('templates/bs-head.php'); ?>

    <title>Pages</title>
</head>
<body>
	<!-- IMPORT NAVIGATION & HEADER-->
	<?php include('templates/bs-nav.php');
    echo printHeader("Pages",NULL); ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
				<table class='table table-hover'>
					<tr>
						<th>Id</th>
						<th>Page</th>
						<th>Access</th>
					</tr>

					<?php
					//Display list of pages
					foreach ($dbpages as $page){
					?>
						<tr>
							<td>
								<?php echo $page['id']; ?>
							</td>
							<td>
								<?php echo "<a href ='admin_page.php?id=".$page['id']."'>".$page['page']."</a>"; ?>
							</td>
							<td>
								
								<?php
								//Show public/private setting of page
								if($page['private'] == 0){ echo "Public"; }
								else { echo "Private"; }
								?>

							</td>
						</tr>
					<?php } ?>
				</table>
            </div>
        </div>
    </div> <!-- end container -->

    <br /><br />

    <!-- INCLUDE BS STICKY FOOTER -->
    <?php include('templates/bs-footer.php'); ?>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>