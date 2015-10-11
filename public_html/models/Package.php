<?php
require_once('Gear.php');

class Package {
	private $pkg_id;
	private $title;
	private $description;
	private $gearList = array();

	//------------------------ getters ------------------------

	public function getID() {
		return $this->pkg_id;
	}

	public function getTitle() {
		return $this->title;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getGearList() {
		return $this->gearList;
	}

	public function printObject() {
		return "<hr>ID:$this->pkg_id<br>TITLE:$this->title<br>DESC:$this->description<hr>";
	}

	//------------------------ Setters ------------------------

	public function setID($pkg_id) {
		$this->pkg_id = $pkg_id;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function addToGearList($gear_id) {
		$this->gearList[] = $gear_id;
	}
	
	public function removeFromGearList($gear_id){
		foreach($gearList as $key => $gear){
			if($gear == $gear_id){
				unset($this->gearList[$key]);
				break;
			}
		}
	}

	//------------------------ DB ------------------------

	//get the packages's related information from the db
	public function retrievePackage($pkg_id){
		$database = new DB();
		$sql = "SELECT * FROM packages WHERE pkg_id='$pkg_id'";
		$results = $database->select($sql);
		
		//set local vars
		$this->pkg_id = $results[0]['pkg_id'];
		$this->title = $results[0]['title'];
		$this->description = $results[0]['description'];

		//query DB for the pkg-gear combos
		$sql = "SELECT gear_id FROM packages_gear WHERE pkg_id='$pkg_id'";
		$results = $database->select($sql);

		//iterate through results and add to the gear array
		foreach ($results as $row){ 
			$this->gearList[] = $row['gear_id'];
	 	}
	}

	public function finalizePackage(){
		$database = new DB();
		
		//if the pkg_id is unset, this is a new package
		if(isset($this->pkg_id)){ //old package
			$sql = "UPDATE packages SET title='$this->title', description='$this->description' WHERE pkg_id='$this->pkg_id'";
			$database->query($sql);

			//remove all old pkg_gear relations from table
			$sql = "DELETE FROM packages_gear WHERE pkg_id='$this->pkg_id'";
			$database->query($sql);
		} else { //new package
			//create the new package
			$sql = "INSERT INTO packages(title,description) VALUES('$this->title','$this->description')";
			$database->query($sql);

			//retrieve pkg_id from newly inserted package
			$sql = "SELECT pkg_id FROM packages WHERE title='$this->title' AND description='$this->description'";
			$results = $database->select($sql);
			$this->pkg_id = $results[0]['pkg_id'];
		}

		//add the package gear relations
		foreach($this->gearList as $gearItem){
			$sql = "INSERT INTO packages_gear(gear_id,pkg_id) VALUES('$gearItem','$this->pkg_id')";	
			$database->query($sql);
		}
	}//finalizePackage

	public static function removePackage($pkg_id){
		$database = new DB();
		$sql = "DELETE FROM packages WHERE pkg_id='$pkg_id'"; 
		$database->query($sql);
	}
}

?>