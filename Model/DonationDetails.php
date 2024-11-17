<?php
class DonationDetails {
    private static $idCounter = 0;
    private $id;
    private $totalCost;
    private $description;
    private $donationId;
    private $donationItems; // Associative array: itemID => quantity

    public function __construct(){
        $this->idCounter++;
        $this->id = $this->idCounter; 
    }


    // Getters and Setters
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getTotalCost(): float {
        return $this->totalCost;
    }

    public function setTotalCost(float $totalCost): void {
        $this->totalCost = $totalCost;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getDonationId(): int {
        return $this->donationId;
    }

    public function setDonationId(int $donationId): void {
        $this->donationId = $donationId;
    }

    public function getDonationItems(): array {
        return $this->donationItems;
    }

    public function setDonationItems(array $donationItems): void {
        $this->donationItems = $donationItems;
    }

    // Methods
    public function getDetails(): array {
        return [
            'id' => $this->id,
            'totalCost' => $this->totalCost,
            'description' => $this->description,
            'donationId' => $this->donationId,
            'donationItems' => $this->donationItems,
        ];
    }
 
public function setDetails(Donate $donate): bool {

    $conn = DatabaseManager::getInstance();
    $this->donationId = $donate->getDonationID();
    $this->donationItems = [];

    // Assuming $donate->getDonationItems() returns an array of DonationItem => quantity
    foreach ($donate->getDonationItems() as $item => $quantity) {
        if ($item instanceof DonationItem) {
            $this->donationItems[$item->getItemID()] = $quantity;
            $tempId = $item->getItemID();
            $sql = "INSERT INTO donation_detail_items (donationDetailId, itemId, quantity) VALUES
                ('$this->id', '$tempId', '$quantity')";
        $isSuccess = $conn->run_select_query($sql);
        }
    }

    $this->totalCost = $donate->calculateTotalCost();
    $this->description = "Donation Details for Donation ID " . $this->donationId;

    //_____________________create in database_______________________//
   

    // Insert into 'donation_details' table
    $sql = "INSERT INTO donation_details (id, totalCost, description, donationId) VALUES
            ('$this->id', '$this->totalCost', '$this->description', '$this->donationId')";
    $isSuccess = $conn->run_select_query($sql);

    if (!$isSuccess) {
        return false;
    }

    // Insert each item into 'donation_details_items' table
    // foreach ($this->donationItems as $itemId => $quantity) {
    //     $sql = "INSERT INTO donation_details_items (donationDetailId, itemId, quantity) VALUES
    //             ('$this->id', '$itemId', '$quantity')";
    //     $isSuccess = $conn->run_select_query($sql);

    //     if (!$isSuccess) {
    //         return false;
    //     }
    // }

    return true;
}
}
?>