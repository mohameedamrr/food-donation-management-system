<?php
require_once 'BillableDonate.php';

abstract class DonateBox extends BillableDonate {


    public function __construct()
    {
    }

    public abstract function getContentDetails(): string ;
    public function executeDonation(): bool
    {
        // Get the instance of DatabaseManager
        $db = DatabaseManager::getInstance();

        // Query to get the itemID for 'Box' from the donation_items table
        $query1 = "SELECT itemID FROM donation_items WHERE itemName = 'Box' LIMIT 1";

        // Execute the query
        $result = $db->runQuery($query1);

        // Check if the record exists
        if ($result && $row = $result->fetch_assoc()) {
            // Get the existing itemID
            $itemID = $row['itemID'];

            // Insert the donation data into the billable_donations table
            $query2 = "
            INSERT INTO food_donation.billable_donations (itemID, animal_type, description, amount) 
            VALUES (
                $itemID, 
                NULL, 
                '{$this->getContentDetails()}', 
                '{$this->calculateCost()}'
            );
        ";

            // Execute the second query
            if ($db->runQuery($query2) !== false) {
                return true; // Insertion was successful
            } else {
                echo "Query 2 failed";
                return false; // Insertion failed
            }
        } else {
            echo "Query 1 failed: 'Box' not found in donation_items table";
            return false; // No 'Box' record found
        }
    }


}
?>
