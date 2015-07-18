<?php
function getPersonName($person_id){
	$sql = "SELECT display_name FROM uc_users WHERE id='$person_id'";
	$results = $database->select($sql);
	return $results[0]['display_name'];
}
?>