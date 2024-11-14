<?php

require_once "DonationItem.php";
require_once "NonBillableDonate.php";
require_once "DonationDetails.php";

class Donate {
    private static $donationIdCounter = 0;
    private $donationID;
    private $donationDate; // DateTime object
    private $userId;
    private array $donationItems; // array of DonationItem => quantity

    public function __construct( DateTime $donationDate, int $userId, array $donationItems) {
        $this->donationIdCounter ++;
        $this->donationID = $this->donationIdCounter;
        $this->donationDate = $donationDate;
        $this->userId = $userId;
        $this->donationItems = $donationItems;
    }

    // Getters and Setters
    public function getDonationID(): int {
        return $this->donationID;
    }

    public function setDonationID(int $donationID): void {
        $this->donationID = $donationID;
    }

    public function getDonationDate(): DateTime {
        return $this->donationDate;
    }

    public function setDonationDate(DateTime $donationDate): void {
        $this->donationDate = $donationDate;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function getDonationItems(): array {
        return $this->donationItems;
    }

    public function setDonationItems(array $donationItems): void {
        $this->donationItems = $donationItems;
    }

    // Methods

    public function calculateTotalCost(): float {
        $totalCost = 0.0;
        foreach ($this->donationItems as $item => $quantity) {
            if ($item instanceof DonationItem) {
                $totalCost += $item->getCost() * $quantity;
            }
        }
        return $totalCost;
    }


    public function donate($donationItemsMap): bool {
        
        foreach ($donationItemsMap as $itemId => $quantity) {
            $donationItem = new DonationItem($itemId); // donation Item object
            $donationItemMap = [$donationItem => $quantity]; // mapping object to quantities
            array_push($this->donationItems, $donationItemMap); // adding the new item object to the class map attribute

        }
        
        // Logic to process the donation
        if($this->donationItems[0][0] instanceof NonBillableDonate){
            $user = new BasicDonator($this->userId, Null);
            $appointment = new Appointment(0,NULL);
        }

        $donationDetails = new DonationDetails();
        $donationDetails->setDetails($this);

        return true;
    }
}
?>
