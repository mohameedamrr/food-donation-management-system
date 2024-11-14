<?php
require_once 'NonBillableDonate.php';
require_once 'DatabaseManager.php';
class DonateReadyMeal extends NonBillableDonate {
    private $mealType;

    private $packagingType;


	public function createReadyMealItems($itemName, $weight, $expiryDate, $itemImage, $mealType, $packagingType): bool {
		$isSuccess = parent::createDonationItems($itemName, $weight, 0, 1, 0, 0, 0, 0); // Set readymeals flag to 1
		
		if (!$isSuccess) {
			return false;
		}

        // $expiryDateStr = $expiryDate instanceof DateTime ? $expiryDate->format('Y-m-d') : $expiryDate;
		$sql = "INSERT INTO ready_meals_donation (itemID, expiryDate, itemImage, mealType, packagingType) VALUES
			('$this->itemID', '2025-06-20', 'NULL', '$mealType', '$packagingType')";

		$conn = DatabaseManager::getInstance();
		$isSuccess = $conn->run_select_query($sql);

		return $isSuccess;
	}
	
    public function getReadyMealItemsInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM ready_meals_donation WHERE itemID = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		parent::getDonationItemInstance($itemID);
		$this->expiryDate = $row['expiryDate'];
		// $this->cost = $row['cost'];
		$this->itemID = $row['itemID'];
        $this->mealType = $row['mealType'];
        $this->packagingType = $row['packagingType'];

		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}
    public function getItemDetails() {
        //$expiryDateStr = $this->expiryDate ? $this->expiryDate->format('Y-m-d') : 'N/A';
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}, Meal Type: {$this->mealType}, Packaging Type: {$this->packagingType}";
    }
}
?>
