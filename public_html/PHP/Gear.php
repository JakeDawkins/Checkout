<?php
require_once('db.php');

//------------------------ new ------------------------
	//inserts a new gear item into db with specified type.
	//if no type specified, inserts null.
	function newGearItem($name,$type) {

	}

	//inserts a new gear category onto the DB.
	function newGearType($type) {

	}

//------------------------ remove ------------------------

	//removes gear item from db
	function deleteGearItem($gear_id) {
		
	}

	//remove a gear type from the DB.
	//this will also remove any gear associated
	function deleteGearType($type) {

	}



//------------------------ search functions ------------------------
	//returns array of gear (no abailabilities)
	function getGearList() {
		return getGearListWithType(NULL);
	}

	//uses type name, not ID. Searches for ID then filters by found ID.
	function getGearListWithType($type) {
		$database = new DB();

		if (is_null($type)){
			$sql = 	"SELECT * FROM gear";	
		} else {
			//get id of type
			$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
			$results = $database->select($sql);
			$type_id = $results[0]['gear_type_id'];//$row["gear_type_id"];

			$sql = "SELECT * FROM gear WHERE gear_type_id='$type_id'";
		}
		return $database->select($sql);
	}

	//returns array of gear info with availabilities
	function getAllGearInfo($type, $co_start, $co_end) {

	}

	//returns info for a single gear item including availability
	function getSingleGearItem($gear_id, $co_start, $co_end) {

	}

	//returns only the availability of a single gear item
	function isAvailable($gear_id, $co_start, $co_end){

	}

	//returns an array of gear types in the DB.
	function getGearTypes() {

	}

?>