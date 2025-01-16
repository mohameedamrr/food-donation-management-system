<?php
// require_once 'DonationItem.php';
class Money extends DonationItem implements IStoreObject,IReadObject,IDeleteObject,IUpdateObject{

    private $amount;

    private $donationPurpose;

    public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost']);
        }
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;

    }

    public function getDonationPurpose() {
        return $this->donationPurpose;
    }

    public function setDonationPurpose($donationPurpose) {
        $this->donationPurpose = $donationPurpose;

    }
    public function validate(): bool{
        if($this->amount < 0){
            return false;
        }
        else{
            return true;
        }
    }
    public static function readObject($item_id) {
        return new Money($item_id);
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
        return new Money($lastInsertedId);
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
//     'item_name' => 'Charity Donation',
//     'currency'  => 'USD',
//     'cost'      => 250,  // Representing the total monetary donation cost
//     // Intentionally including fields not in $allowedFields:
//     'amount'          => 100,
//     'donationPurpose' => 'Medical Supplies'
// ];
// // Since 'amount' and 'donationPurpose' are NOT in $allowedFields,
// // they won't appear in the final INSERT query.

// $newMoney = Money::storeObject($data);

// if ($newMoney === null) {
//     echo "No valid data to store in the database.\n";
//     exit();
// }

// echo "New Money object created with ID: " . $newMoney->getItemID() . "\n";
// echo "Item Name: " . $newMoney->getItemName() . "\n";
// echo "Currency: " . $newMoney->getCurrency() . "\n";
// echo "Cost: " . $newMoney->getCost() . "\n\n";

// // 2. Read (Retrieve) the Money object from the DB
// $retrievedMoney = Money::readObject($newMoney->getItemID());

// echo "Retrieved Money object with ID: " . $retrievedMoney->getItemID() . "\n";
// echo "Item Name: " . $retrievedMoney->getItemName() . "\n";
// echo "Currency: " . $retrievedMoney->getCurrency() . "\n";
// echo "Cost: " . $retrievedMoney->getCost() . "\n";

// // 2a. Demonstrate that amount and donationPurpose (in code)
// //     are not fetched from DB by default. We can still set them manually:
// $retrievedMoney->setAmount(100);
// $retrievedMoney->setDonationPurpose('Medical Supplies');

// echo "Amount (in-memory): " . $retrievedMoney->getAmount() . "\n";
// echo "Donation Purpose (in-memory): " . $retrievedMoney->getDonationPurpose() . "\n\n";

// // 3. Update the Money object (only allowed fields will be updated in DB)
// $retrievedMoney->updateObject([
//     'item_name' => 'Water Relief Fund',
//     'cost'      => 300
//     // 'amount'          => 200,
//     // 'donationPurpose' => 'Emergency Relief'
//     // These won't get stored because they're not in $allowedFields.
// ]);

// // 4. Confirm the updated fields by reading again
// $updatedMoney = Money::readObject($newMoney->getItemID());
// echo "Updated Money object with ID: " . $updatedMoney->getItemID() . "\n";
// echo "New Item Name: " . $updatedMoney->getItemName() . "\n";
// echo "New Cost: " . $updatedMoney->getCost() . "\n\n";

// // 5. (Optional) Delete the Money object
// // Uncomment if you wish to remove it from the database
// Money::deleteObject($newMoney->getItemID());
// echo "Money object with ID {$newMoney->getItemID()} deleted from database.\n";
?>
