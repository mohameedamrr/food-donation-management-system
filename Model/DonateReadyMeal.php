<?php
require_once 'NonBillableDonate.php';

class DonateReadyMeal extends NonBillableDonate {
    private $mealType;

    private $packagingType;

    public function createDonationItems($itemID, $itemName, $weight, $expiryDate, $itemImage, $mealType,$packagingType): bool {
        $sql = "INSERT INTO `ready_meals_donation` (itemName, itemWeight, expiryDate, itemImage, mealType, packagingType) VALUES
        ($itemName,$weight,$expiryDate,$itemImage,$mealType,$packagingType)";
        
        $conn = DatabaseManager::getInstance();	

		$isSuccess =$conn->run_select_query($sql);
        
        return $isSuccess;


    }
    public function getDonationItemInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM ready_meals_donation WHERE id = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->itemName = $row['itemName'];
		$this->weight = $row['itemWeight'];
		$this->expiryDate = $row['expiryDate'];
		$this->cost = $row['cost'];
		$this->itemID = $row['id'];
        $this->mealType = $row['mealType'];
        $this->packagingType = $row['packagingType'];

		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}

}
?>
