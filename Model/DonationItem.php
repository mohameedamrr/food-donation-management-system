<?php
// classes/DonationItem.php

abstract class DonationItem {
    protected $itemID;
    protected $itemName;
    protected $weight;
    protected $expiryDate;
    protected $cost;

    public function __construct($itemID, $itemName, $weight, $expiryDate, $cost) {
        $this->itemID = $itemID;
        $this->itemName = $itemName;
        $this->weight = $weight;
        $this->expiryDate = $expiryDate;
        $this->cost = $cost;
    }

    public function getItemDetails() {
        return array(
            'itemID' => $this->itemID,
            'itemName' => $this->itemName,
            'weight' => $this->weight,
            'expiryDate' => $this->expiryDate,
            'cost' => $this->cost,
        );
    }

    // Additional methods as needed
}
?>
