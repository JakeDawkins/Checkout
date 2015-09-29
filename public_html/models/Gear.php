<?php
require_once('db.php');

//------------------------ adders ------------------------

	//inserts a new gear item into db
	//	type: gear_type_id in gear_types table
	//	qty: max qty in stock
	function newGearItem($name,$type,$qty) {
		$database = new DB();
		$sql = "INSERT INTO gear(name,gear_type_id,qty) VALUES('$name','$type','$qty')";
		$database->query($sql);
	}

	//inserts a new gear category onto the DB. 
	//	RETURNS: new ID
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

//------------------------ modifiers ------------------------

	//renames gear type with gear_id to newName
	function renameGearType($gear_type_id, $newName){
		$database = new DB();
		$sql = "UPDATE gear_types SET type='$newName' WHERE gear_type_id='$gear_type_id'";
		$database->query($sql);
	}

	//updates gear item quantities
	function updateGearQty($gear_id, $qty){
		$database = new DB();
		$sql = "UPDATE gear SET qty='$qty' WHERE gear_id='$gear_id'";
		$database->query($sql);
	}

//------------------------ search functions ------------------------
	function getGearName($gear_id){
		$database = new DB();
		$sql = "SELECT name FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);
		return $results[0]['name'];
	}

	//returns the ID of the gear type, not the type name
	function getGearType($gear_id){
		$database = new DB();
		$sql = "SELECT gear_type_id FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);
		return $results[0]['gear_type_id'];
	}

	//get the total quantity of a gear item. Not affected by checkouts
	function getTotalGearQty($gear_id){
		$database = new DB();
		$sql = "SELECT qty FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);
		return $results[0]['qty'];
	}

	//returns array of gear (no availabilities)
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

			$sql = "SELECT * FROM gear WHERE gear_type_id='$type_id' ORDER BY name";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name";
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

			$sql = "SELECT * FROM gear WHERE gear_type_id='$type_id' ORDER BY name";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name";
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

	//lists the available quantity of any item. 
	function availableQty($gear_id, $co_start, $co_end){
		$database = new DB();

		//list rentals in time range
		$sql = "SELECT * FROM checkouts INNER JOIN co_gear ON checkouts.co_id = co_gear.co_id WHERE co_start < '$co_end' AND co_end > '$co_start'";

		$results = $database->select($sql);

		//TODO

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
		$sql = "SELECT * FROM gear_types ORDER BY type";
		return $database->select($sql);
	}

	//returns name string of gear type
	function gearTypeWithID($type_id) {
		$database = new DB();
		$sql = "SELECT * FROM gear_types WHERE gear_type_id='$type_id'";
		$results = $database->select($sql);
		return $results[0]['type'];
	}

	//fetches the checkout ID of the most recent Checkout associated
	//with a gear item.
	function fetchLastCheckout($gear_id) {
		$database = new DB();
		$sql = "SELECT * FROM co_gear INNER JOIN checkouts ON co_gear.co_id = checkouts.co_id WHERE gear_id = '$gear_id' AND co_start < NOW() ORDER BY co_end DESC LIMIT 1";
		$results = $database->select($sql);
		return $results[0]['co_id'];
	}

?>
