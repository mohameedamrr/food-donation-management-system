<?php
require_once ("DatabaseManager.php");
class DonationItem {
	private static $itemIDCounter = 0;
    protected $itemID;
    protected $itemName;
    protected $weight;
    protected $expiryDate; // DateTime object
    protected $cost;

    //Database
    // public function __construct(int $itemID) {
    //     $this->itemID = $itemID;
    // }

    public function __construct() {
        //database manager
    }



    public function getItemDetails() {
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}";
    }

    public function setItemID($value) {
		$this->itemID = $value;
	}

	public function setItemName($value) {
		$this->itemName = $value;
	}

	public function setWeight($value) {
		$this->weight = $value;
	}

	public function setExpiryDate($value) {
		$this->expiryDate = $value;
	}

	public function setCost($value) {
		$this->cost = $value;
	}

	public function getItemID() {
		return $this->itemID;
	}

	public function getItemName() {
		return $this->itemName;
	}

	public function getWeight() {
		return $this->weight;
	}

	public function getExpiryDate() {
		return $this->expiryDate;
	}

	public function getCost() {
		return $this->cost;
	}
}
?>


