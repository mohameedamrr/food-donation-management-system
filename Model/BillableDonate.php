<?php
require_once 'DonationItem.php';
require_once 'DatabaseManager.php';
abstract class BillableDonate extends DonationItem {
    protected $currency;
    protected $cost;

    public abstract function calculateCost(): float;
    public abstract function executeDonation(): bool;

    public static function getDonationById(int $itemID): ?array {
        $db = DatabaseManager::getInstance();
        $query = "SELECT * FROM `food_donation`.`billable_donations` WHERE `itemID` = $itemID";
        $result = $db->runQuery($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row; // Return the single record as an associative array
        }
        return null; // Return null if no record is found
    }

    // Function to get all donations as an array list
    public static function getAllDonations(): array {
        $db = DatabaseManager::getInstance();
        $query = "SELECT * FROM `food_donation`.`billable_donations`";
        $result = $db->runQuery($query);
        
        $donations = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $donations[] = $row; // Append each row to the donations array
            }
        }
        return $donations; // Return the array list of donations
    }

}
?>
