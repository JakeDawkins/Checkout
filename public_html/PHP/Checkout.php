<?php
//require_once('../db.php');
require_once('Gear.php');

class Checkout {
	private $co_id;
	private $title;
	private $person_id;
	private $co_start;
	private $co_end;
	private $description;
	private $gearList = array();

	//------------------------ getters ------------------------
	public function getID() {
		return $co_id;
	}
	public function getTitle() {
		return $title;
	}
	public function getPerson() {
		return $person_id;
	}
	public function getStart() {
		return $co_start;
	}
	public function getEnd() {
		return $co_end;
	}
	public function getDescription() {
		return $description;
	}
	public function getGearList() {
		return $gearList;
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
	public function addItemToGearList($gear_id) {
		//if (!in_array($gear_id, $this->gearList)){
			$this->gearList[] = $gear_id;
		//}
	}


	//------------------------ DB ------------------------
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

		//TODO - Query DB for the checkout-gear combos
		$sql = "SELECT gear_id FROM co_gear WHERE co_id='$co_id'";
		$results = $database->select($sql);

		//iterate through results and add to the gear array
		foreach ($results as $row){ 
			$this->gearList[] = $row['gear_id'];
	 	}
	}

	public function finalizeCheckout(){
		$database = new DB();

		//if the co_id is unset, this is a new checkout
		if(isset($this->co_id)){
			printf("old checkout<br>");
			// $sql = "UPDATE checkouts SET title='$title', person_id='$person_id', co_start='$co_start', co_end='$co_end', description='$description' WHERE id=$co_id";
			// //query
			// printf("___%s",$sql);

			// $sql = "SELECT * FROM co_gear WHERE co_id='$co_id'";
			// printf("___%s",$sql);
			// //query
			// //find a way to update the table

		} else { //new checkout
			//create the new checkout
			printf("new checkout<br>");
			$sql = "INSERT INTO checkouts(title,person_id,co_start,co_end,description) VALUES('$this->title','$this->person_id','$this->co_start','$this->co_end','$this->description')";
			printf("___%s<br>",$sql);
			//$database->query($sql);

			//retrieve co_id from newly inserted checkout
			$sql = "SELECT co_id FROM checkouts WHERE person_id='$this->person_id' AND co_start='$this->co_start' AND co_end='$this->co_end'";
			printf("___%s<br>",$sql);
			$results = $database->select($sql);
			$this->co_id = $results[0]['co_id'];
			printf("___id is: %d",$this->co_id);

			printf("___gear list count: %d<br>",count($this->gearList));

			//add the checkout gear relations
			foreach($this->gearList as $gearItem){
				printf("iterate...<br>");
				//check to see if the item is available
				if (isAvailable($gearItem,$this->co_start,$this->co_end)){
					$sql = "INSERT INTO co_gear(gear_id,co_id) VALUES('$gearItem','$this->co_id')";	
					printf("___%s<br>",$sql);
					$database->query($sql);
				} else {
					//do some error logging here...
					printf("_!_item not available: %s<br>",$gearItem);
				}
			}//foreach
		}//else	
	}//finalizeCheckout
}

?>