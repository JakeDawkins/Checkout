<?php
require_once('db.php');

class Gear implements JsonSerializable {
	private $gear_id;
	private $name;
	private $type;
	private $qty;
	private $isDisabled;
	private $notes;

	//------------------------ GETTERS ------------------------
	public function getID() {
		return $this->gear_id;
	}

	public function getName() {
		return $this->name;
	}

	public function getType() {
		return $this->type;
	}

	public function getQty() {
		return $this->qty;
	}

	public function isDisabled() {
		return $this->isDisabled;
	}

	public function getNotes() {
		return $this->notes;
	}

	//for web services
	// -- jsonSerializable Interface
    public function jsonSerialize()
    {
        return [
            'gear' => [
                'gear_id' => $this->gear_id,
                'name' => $this->name,
                'type' => $this->type,
                'qty' => $this->qty,
                'isDisabled' => $this->isDisabled,
                'notes' => $this->notes
            ]
        ];
    }

	//------------------------ SETTERS ------------------------
	public function setName($name) {
		$this->name = $name;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setQty($qty) {
		$this->qty = $qty;
	}

	public function setIsDisabled($isDisabled) {
		$this->isDisabled = $isDisabled;
	}

	public function setNotes($notes) {
		$this->notes = $notes;
	}

	//------------------------ DB ------------------------
	//fetch object vars from DB, given an ID
	public function fetch($gear_id) {
		//DB lookup
		$database = new DB();
		$sql = "SELECT * FROM gear WHERE gear_id='$gear_id'";
		$results = $database->select($sql);

		//set instance vars
		$this->gear_id = $gear_id;
		$this->name = $results[0]['name'];
		$this->type = $results[0]['gear_type_id'];
		$this->qty = $results[0]['qty'];
		$this->isDisabled = $results[0]['isDisabled'] == 1;
		$this->notes = $results[0]['notes'];
	}

	//either add new/update old gear object on DB.
	public function finalize() {
		$database = new DB();

		//no gear_id means this is a new gear item.
		if(!isset($this->gear_id)){
			$sql = "INSERT INTO gear(name, gear_type_id, qty, isDisabled, notes) VALUES('$this->name','$this->type','$this->qty','$this->isDisabled','$this->notes')";
			$database->query($sql);

			$sql = "SELECT gear_id FROM gear WHERE name='$this->name' AND gear_type_id='$this->type' AND qty='$this->qty' AND notes='$this->notes'";
			$results = $database->select($sql);
			$this->gear_id = $results[0]['gear_id'];
		} else { //update old item
			if($this->isDisabled){
				$sql = "UPDATE gear SET name='$this->name', gear_type_id='$this->type', qty='$this->qty', isDisabled=1, notes='$this->notes' WHERE gear_id='$this->gear_id'";
			} else {
				$sql = "UPDATE gear SET name='$this->name', gear_type_id='$this->type', qty='$this->qty', isDisabled=0, notes='$this->notes' WHERE gear_id='$this->gear_id'";
			}
			
			$database->query($sql);
		}
	}

	//remove gear item with this ID from DB
	public function delete() {
		$database = new DB();
		$sql = "DELETE FROM gear WHERE gear_id='$this->gear_id'";
		$database->query($sql);
	}

	//returns how much of a gear is available in the range
	//$co_start --> $co_end
	public function availableQty($co_start, $co_end) {
		$database = new DB();
		$qty = $this->qty;

		//list rentals in the time range
		$sql = "SELECT * FROM checkouts INNER JOIN co_gear ON checkouts.co_id = co_gear.co_id WHERE co_start < '$co_end' AND co_end > '$co_start'";
		$results = $database->select($sql);

		//no restuls... item available
		if(count($results) == 0){
			return $qty;
		}

		//results contain all checkouts in the given time range.
		//check each checkout to see if it has the desired gear item in it
		foreach ($results as $row){
			if ($row['gear_id'] == $this->gear_id){
				$qty -= $row['qty'];
			}
		}
		return $qty;
	}

	//returns the available quantity of a gear item EXCLIDING checkout with $co_id
	public function availableQtyExcludingCheckout($co_id, $co_start, $co_end){
		$database = new DB();

		//available qty not excluding the checkout
		$qty = $this->availableQty($co_start, $co_end);

		//not checked out by anything in this time
		if($qty == $this->qty) return $qty; 

		//find qty checked out by co_id and add to qty
		//list checkouts in the time range
		$sql = "SELECT * FROM checkouts INNER JOIN co_gear ON checkouts.co_id = co_gear.co_id WHERE co_start < '$co_end' AND co_end > '$co_start'";
		$results = $database->select($sql);

		//results contain all checkouts in the given time range.
		//check each checkout to see if it has the desired gear item in it
		foreach ($results as $row){
			if ($row['co_id'] == $co_id && $row['gear_id'] == $this->gear_id){
				$qty += $row['qty'];
			}
		}
		return $qty;
	}

	//returns the ID of the last person BEFORE TODAY to check out the item
	public function lastCheckoutID(){
		$database = new DB();
		$sql = "SELECT * FROM co_gear INNER JOIN checkouts ON co_gear.co_id = checkouts.co_id WHERE gear_id = '$this->gear_id' AND co_start < NOW() ORDER BY co_end DESC LIMIT 1";
		$results = $database->select($sql);
		return $results[0]['co_id'];
	}

	//returns a string describing the status of the item (i.e. in stock, disabled, etc.)
	public function status(){
		if($this->isDisabled){
			return "Disabled";
		}
		$now = date('Y-m-d h:m:s');
		if($this->qty > 1){
			return $this->availableQty($now, $now) . "/" . $this->qty . " Available";	
		} else { //max 1 qty. Just display in stock or not.
			if($this->availableQty($now, $now) == 1) return "In Stock";
			else return "Out";
		}	
	}

	//fetch a list of all checkouts containing this item
	//reverse chronological order
	public function fetchCheckoutsWithGear() {
		$database = new DB();
		$sql = "SELECT * FROM co_gear INNER JOIN checkouts ON co_gear.co_id = checkouts.co_id WHERE gear_id = '$this->gear_id' AND co_start < NOW() ORDER BY co_end DESC";
		$results = $database->select($sql);
		return $results;
	}

	//------------------------ CLASS METHODS ------------------------
	//returns an array of all Gear objects
	public static function getGearList() {
		return self::getGearListWithType(NULL);
	}

	//returns an array of all gear with type (non object)
	public static function getGearListWithType($type) {
		$database = new DB();

		if (is_null($type)){
			$sql = 	"SELECT * FROM gear";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name";
		}

		return $database->select($sql);
	}

	//returns an array of all gear available in the range
	//$co_start --> $co_end
	public static function getAvailableGear($co_start, $co_end) {
		return self::getAvailableGearWithTypeAndExclusions(NULL, NULL, $co_start, $co_end);
	}

	//returns an array of all gear available with a type 
	//in the range $co_start --> $co_end
	public static function getAvailableGearWithType($type, $co_start, $co_end) {
		return self::getAvailableGearWithTypeAndExclusions($type, NULL, $co_start, $co_end);
	}

	//get available gear with type EXCLUDING checkouts from a specific checkout
	//used for EDITING checkouts.
	public static function getAvailableGearWithTypeAndExclusions($type, $co_id, $co_start, $co_end) {
		$results = self::getGearListWithType($type);

		//handle differently if there are exclusions.
		if(is_null($co_id)){ //no exclusions
			foreach($results as $row) {
				//check if in stock for dates
				$gearObject = new Gear();
				$gearObject->fetch($row['gear_id']);

	    		if($gearObject->availableQty($co_start, $co_end) > 0 && !$gearObject->isDisabled()){
	        		//add object to return array if in stock
	        		$available_gear[] = $row;
	    		}
			}
		} else { //with exclusions
			foreach($results as $row) {
				//check if in stock for dates
				$gearObject = new Gear();
				$gearObject->fetch($row['gear_id']);

	    		if($gearObject->availableQtyExcludingCheckout($co_id, $co_start, $co_end) > 0 && !$gearObject->isDisabled()){
	        		//add object to return array if in stock
	        		$available_gear[] = $row;
	    		}
			}
		}
		return $available_gear;
	}
}//end class

//----------------------------------------------------------------------
//------------------------ Gear Types Functions ------------------------
//----------------------------------------------------------------------

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

	//remove a gear type from the DB.
	//this will also remove any gear associated
	function deleteGearType($gear_type_id) {
		$database = new DB();
		$sql = "DELETE FROM gear_types WHERE gear_type_id='$gear_type_id'";
		$database->query($sql);
	}

	//renames gear type with gear_type_id to newName
	function renameGearType($gear_type_id, $newName){
		$database = new DB();
		$sql = "UPDATE gear_types SET type='$newName' WHERE gear_type_id='$gear_type_id'";
		$database->query($sql);
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

?>