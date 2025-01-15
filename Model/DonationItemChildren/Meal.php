<?php
// require_once 'DatabaseManager.php';
require_once 'DonationItem.php';
class Meal extends DonationItem implements IStoreObject,IReadObject,IDeleteObject,IUpdateObject{

    private $mealType;

    private $mealQuantity;

    private $expiration;

    private $ingredients;

    public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            $this->mealType = $row['mealType'];
            $this->ingredients = $row['ingredients'];
            $this->expiration = $row['expiration'];
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost']);
        }
    }

    public function getMealType() {
        return $this->mealType;
    }

    public function setMealType($value) {
        $adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET 'mealType' = '$value' WHERE 'item_id' = '$this->itemID'"); // Should succeed
        $this->mealType = $value;

    }

    public function getMealQuantity() {
        return $this->mealQuantity;
    }

    public function setMealQuantity($mealQuantity) {

        $this->mealQuantity = $mealQuantity;
    }

    public function getExpiration() {
        return $this->expiration;
    }

    public function setExpiration($expiration) {
        $adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET 'expiration' = '$expiration' WHERE item_id = '$this->itemID'");
        $this->expiration = $expiration;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function setIngredients($ingredients) {
        $adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET 'ingredients' = '$ingredients' WHERE item_id = '$this->itemID'");
        $this->ingredients = $ingredients;

    }
    public function validate(): bool{
        $date = new DateTime('today');
        $expirationDate = new DateTime($this->expiration);
        if($expirationDate < $date || $this->mealQuantity < 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function readObject($item_id) {
        return new Meal($item_id);
    }

    public static function storeObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'mealType',
            'ingredients'
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        if (empty($filteredData)) {
            return null;
        }

        $columns = implode(", ", array_map(fn($col) => "`$col`", array_keys($filteredData)));
        $placeholders = implode(", ", array_map(
            fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'",
            array_values($filteredData)
        ));

        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("INSERT INTO food_donation.donation_items ($columns) VALUES ($placeholders)");
        $lastInsertedId = $adminProxy->getLastInsertId();
        return new Meal($lastInsertedId);
    }

    public function updateObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'mealType',
            'ingredients'
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        if (empty($filteredData)) {
            return;
        }

        $updates = [];
        foreach ($filteredData as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }

        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("UPDATE donation_items SET " . implode(", ", $updates) . " WHERE item_id = $this->itemID");
    }

    public static function deleteObject($id) {
        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("DELETE FROM donation_items WHERE item_id = $id");
    }
}

// $data = [
//     'item_name'   => 'My Test Meal',
//     'currency'    => 'USD',
//     'cost'        => 5,
//     'mealType'    => 'Vegan',
//     'ingredients' => 'Tomatoes, Lettuce, Cucumber',
// ];
// $newMeal = Meal::storeObject($data);

// echo "New Meal created with ID: " . $newMeal->getItemID() . "\n";
// echo "Meal Type: " . $newMeal->getMealType() . "\n";
// echo "Ingredients: " . $newMeal->getIngredients() . "\n\n";

// // 2. Read (retrieve) the Meal object from the DB, using the generated ID
// //    This confirms the object can be read back from the database properly
// $retrievedMeal = Meal::readObject($newMeal->getItemID());

// echo "Retrieved Meal with ID: " . $retrievedMeal->getItemID() . "\n";
// echo "Meal Type: " . $retrievedMeal->getMealType() . "\n";
// echo "Ingredients: " . $retrievedMeal->getIngredients() . "\n\n";

// // 3. Update the Meal object
// $retrievedMeal->updateObject([
//     'mealType'    => 'Vegetarian',
//     'ingredients' => 'Tomatoes, Lettuce, Cucumber, Carrots'
// ]);

// // 4. Confirm that the update took effect by reading the Meal again
// $updatedMeal = Meal::readObject($newMeal->getItemID());
// echo "Updated Meal with ID: " . $updatedMeal->getItemID() . "\n";
// echo "New Meal Type: " . $updatedMeal->getMealType() . "\n";
// echo "New Ingredients: " . $updatedMeal->getIngredients() . "\n\n";

// Meal::deleteObject($newMeal->getItemID());
// echo "Meal with ID {$newMeal->getItemID()} deleted from the database.\n";
?>

