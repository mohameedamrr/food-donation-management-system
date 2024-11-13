<?php
require_once 'DonationItem.php';

abstract class NonBillableDonate extends DonationItem {
    protected $itemImage;  // Path to the image file
    protected $weight;
    protected $expiryDate;

    public function __construct() {

    }

    public function addImage($imagePath): void {
        // Assume $imagePath is a valid path to the image file
        $this->itemImage = $imagePath;
    }

    public function checkItems(): bool {
        // Check if the item is not expired
        $now = new DateTime();
        if ($this->expiryDate < $now) {
            return false; // Item is expired
        }
        // Additional checks can be implemented here
        return true;
    }

    public function getItemImage() {
		return $this->itemImage;
	}

	public function setItemImage($value) {
		$this->itemImage = $value;
	}

	public function getWeight() {
		return $this->weight;
	}

	public function setWeight($value) {
		$this->weight = $value;
	}
}
?>

	
