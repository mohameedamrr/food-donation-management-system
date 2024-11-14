<?php
require_once 'NonBillableDonate.php';

class DonateMeal extends NonBillableDonate {
    private $mealType;
    private $servings;
    private $ingredients; // Array of ingredient names

    public function __construct(

    ) {

    }


    public function createMealItems($itemName, $weight, $expiryDate, $itemImage, $mealType, $servings, $ingredients): bool {
        $isSuccess = $this->createDonationItems($itemName, $weight, 0, 0, 1, 0, 0, 0); // Set meals flag to 1
        
        if (!$isSuccess) {
            return false;
        }

        $sql = "INSERT INTO meals_donation (itemID, expiryDate, itemImage, mealType, servings, ingredients) VALUES
            ($this->itemID, '$expiryDate', '$itemImage', '$mealType', $servings, '$ingredients')";

        $conn = DatabaseManager::getInstance();
        $isSuccess = $conn->run_select_query($sql);

        return $isSuccess;
    }
    
    public function getMealItemInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM meals_donation WHERE id = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		parent::getDonationItemInstance($itemID);
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
