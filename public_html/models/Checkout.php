<?php
require_once('Gear.php');

class Checkout {
	private $co_id;
	private $title;
	private $person_id;
	private $co_start;
	private $co_end;
	private $description;
	private $gearList = array();
	private $returned;

	//------------------------ getters ------------------------

	public function getID() {
		return $this->co_id;
	}

	public function getTitle() {
		return $this->title;
	}
	
	public function getPerson() {
		return $this->person_id;
	}
	
	public function getStart() {
		return $this->co_start;
	}
	
	public function getEnd() {
		return $this->co_end;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getGearList() {
		return $this->gearList;
	}

	public function printObject() {
		return "<hr>ID:$this->co_id<br>TITLE:$this->title<br>PERSON:$this->person_id<br>START:$this->co_start<br>END:$this->co_end<br>DESC:$this->description<br>RET:$this->returned<hr>";
	}

	public function getReturned() {
		return $this->returned;
	}

	public function qtyOfItem($gear_id) {
		foreach($this->gearList as $gear){
			if($gear[0] == $gear_id) return $gear[1];
		}
		return -1;
	}

	//------------------------ Setters ------------------------

	public function setID($co_id) {
		$this->co_id = $co_id;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setPerson($person_id) {
		$this->person_id = $person_id;
	}
	
	public function setStart($co_start) {
		$this->co_start = $co_start;
	}
	
	public function setEnd($co_end) {
		$this->co_end = $co_end;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function addToGearList($gear_id, $qty) {
		$this->gearList[] = array();
		$end = count($this->gearList)-1;
		$this->gearList[$end][] = $gear_id;
		$this->gearList[$end][] = $qty;
	}
	
	public function removeFromGearList($gear_id){
		//check to see if gear item in array
		$i = 0;
		foreach($gearList as $gear){
			if($gear[0] == $gear_id){
				unset($this->gearList[$i]);
				break;
			}
		$i++;
		}
	}

	public function setReturned($returned){
		$this->returned = $returned;
	}

	//------------------------ DB ------------------------

	//returns an array of all Checkout Objects
	public static function getCheckoutsInRange($start, $end){
		$database = new DB();
		$checkouts = array();

		$sql = "SELECT co_id, co_start FROM checkouts ORDER BY co_start";
		$results = $database->select($sql);

		foreach ($results as $result) {
			if($result['co_start'] > $start && $result['co_start'] < $end){
				$newCO = new Checkout();
				$newCO->retrieveCheckout($result['co_id']);
				$checkouts[] = $newCO;
				//printf("%s",$newCO->printObject()); //check construction
			}
		}//foreach
		
		return $checkouts;
	}

	public static function getCheckoutsInRangeForPerson($person_id, $start, $end){
		$database = new DB();
		$checkouts = array();

		$sql = "SELECT co_id, co_start FROM checkouts WHERE person_id='$person_id' ORDER BY co_start";
		$results = $database->select($sql);

		foreach ($results as $result) {
			if($result['co_start'] > $start && $result['co_start'] < $end){
				$newCO = new Checkout();
				$newCO->retrieveCheckout($result['co_id']);
				$checkouts[] = $newCO;
				//printf("%s",$newCO->printObject()); //check construction
			}
		}//foreach
		
		return $checkouts;
	}

	//get the checkout's related information from the db
	public function retrieveCheckout($co_id){
		$database = new DB();
		$sql = "SELECT * FROM checkouts WHERE co_id='$co_id'";
		$results = $database->select($sql);
		
		//set local vars
		$this->co_id = $results[0]['co_id'];
		$this->title = $results[0]['title'];
		$this->person_id = $results[0]['person_id'];
		$this->co_start = $results[0]['co_start'];
		$this->co_end = $results[0]['co_end'];
		$this->description = $results[0]['description'];
		$this->returned = $results[0]['returned'];

		//query DB for the checkout-gear combos
		$sql = "SELECT gear_id,qty FROM co_gear WHERE co_id='$co_id'";
		$results = $database->select($sql);

		//iterate through results and add to the gear array
		foreach ($results as $row){ 
			$this->gearList[] = array();
			$end = count($this->gearList)-1;
			$this->gearList[$end][] = $row['gear_id'];
			$this->gearList[$end][] = $row['qty'];
	 	}
	}

	public function finalizeCheckout(){
		$database = new DB();
		
		//if the co_id is unset, this is a new checkout
		if(isset($this->co_id)){ //old checkout
			$sql = "UPDATE checkouts SET title='$this->title', person_id='$this->person_id', co_start='$this->co_start', co_end='$this->co_end', description='$this->description' WHERE co_id='$this->co_id'";
			$database->query($sql);

			//set return datetime
			if(isset($this->returned)){
				$sql = "UPDATE checkouts SET returned='$this->returned' WHERE co_id='$this->co_id'";
				$database->query($sql);	
			} 

			//remove all old co_gear relations from table
			$sql = "DELETE FROM co_gear WHERE co_id='$this->co_id'";
			$database->query($sql);
		} else { //new checkout
			//create the new checkout
			$sql = "INSERT INTO checkouts(title,person_id,co_start,co_end,description) VALUES('$this->title','$this->person_id','$this->co_start','$this->co_end','$this->description')";
			$database->query($sql);

			//retrieve co_id from newly inserted checkout
			$sql = "SELECT co_id FROM checkouts WHERE person_id='$this->person_id' AND co_start='$this->co_start' AND co_end='$this->co_end' AND description='$this->description'";
			$results = $database->select($sql);
			$this->co_id = $results[0]['co_id'];
		}

		//add the checkout gear relations
		foreach($this->gearList as $gearItem){
			//check to see if the item is available
			if (availableQty($gearItem[0],$this->co_start,$this->co_end) > 0){
				$gear_id = $gearItem[0];
				$gearQty = $gearItem[1];
				$sql = "INSERT INTO co_gear(gear_id,co_id,qty) VALUES('$gear_id','$this->co_id','$gearQty')";	

				$database->query($sql);
			} else { //requested item not available
				//do some error logging here...
			}
		}//foreach
	}//finalizeCheckout

	public static function removeCheckout($co_id){
		$database = new DB();
		$sql = "DELETE FROM checkouts WHERE co_id='$co_id'"; 
		$database->query($sql);
	}
}

?>