<?php
require_once 'NonBillableDonate.php';
require_once 'DatabaseManager.php';
class DonateReadyMeal extends NonBillableDonate {
    private $mealType;

    private $packagingType;


	public function __construct(int $DonationItemID) {
        parent::__construct($DonationItemID);
    }


	public function createReadyMealItems($itemName, $weight, $expiryDate, $itemImage, $mealType, $packagingType): bool {
		$isSuccess = parent::createDonationItems($itemName, $weight, 0, 1, 0, 0, 0, 0); // Set readymeals flag to 1
		
		if (!$isSuccess) {
			return false;
		}

        // $expiryDateStr = $expiryDate instanceof DateTime ? $expiryDate->format('Y-m-d') : $expiryDate;
		$sql = "INSERT INTO ready_meals_donation (itemID, expiryDate, itemImage, mealType, packagingType,isDeleted) VALUES
			('$this->itemID', '2025-06-20', 'NULL', '$mealType', '$packagingType', 0)";

		$conn = DatabaseManager::getInstance();
		$isSuccess = $conn->run_select_query($sql);

		return $isSuccess;
	}

	public static function deleteObject($id){
        $sql = "UPDATE ready_meals_donation
        SET isDeleted = 1 
        WHERE itemID = '$id'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}
	
    public function getReadyMealItemsInstance(): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM ready_meals_donation WHERE itemID = $this->itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->expiryDate = $row['expiryDate'];
		// $this->cost = $row['cost'];
		//$this->itemID = $row['itemID'];
        $this->mealType = $row['mealType'];
        $this->packagingType = $row['packagingType'];
		parent::getDonationItemInstance($this->itemID);		
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}
    public function getItemDetails() {
        //$expiryDateStr = $this->expiryDate ? $this->expiryDate->format('Y-m-d') : 'N/A';
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}, Meal Type: {$this->mealType}, Packaging Type: {$this->packagingType}";
    }

	public function setmealType($value) :void{
		$this->mealType = $value;
		$sql = "UPDATE ready_meals_donation 
        SET mealType = '$value';
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
}
	public function setpackagingType($value) :void{
		$this->packagingType = $value;
		$sql = "UPDATE ready_meals_donation 
        SET packagingType = '$value';
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
}

}
?>
