<?php
abstract class DonationItem {
    protected $itemID;
    protected $itemName;
    protected $weight;
    protected $expiryDate; // DateTime object
    protected $cost;

    public function getItemDetails(): array {
        // Return item details as an associative array
    }
}
?>

