<?php
class DonationDetails {
    private $id;
    private $quantity;
    private $totalCost;
    private $description;
    private $donationId;
    private $donationItemsId; // array of int

    public function getDetails(): array {
        // Return donation details as an associative array
    }

    public function setDetails(Donate $donate): bool {
        // Set donation details based on a Donate object
    }
}
?>
