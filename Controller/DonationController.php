<?php
require_once '../Model/DonationItem.php';
require_once '../Model/NonBillableDonate.php';
require_once '../Model/DatabaseManager.php';

class DonationController {
    private $donationItem;

    public function __construct(DonationItem $donationItem) {
        $this->donationItem = $donationItem;
    }

    // Add a new donation item
    public function addDonationItem($itemName, $itemWeight, $itemImage) {
        $this->donationItem->setItemName($itemName);
        $this->donationItem->setWeight($itemWeight);
        echo "Donation item added successfully.";
    }

    // View donation item details
    public function viewDonationItem($itemId) {
        // Assuming the method to fetch donation details by ID exists
        $itemDetails = $this->donationItem->getItemDetails();
        return $itemDetails;
    }
}
?>
