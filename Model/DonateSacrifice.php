<?php
// classes/DonateSacrifice.php
require_once 'BillableDonate.php';

class DonateSacrifice extends BillableDonate {
    private $animalType;
    private $location; // Location for sacrifice donation

    public function __construct($itemID, $itemName, $weight, $expiryDate, $cost, $currency, $animalType) {
        parent::__construct($itemID, $itemName, $weight, $expiryDate, $cost, $currency);
        $this->animalType = $animalType;
    }

    public function calculateCost() {
        // Calculate cost based on animal type and weight
        // For simplicity, we'll use the cost property
        return $this->cost;
    }
	public function getAnimalType() {
		return $this->animalType;
	}

	public function setAnimalType($value) {
		$this->animalType = $value;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation($value) {
		$this->location = $value;
	}
    // Additional methods as needed
}
?>
