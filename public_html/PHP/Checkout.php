<?php
require_once('../db.php');
require_once('Gear.php');

class Checkout {
	private $co_id;
	private $title;
	private $person_id;
	private $co_start;
	private $co_end;
	private $description;
	private $gearList;

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

	//------------------------  ------------------------
	public function writeCheckout() {


		
		$database = new DB();
		$sql = sprintf();
		database->query($sql);
	}









}

?>