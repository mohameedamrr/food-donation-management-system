<?php
require_once 'NonBillableDonate.php';
require_once 'DatabaseManager.php';
class DonateMeal extends NonBillableDonate {
    private $mealType;
    private $servings;
    private $ingredients; // Array of ingredient names

    public function __construct($DonationItemID) {
    parent::__construct($DonationItemID);
    }

    public function createMealItems($itemName, $weight, $expiryDate, $itemImage, $mealType, $servings, $ingredients): bool {
        $isSuccess = $this->createDonationItems($itemName, $weight, 0, 0, 1, 0, 0, 0); // Set meals flag to 1
        
        if (!$isSuccess) {
            return false;
        }

        
        $sql = "INSERT INTO meals_donation (itemID, expiryDate, itemImage, mealType, servings, ingredients, isDeleted) VALUES
            ('$this->itemID', '$expiryDate', '$itemImage', '$mealType', '$servings', '$ingredients', 0)";

        $conn = DatabaseManager::getInstance();
        $isSuccess = $conn->run_select_query($sql);

        return $isSuccess;
    }
    public static function deleteObject($id){
        $sql = "UPDATE meals_donation 
        SET isDeleted = 1 
        WHERE itemID = '$id'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}
    public function getMealItemInstance(): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM meals_donation WHERE itemID = $this->itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->expiryDate = $row['expiryDate'];
		//$this->cost = $row['cost'];
		//$this->itemID = $row['itemID'];
        $this->mealType = $row['mealType'];
        $this->servings = $row['servings'];
        $this->ingredients = $row['ingredients'];
        parent::getDonationItemInstance($this->itemID);
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}
    public function getItemDetails() {
        // $expiryDateStr = $this->expiryDate ? $this->expiryDate->format('Y-m-d') : 'N/A';
        // $ingredientsStr = implode(', ', $this->ingredients); // Convert ingredients array to string
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}, Meal Type: {$this->mealType}, Servings: {$this->servings}, Ingredients: {$this->ingredients}";
    }

    public function getMealType() {
		return $this->mealType;
	}

	public function setMealType($value) {
		$this->mealType = $value;
        $sql = "UPDATE meals_donation 
        SET mealType = '$value'
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}

	public function getServings() {
		return $this->servings;
	}

	public function setServings($value) {
		$this->servings = $value;
        $sql = "UPDATE meals_donation 
        SET servings = '$value'
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	public function setIngredients($value) {
		$this->ingredients = $value;
        $sql = "UPDATE meals_donation 
        SET ingredients = '$value'
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}
}
?>

