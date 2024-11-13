<?php
require_once 'NonBillableDonate.php';

class DonateMeal extends NonBillableDonate {
    private $mealType;
    private $servings;
    private $ingredients; // Array of ingredient names

    public function __construct(

    ) {

    }

    public function createDonationItems($itemName, $weight, $expiryDate, $itemImage, $mealType,$servings, $ingredients): bool {
        $sql = "INSERT INTO `meals_donation` (itemName, itemWeight, expiryDate, itemImage, mealType, servings, ingredients) VALUES
        ($itemName,$weight,$expiryDate,$itemImage,$mealType,$servings,$ingredients)";
        
        $conn = DatabaseManager::getInstance();	

		$isSuccess =$conn->run_select_query($sql);
        
        return $isSuccess;


    }
    public function getDonationItemInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM meals_donation WHERE id = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->itemName = $row['itemName'];
		$this->weight = $row['itemWeight'];
		$this->expiryDate = $row['expiryDate'];
		$this->cost = $row['cost'];
		$this->itemID = $row['id'];
        $this->mealType = $row['mealType'];
        $this->servings = $row['servings'];
        $this->ingredients = $row['ingredients'];
    }

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}
}
?>
