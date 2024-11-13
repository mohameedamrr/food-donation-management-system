<?php
abstract class DonationItem {
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

    public function __construct(int $itemID) {
		$this->itemIDCounter ++;
        $this->itemID = $this->itemIDCounter;
        //database manager
    }

    public function getItemDetails(): array {
        return [
            'itemID'     => $this->itemID,
            'itemName'   => $this->itemName,
            'weight'     => $this->weight,
            'expiryDate' => $this->expiryDate->format('Y-m-d'),
            'cost'       => $this->cost
        ];
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


