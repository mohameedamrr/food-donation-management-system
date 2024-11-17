<?php
require_once 'BillableDonate.php';

class DonateMoneyItem extends BillableDonate {
    private $amount;
    private $id;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }
    private $donationPurpose;


    public function getReceipt(): string {
        return $this->donationPurpose." The cost of this is:".$this->amount;
    }

    public function calculateCost(): float
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getDonationPurpose()
    {
        return $this->donationPurpose;
    }

    /**
     * @param mixed $donationPurpose
     */
    public function setDonationPurpose($donationPurpose): void
    {
        $this->donationPurpose = $donationPurpose;
    }

    public function executeDonation(): bool
    {
       // Get the instance of DatabaseManager
    $db = DatabaseManager::getInstance();

    // Query to get the itemID for 'Money' from the donation_items table
    $query1 = "SELECT itemID FROM donation_items WHERE itemName = 'Money' LIMIT 1";

    // Execute the query
    $result = $db->runQuery($query1);

    // Check if the record exists
    if ($result && $row = $result->fetch_assoc()) {
        // Get the existing itemID
        $itemID = $row['itemID'];

        // Insert the donation data into the billable_donations table
        $query2 = "
            INSERT INTO food_donation.billable_donations (itemID, animal_type, description, amount) 
            VALUES ($itemID, NULL, '{$this->getDonationPurpose()}', '{$this->calculateCost()}');
        ";

        // Execute the second query
        if ($db->runQuery($query2) !== false) {
            return true; // Insertion was successful
        } else {
            echo "Query 2 failed";
            return false; // Insertion failed
        }
    } else {
        echo "Query 1 failed: 'Money' not found in donation_items table";
        return false; // No 'Money' record found
    }

}}
?>
