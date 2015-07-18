<?php
require_once('db.php');

//------------------------ adders ------------------------

	//inserts a new gear item into db with specified type.
	//if no type specified, inserts null.
	function newGearItem($name,$type) {
		$database = new DB();

		if (is_numeric($type)) {
			$sql = "INSERT INTO gear(name,gear_type_id) VALUES('$name','$type')";
			// printf("__%s__",$sql);
		} else {
			$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
			// printf("__%s__",$sql);
			$results = $database->select($sql);
			$gear_type_id = $results[0]['gear_type_id'];
			$sql = "INSERT INTO gear(name,gear_type_id) VALUES('$name','$gear_type_id')";
			// printf("__%s__",$sql);
		}
		$database->query($sql);
	}

	//inserts a new gear category onto the DB. Returns new ID
	function newGearType($type) {
		$database = new DB();
		$sql = "INSERT INTO gear_types(type) VALUES('$type')";
		$database->query($sql);

		//returns the newly inserted type's ID.
		$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
		$results = $database->select($sql);

		return $results[0]['gear_type_id'];
	}

//------------------------ removers ------------------------

	//removes gear item from db
	function deleteGearItem($gear_id) {
		$database = new DB();
		$sql = "DELETE FROM gear WHERE gear_id='$gear_id'";
		$database->query($sql);
	}

	//remove a gear type from the DB.
	//this will also remove any gear associated
	function deleteGearType($gear_type_id) {
		$database = new DB();
		$sql = "DELETE FROM gear_types WHERE gear_type_id='$gear_type_id'";
		$database->query($sql);
	}

//------------------------ search functions ------------------------
	function getGearName($gear_id){
		$database = new DB();
		$sql = "SELECT name FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);
		return $results[0]['name'];
	}

	//returns array of gear (no abailabilities)
	function getGearList() {
		return getGearListWithType(NULL);
	}

	//uses type name or ID.
	// if needed, Searches for ID then filters by found ID.
	function getGearListWithType($type) {
		$database = new DB();

		if (is_null($type)){
			$sql = 	"SELECT * FROM gear";
		} elseif (!is_numeric($type)) { //type name passed in
			//get id of type
			$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
			$results = $database->select($sql);
			$type_id = $results[0]['gear_type_id'];//$row["gear_type_id"];

			$sql = "SELECT * FROM gear WHERE gear_type_id='$type_id'";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type'";
		}

		return $database->select($sql);
	}

	function getAvailableGear($co_start, $co_end){
		return getAvailableGearWithType(NULL, $co_start, $co_end);
	}

	function getAvailableGearWithType($type, $co_start, $co_end){
		//$return_gear = array();
		$database = new DB();

		if (is_null($type)){
			$sql = 	"SELECT * FROM gear";
		} elseif (!is_numeric($type)) { //type name passed in
			//get id of type
			$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
			$results = $database->select($sql);
			$type_id = $results[0]['gear_type_id'];//$row["gear_type_id"];

			$sql = "SELECT * FROM gear WHERE gear_type_id='$type_id'";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type'";
		}

		$results = $database->select($sql);

		$available_gear = array();

		foreach($results as $row) {
			//printf("__GEAR_ID___:%d",$row['gear_id']);
			//echo '<br>';
    		if(isAvailable($row['gear_id'],$co_start, $co_end)){
        		//add object to return array
        		$available_gear[] = $row;
    		}
		}

		return $available_gear;
	}

	//returns only the availability of a single gear item
	function isAvailable($gear_id, $co_start, $co_end){
		$database = new DB();

		//list rentals in the time range
		$sql = "SELECT * FROM checkouts INNER JOIN co_gear ON checkouts.co_id = co_gear.co_id WHERE co_start < '$co_end' AND co_end > '$co_start'";

		$results = $database->select($sql);
		//no restuls... item available
		if(count($results) == 0){
			return true;
		}

		//results contain all checkouts in the given time range.
		//check each checkout to see if it has the desired gear item in it
		foreach ($results as $row){
			//$id = $row['co_id'];
			//printf("__ID: %s__",$id);
			if ($row['gear_id'] == $gear_id) return false;
		}

		//no checkouts have that gear item listed.
		return true;
	}

	//returns an array of gear types in the DB.
	function getGearTypes() {
		$database = new DB();
		$sql = "SELECT * FROM gear_types";
		return $database->select($sql);
	}

?>
