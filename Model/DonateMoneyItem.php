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

        // Construct the SQL query to insert the donation data

        $query1 =  "INSERT INTO donation_items (itemName, itemWeight, israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox) VALUES
			('Money', 0, 0, 0, 0, 1, 0, 0)";

        // Execute the query and check if it was successful
        if ($db->runQuery($query1) !== false) {
            $itemID = $db->getLastInsertId();
            $query2 = "
        INSERT INTO food_donation.billable_donations (id, animal_type, description, amount) 
        VALUES ($itemID, 'Null', '{$this->getDonationPurpose()}','{$this->calculateCost()}');
    ";
            if ($db->runQuery($query2) !== false) {

                return true;
            }
            else{
                echo "query 2 failed";
                return false;
            }


            return true; // Insertion was successful
        } else {
            echo "query 1 failed";
            return false; // Insertion failed
        }

    }

}
?>
