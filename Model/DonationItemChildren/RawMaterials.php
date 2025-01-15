<?php
// require_once 'DatabaseManager.php';
require_once 'DonationItem.php';
class RawMaterials extends DonationItem implements IStoreObject,IReadObject,IDeleteObject,IUpdateObject{

    private $materialType;

    private $quantity;

    private $rawMaterialWeight;

    private $supplier;

    public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost']);
        }
    }

    public function getMaterialType() {
        return $this->materialType;
    }

    public function setMaterialType($materialType) {
        $this->materialType = $materialType;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getRawMaterialWeight() {
        return $this->rawMaterialWeight;
    }

    public function setRawMaterialWeight($rawMaterialWeight) {
        $this->rawMaterialWeight = $rawMaterialWeight;

    }

    public function getSupplier() {
        return $this->supplier;
    }

    public function setSupplier($supplier) {
        $this->supplier = $supplier;
    }

    public static function readObject($item_id) {
        return new RawMaterials($item_id);
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
        return new RawMaterials($lastInsertedId);
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
//     'item_name'         => 'Metal Sheets',
//     'currency'          => 'USD',
//     'cost'              => 100,
//     'materialType'      => 'Steel',
//     'quantity'          => 50,
//     'weight' => 200,
//     'supplier'          => 'Acme Supply Co.',
// ];
// $newMaterial = RawMaterials::storeObject($data);

// echo "New RawMaterials object created with ID: " . $newMaterial->getItemID() . "\n";
// echo "Material Type: " . $newMaterial->getMaterialType() . "\n";
// echo "Quantity: " . $newMaterial->getQuantity() . "\n";
// echo "Supplier: " . $newMaterial->getSupplier() . "\n\n";

// // 2. Read (retrieve) the RawMaterials object from the DB
// $retrievedMaterial = RawMaterials::readObject($newMaterial->getItemID());

// echo "Retrieved RawMaterials object with ID: " . $retrievedMaterial->getItemID() . "\n";
// echo "Material Type: " . $retrievedMaterial->getMaterialType() . "\n";
// echo "Quantity: " . $retrievedMaterial->getQuantity() . "\n";
// echo "Supplier: " . $retrievedMaterial->getSupplier() . "\n\n";

// // 3. Update some properties of the RawMaterials object
// $retrievedMaterial->updateObject([
//     'materialType'      => 'Aluminum',
//     'rawMaterialWeight' => 180,
//     'supplier'          => 'Better Supply Inc.'
// ]);

// // 4. Confirm the updated fields
// $updatedMaterial = RawMaterials::readObject($newMaterial->getItemID());
// echo "Updated RawMaterials with ID: " . $updatedMaterial->getItemID() . "\n";
// echo "New Material Type: " . $updatedMaterial->getMaterialType() . "\n";
// echo "New Weight: " . $updatedMaterial->getRawMaterialWeight() . "\n";
// echo "New Supplier: " . $updatedMaterial->getSupplier() . "\n\n";

// RawMaterials::deleteObject($newMaterial->getItemID());
// echo "RawMaterials with ID {$newMaterial->getItemID()} deleted.\n";
?>
