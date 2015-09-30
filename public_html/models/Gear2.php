<?php
require_once('db.php');

class Gear {
	private $gear_id;
	private $name;
	private $gear_type_id;
	private $qty;

	//------------------------ static methods ------------------------
	//doesn't return a list of gear objects
	public static function getGearList(){
		return getGearListWithType(NULL);
	}

	public static function getGearListWithType($type){
		$database = new DB();

		if (is_null($type)){ $sql = "SELECT * FROM gear";
		} else { $sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name"; }

		return $database->select($sql);
	}

	//doesn't return a list of gear objects
	public static function getAvailableGear($co_start, $co_end){
		return getAvailableGearWithType(NULL, $co_start, $co_end);
	}

	public static function getAvailableGearWithType($type, $co_start, $co_end){
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

	//------------------------ getters ------------------------

	public function getID(){
		return $this->gear_id;
	}

	public function getName(){
		return $this->name;
	}

	public function getGearTypeID(){
		return $this->gear_type_id;
	}

	public function getQty(){
		return $this->qty;
	}

	public function printObject(){
		echo "ID: " . $this->gear_id . "<br />";
		echo "NAME: " . $this->name . "<br />";
		echo "TYPE: " . $this->gear_type_id . "<br />";
		echo "QTY: " . $this->qty . "<br />";
	}

	public function retrieveGear($gear_id){
		$database = new DB();
		$sql = "SELECT * FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);
		
		$this->gear_id = $gear_id;
		$this->name = $results[0]['name'];
		$this->gear_type_id = $results[0]['gear_type_id'];
		$this->qty = $results[0]['qty'];
	}

	//------------------------ setters ------------------------

	public function setID($newID){
		$this->gear_id = $newID;
	}

	public function setName($newName){
		$this->name = $newName;
	}

	public function setGearTypeID($newTypeID){
		$this->gear_type_id = $newTypeID;
	}

	public function setQty($newQty){
		$this->qty = $newQty;
	}

	//------------------------ db ------------------------
	public function delete(){
		$database = new DB();
		$sql = "DELETE FROM gear WHERE gear_id='$this->gear_id'";
		$database->query($sql);
	}

	public function finalize(){
		if(isset($this->gear_id)){ //old gear item

		} else { //new gear item

		}
	}//end finalize

	//lists the available quantity of any item. 
	public function availableQty($gear_id, $co_start, $co_end){
		$database = new DB();

		//list rentals in time range
		$sql = "SELECT * FROM checkouts INNER JOIN co_gear ON checkouts.co_id = co_gear.co_id WHERE co_start < '$co_end' AND co_end > '$co_start'";

		$results = $database->select($sql);

		//TODO

	}



}//end class
?>