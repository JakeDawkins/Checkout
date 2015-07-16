<?php
//class Person {
	function getPersonName($person_id){
		$sql = "SELECT name FROM people WHERE person_id='$person_id'";
		$results = $database->select($sql);
		return $results[0]['name'];
	}

//}

?>