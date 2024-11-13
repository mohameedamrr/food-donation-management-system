<?php
require_once 'DonationItem.php';

abstract class NonBillableDonate extends DonationItem {
    protected $expiryDate; // DateTime object
    protected $itemImage;  // Image data or path
    protected $weight;

    public function addImage() {
        // Add an image to the donation item
    }

    public function checkItems(): bool {
        // Check the validity of items
    }
}
?>
