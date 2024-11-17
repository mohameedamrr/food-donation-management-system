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


    public function donate($donationItemsMap): bool
{
    foreach ($donationItemsMap as $itemId => $quantity) {
        $donationItem = new DonationItem($itemId); // Create a DonationItem object
        $itemFlags = $donationItem->getFlags(); // Retrieve flags for the item

        // Determine the type of donation based on flags
        $donationObject = null;
        if ($itemFlags['israwmaterial']) {
            $donationObject = new DonateRawMaterials($itemId);
            $donationObject = $donationObject->getRawMaterialItemsInstance(); 
        } elseif ($itemFlags['isreadymeal']) {
            $donationObject = new DonateReadyMeal($itemId);
            $donationObject = $donationObject->getReadyMealItemsInstance(); 
        } elseif ($itemFlags['ismeal']) {
            $donationObject = new DonateMeal($itemId);
            $donationObject = $donationObject->getMealItemInstance(); 
        } elseif ($itemFlags['ismoney']) {
           // $donationObject = new DonateMoneyItem();
        } elseif ($itemFlags['issacrifice']) {
           // $donationObject = new DonateSacrificeItem();
        } elseif ($itemFlags['isbox']) {
           // $donationObject = new DonateBox();
        } else {
            throw new Exception("Invalid donation item type for item ID: $itemId");
        }

        // Map the donation object to its quantity
        $donationItemMap = [$donationObject => $quantity];
        array_push($this->donationItems, $donationItemMap); // Add the donation object to the class map attribute
    }

    // Logic to process the donation
    if ($this->donationItems[0][0] instanceof NonBillableDonate) {
        $user = new BasicDonator($this->userId, null);
        //Appointment::storeObject();
        
    }

    $donationDetails = new DonationDetails();
    $donationDetails->setDetails($this);

    return true;
}
}
?>
