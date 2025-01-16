<?php
require_once 'Box.php';
require_once 'BoxDecorator.php';
require_once 'BoxAdditionalRice.php';
require_once 'BoxAdditionalPasta.php';
require_once 'BoxAdditionalOil.php';

class BasicBox extends Box implements IDeleteObject, IReadObject, IStoreObject, IUpdateObject {

    public function __construct($item_id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_items WHERE item_id = $item_id")->fetch_assoc();
        if(isset($row)) {
            echo 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
            parent::__construct($row['item_id'],$row['item_name'],$row['currency'],$row['cost'], $row['initial_box_size'], explode(", ", $row['initial_item_list']));
        }
    }

    public function addItem($item): array {
        $this->finalItemList = $this->initialItemList;
        echo $this->finalItemList;
        $this->finalBoxSize = $this->initialBoxSize;
        return $this->finalItemList;
    }

    public function calculateCost(): float {
        $this->totalCost = $this->cost;
        return $this->totalCost;
    }
 
    public static function readObject($item_id) {
        return new BasicBox($item_id);
    }

    public static function storeObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'initial_box_size',
            'initial_item_list'
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
        return new BasicBox($lastInsertedId);
    }

    public function updateObject(array $data) {
        $allowedFields = [
            'item_name',
            'currency',
            'cost',
            'initial_box_size',
            'initial_item_list'
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
// $basicBox = new BasicBox(7);

// // Test addItem and calculateCost
// $basicBox->addItem('itemB');
// echo "Initial Item List: ";
// print_r($basicBox->getInitialItemList()); // Should be ['itemA']
// echo "Final Item List: ";
// print_r($basicBox->getFinalItemList()); // Should be ['itemA']
// echo "Total Cost: " . $basicBox->calculateCost() . "\n"; // Should be 100
// $oilDecorator = new BoxAdditionalOil($basicBox, 5);

// // Test addItem and calculateCost
// $oilDecorator->addItem('OilBottle');
// echo "Final Item List after Oil: ";
// print_r($oilDecorator->getFinalItemList()); // Should be ['itemA', 'OilBottle']
// echo "Total Cost after Oil: " . $oilDecorator->calculateCost() . "\n"; // Should be 100 + (10*5) = 150


// $pastaDecorator = new BoxAdditionalPasta($oilDecorator, 3);

// $pastaDecorator->addItem('PastaPacket');
// echo "Final Item List after Pasta: ";
// print_r($pastaDecorator->getFinalItemList()); // Should be ['itemA', 'OilBottle', 'PastaPacket']
// echo "Total Cost after Pasta: " . $pastaDecorator->calculateCost() . "\n"; // Should be 150 + (10*3) = 180

// $riceDecorator = new BoxAdditionalRice($pastaDecorator, 2);

// $riceDecorator->addItem('RiceBag');
// echo "Final Item List after Rice: ";
// print_r($riceDecorator->getFinalItemList()); // Should be ['itemA', 'OilBottle', 'PastaPacket', 'RiceBag']
// echo "Total Cost after Rice: " . $riceDecorator->calculateCost() . "\n"; // Should be 180 + (10*2) = 200

?>