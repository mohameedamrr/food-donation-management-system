<?php
// require_once 'NonBillableDonate.php';
// require_once 'DatabaseManager.php';
// require_once 'DonationItem.php';
class ClientReadyMeal extends DonationItem implements IStoreObject,IReadObject,IDeleteObject,IUpdateObject{

	private $readyMealType;

	private $readyMealExpiration;

	private $packagingType;

	private $readyMealQuantity;

	public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost']);
        }
    }

	public function getReadyMealType() {
		return $this->readyMealType;
	}

	public function setReadyMealType($readyMealType) {
		$this->readyMealType = $readyMealType;
	}

	public function getReadyMealExpiration() {
		return $this->readyMealExpiration;
	}

	public function setReadyMealExpiration($readyMealExpiration) {
		$this->readyMealExpiration = $readyMealExpiration;
	}

	public function getPackagingType() {
		return $this->packagingType;
	}

	public function setPackagingType($packagingType) {
		$this->packagingType = $packagingType;

	}

	public function getReadyMealQuantity() {
		return $this->readyMealQuantity;
	}

	public function setReadyMealQuantity($readyMealQuantity) {
		$this->readyMealQuantity = $readyMealQuantity;

	}
    public function validate(): bool{
        if($this->readyMealQuantity < 0){
            return false;
        }
        else{
            return true;
        }
    }
    public static function readObject($item_id) {
        return new ClientReadyMeal($item_id);
    }

    public static function storeObject(array $data) {
		$allowedFields = [
            'item_name',
            'currency',
            'cost'
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
        return new ClientReadyMeal($lastInsertedId);
    }

    public function updateObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost'
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
//     'item_name' => 'Prepared Meal Box',
//     'currency'  => 'USD',
//     'cost'      => 15,
//     // Intentionally including fields not in $allowedFields:
//     'readyMealType'     => 'Vegetarian',
//     'expiration'        => '2025-12-31',
//     'packagingType'     => 'Biodegradable',
//     'readyMealQuantity' => 20
//     // These won't be stored in the DB, but we can still set them on the object in-memory
// ];

// $newMeal = ClientReadyMeal::storeObject($data);

// if ($newMeal === null) {
//     echo "No valid fields found to store in the database.\n";
//     exit;
// }

// echo "New ClientReadyMeal created with ID: " . $newMeal->getItemID() . "\n";
// echo "Item Name (DB): " . $newMeal->getItemName() . "\n";
// echo "Currency (DB): " . $newMeal->getCurrency() . "\n";
// echo "Cost (DB): " . $newMeal->getCost() . "\n\n";

// // 2. Read (retrieve) the ClientReadyMeal object from the database
// $retrievedMeal = ClientReadyMeal::readObject($newMeal->getItemID());

// echo "Retrieved ClientReadyMeal with ID: " . $retrievedMeal->getItemID() . "\n";
// echo "Item Name (DB): " . $retrievedMeal->getItemName() . "\n";
// echo "Currency (DB): " . $retrievedMeal->getCurrency() . "\n";
// echo "Cost (DB): " . $retrievedMeal->getCost() . "\n\n";

// // 2a. Demonstrate setting in-memory fields (not stored by default in DB)
// $retrievedMeal->setReadyMealType('Vegan');
// $retrievedMeal->setExpiration('2026-01-31');
// $retrievedMeal->setPackagingType('Plastic-Free');
// $retrievedMeal->setReadyMealQuantity(10);

// echo "Ready Meal Type (in-memory): " . $retrievedMeal->getReadyMealType() . "\n";
// echo "Expiration (in-memory): " . $retrievedMeal->getExpiration() . "\n";
// echo "Packaging Type (in-memory): " . $retrievedMeal->getPackagingType() . "\n";
// echo "Ready Meal Quantity (in-memory): " . $retrievedMeal->getReadyMealQuantity() . "\n\n";

// // 3. Update the ClientReadyMeal object in the DB
// //    Only item_name, currency, and cost are actually updated in the DB.
// $retrievedMeal->updateObject([
//     'item_name' => 'Updated Meal Box',
//     'cost'      => 20
//     // 'readyMealType' => 'Gluten-Free' // This won't be updated in DB unless you add it to $allowedFields.
// ]);

// // 4. Read again to confirm updated fields
// $updatedMeal = ClientReadyMeal::readObject($newMeal->getItemID());
// echo "Updated ClientReadyMeal with ID: " . $updatedMeal->getItemID() . "\n";
// echo "New Item Name (DB): " . $updatedMeal->getItemName() . "\n";
// echo "New Cost (DB): " . $updatedMeal->getCost() . "\n\n";

// // 5. (Optional) Delete the record from DB
// // Uncomment if you want to remove it after testing
// ClientReadyMeal::deleteObject($updatedMeal->getItemID());
// echo "ClientReadyMeal with ID {$updatedMeal->getItemID()} deleted from DB.\n";
?>
