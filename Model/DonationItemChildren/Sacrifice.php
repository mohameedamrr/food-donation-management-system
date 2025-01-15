<?php
require_once 'DonationItem.php';
class Sacrifice extends DonationItem implements IStoreObject,IReadObject,IDeleteObject,IUpdateObject{

    private $animalType;

    private $weight;

    public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            $this->animalType = $row['animal_type'];
            $this->weight = $row['weight'];
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost']);
        }
    }

    public function getAnimalType() {
        return $this->animalType;
    }

    public function setAnimalType($animalType) {
        $adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET 'animal_type' = '$animalType' WHERE item_id = '$this->itemID'"); // Should succeed
        $this->animalType = $animalType;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $adminProxy = new DatabaseManagerProxy('admin');
		$adminProxy->runQuery("UPDATE donation_items SET 'weight' = '$weight' WHERE item_id = '$this->itemID'"); // Should succeed
        $this->weight = $weight;
    }

    public function calculateCost(): float {
        if($this->animalType == 'cow'){
            return $this->weight * 1000;
        }
        else if($this->animalType == 'goat'){
            return $this->weight * 500;
        }
        else if($this->animalType == 'sheep'){
            return $this->weight * 300;
        }
        else{
            return 0;
        }
    }
    public function validate(): bool{
        if($this->weight < 0 || $this->weight > 10000){
            return false;
        }
        else{
            return true;
        }
    }
    public static function readObject($item_id) {
        return new Sacrifice($item_id);
    }

    public static function storeObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'animal_type',
            'weight'
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
        return new Sacrifice($lastInsertedId);
    }

    public function updateObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'animal_type',
            'weight'
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
//     'item_name'   => 'Festival Sacrifice',
//     'currency'    => 'USD',
//     'cost'        => 200,      // Could be the base cost or custom value
//     'animal_type' => 'sheep',
//     'weight'      => 3         // e.g., 3 units of weight
// ];

// // storeObject() will filter out anything not in allowedFields
// $newSacrifice = Sacrifice::storeObject($data);

// if ($newSacrifice === null) {
//     echo "No valid fields were provided for storing a Sacrifice.\n";
//     exit;
// }

// echo "New Sacrifice created with ID: " . $newSacrifice->getItemID() . "\n";
// echo "Item Name (DB): " . $newSacrifice->getItemName() . "\n";
// echo "Currency (DB): " . $newSacrifice->getCurrency() . "\n";
// echo "Cost (DB): " . $newSacrifice->getCost() . "\n";
// echo "Animal Type (DB): " . $newSacrifice->getAnimalType() . "\n";
// echo "Weight (DB): " . $newSacrifice->getWeight() . "\n\n";

// // 2. Read (retrieve) the Sacrifice object from the DB
// $retrievedSacrifice = Sacrifice::readObject($newSacrifice->getItemID());

// echo "Retrieved Sacrifice with ID: " . $retrievedSacrifice->getItemID() . "\n";
// echo "Item Name (DB): " . $retrievedSacrifice->getItemName() . "\n";
// echo "Currency (DB): " . $retrievedSacrifice->getCurrency() . "\n";
// echo "Cost (DB): " . $retrievedSacrifice->getCost() . "\n";
// echo "Animal Type (DB): " . $retrievedSacrifice->getAnimalType() . "\n";
// echo "Weight (DB): " . $retrievedSacrifice->getWeight() . "\n";

// // 2a. Demonstrate the in-memory calculation of cost based on type & weight
// echo "Calculated Cost (in-memory, ignoring DB 'cost'): "
//      . $retrievedSacrifice->calculateCost() . "\n\n";

// // 3. Update the Sacrifice object (allowed: item_name, currency, cost, animal_type, weight)
// $retrievedSacrifice->updateObject([
//     'animal_type' => 'goat',
//     'weight'      => 2,
//     'cost'        => 150
//     // If you pass in keys not in allowedFields, they'll be discarded
// ]);

// // 4. Read again to confirm updated fields
// $updatedSacrifice = Sacrifice::readObject($newSacrifice->getItemID());
// echo "Updated Sacrifice with ID: " . $updatedSacrifice->getItemID() . "\n";
// echo "New Animal Type (DB): " . $updatedSacrifice->getAnimalType() . "\n";
// echo "New Weight (DB): " . $updatedSacrifice->getWeight() . "\n";
// echo "New Cost (DB): " . $updatedSacrifice->getCost() . "\n";
// echo "Calculated Cost (in-memory): " . $updatedSacrifice->calculateCost() . "\n\n";

// // 5. (Optional) Delete the Sacrifice object from the DB
// // Uncomment if you wish to remove it after testing
// Sacrifice::deleteObject($updatedSacrifice->getItemID());
// echo "Sacrifice with ID {$updatedSacrifice->getItemID()} deleted from DB.\n";
?>
