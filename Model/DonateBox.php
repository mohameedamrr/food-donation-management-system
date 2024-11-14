<?php
require_once 'BillableDonate.php';

abstract class DonateBox extends BillableDonate {


    public abstract function getContentDetails(): string ;
    public function executeDonation(): bool
    {
        // Get the instance of DatabaseManager
        $db = DatabaseManager::getInstance();

        // Construct the SQL query to insert the donation data

        $query1 =  "INSERT INTO donation_items (itemName, itemWeight, israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox) VALUES
			('Box', {$this->getWeight()}, 0, 0, 0, 0, 0, 1)";

        // Execute the query and check if it was successful
        if ($db->runQuery($query1) !== false) {
            $itemID = $db->getLastInsertId();
            $query2 = "
        INSERT INTO food_donation.billable_donations (id, animal_type, description, amount) 
        VALUES ($itemID, NULL,  '{$this->getContentDetails()}','{$this->calculateCost()}');
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
