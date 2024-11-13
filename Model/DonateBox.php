<?php
require_once 'BillableDonate.php';

abstract class DonateBox extends BillableDonate {


    public abstract function getContentDetails(): string ;
    public function executeDonation(): bool
    {
        // Get the instance of DatabaseManager
        $db = DatabaseManager::getInstance();

        // Construct the SQL query to insert the donation data
        $query = "
        INSERT INTO food_donation.billable_donations (user_id, donation_type, amount, animal_type, description) 
        VALUES (5, 'Box', {$this->calculateCost()}, NULL, '{$this->getContentDetails()}');
    ";

        // Execute the query and check if it was successful
        if ($db->runQuery($query) !== false) {
            return true; // Insertion was successful
        } else {
            // Log or handle the error

            return false; // Insertion failed
        }

    }

}
?>
