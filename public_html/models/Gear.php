<?php
require_once('db.php');

/*
Class: Gear
	Handles interaction with individual gear items.
*/
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

		//parse results and set instance vars
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

			//retrieve gear_id of new item from DB, and set instance var
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

	/*
		Goal: returns how much of a gear is available in the range $co_start --> $co_end
		Return : int
	*/
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

	/*
		Goal: Return the available quantity of a gear item in the time range 
			co_start --> co_end, excluding a checkout with co_id. i.e. pretend
			the checkout with co_id doesn't exist
		Returns: int
	*/
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

	/*
		Goal: return the user_ID of the last person BEFORE NOW to check out 
			this item. 
		Notes: Not exactly accurate. Uses server timezone with local checkout times
		Returns: int (user_id)
	*/
	public function lastCheckoutID(){
		$database = new DB();
		$sql = "SELECT * FROM co_gear INNER JOIN checkouts ON co_gear.co_id = checkouts.co_id WHERE gear_id = '$this->gear_id' AND co_start < NOW() ORDER BY co_end DESC LIMIT 1";
		$results = $database->select($sql);
		return $results[0]['co_id'];
	}

	/*
		Goal: returns a string describing the current status of the item (ex: "In Stock", 
			"Disabled", "4/5 Available", etc.)
		Returns: string
	*/
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

	/*
		Goal: Fetch a list of all PAST checkouts containing this gear item in 
			reverse chronological order.
		Notes: Not exactly accurate. Uses server timezone with local checkout times
		Returns: Array of Associative Arrays 
	*/
	public function fetchCheckoutsWithGear() {
		$database = new DB();
		$sql = "SELECT * FROM co_gear INNER JOIN checkouts ON co_gear.co_id = checkouts.co_id WHERE gear_id = '$this->gear_id' AND co_start < NOW() ORDER BY co_end DESC";
		$results = $database->select($sql);
		return $results;
	}

	//------------------------ CLASS METHODS ------------------------
	
	/*
		Goal: fetch an array of all gear items in the DB
		Notes: just calls the getGearListWithType method
		Returns: Array of Associative Arrays
	*/
	public static function getGearList() {
		return self::getGearListWithType(NULL);
	}

	/*
		Goal: fetch an array of all gear items with $type in the DB
		Returns: Array of Associative Arrays
	*/
	public static function getGearListWithType($type) {
		$database = new DB();

		if (is_null($type)){
			$sql = 	"SELECT * FROM gear";
		} else { //type ID passed in
			$sql = "SELECT * FROM gear WHERE gear_type_id='$type' ORDER BY name";
		}

		return $database->select($sql);
	}

	/*
		Goal: fetch all available gear in the time range co_start to co_end
		Returns: Array of Associative Arrays
	*/
	public static function getAvailableGear($co_start, $co_end) {
		return self::getAvailableGearWithTypeAndExclusions(NULL, NULL, $co_start, $co_end);
	}

	/*
		Goal: fetch all available gear in the time range co_start to co_end
			that is of type $type
		Returns: Array of Associative Arrays
	*/
	public static function getAvailableGearWithType($type, $co_start, $co_end) {
		return self::getAvailableGearWithTypeAndExclusions($type, NULL, $co_start, $co_end);
	}

	/*
		Goal: fetch all available gear in the time range co_start to co_end
			that is of type $type and excludes checkouts with co_id
		Returns: Array of Associative Arrays
		Usage: Mainly in editing checkouts
			$type may be null -- all types
			$co_id may be null -- no excluded checkouts
	*/
	public static function getAvailableGearWithTypeAndExclusions($type, $co_id, $co_start, $co_end) {
		$results = self::getGearListWithType($type);

		if(is_null($co_id)){ //do not exclude any checkouts
			foreach($results as $row) {
				//check if in stock for dates
				$gearObject = new Gear();
				$gearObject->fetch($row['gear_id']);

	    		if($gearObject->availableQty($co_start, $co_end) > 0 && !$gearObject->isDisabled()){
	        		//add object to return array if in stock
	        		$availableGear[] = $row;
	    		}
			}
		} else { //with exclusions
			foreach($results as $row) {
				//check if in stock for dates
				$gearObject = new Gear();
				$gearObject->fetch($row['gear_id']);

	    		if($gearObject->availableQtyExcludingCheckout($co_id, $co_start, $co_end) > 0 && !$gearObject->isDisabled()){
	        		//add object to return array if in stock
	        		$availableGear[] = $row;
	    		}
			}
		}
		return $availableGear;
	}
}//end class

//----------------------------------------------------------------------
//------------------------ Gear Types Functions ------------------------
//----------------------------------------------------------------------

	/*
		Goal: add new gear category, $type, to the DB 
		Returns: the ID of the new gear category
	*/
	function newGearType($type) {
		$database = new DB();
		$sql = "INSERT INTO gear_types(type) VALUES('$type')";
		$database->query($sql);

		//fetch the newly inserted type's ID.
		$sql = "SELECT gear_type_id FROM gear_types WHERE type='$type'";
		$results = $database->select($sql);

		return $results[0]['gear_type_id'];
	}

	/*
		Goal: remove a gear type from the DB
		Side Effects: Will remove any gear associated with this type
	*/
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