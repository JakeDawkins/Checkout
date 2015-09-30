<?php
require_once('db.php');

class Gear {
	private $gear_id;
	private $name;
	private $gear_type_id;
	private $qty;

	//------------------------ static methods ------------------------
	public static function getGearList(){
		return getGearListWithType(NULL);
	}

	public static function getGearListWithType($type){
		$database = new DB();

		if (is_null($type)){ $sql = "SELECT * FROM gear";
		} else { $sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name"; }

		return $database->select($sql);
	}

	public static function getAvailableGear($co_start, $co_end){
		return getAvailableGearWithType(NULL, $co_start, $co_end);
	}

	public static function getAvailableGearWithType($type, $co_start, $co_end){
		
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


}//end class
?>