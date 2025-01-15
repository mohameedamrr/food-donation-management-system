<?php
//require_once '../interfaces/IDatabaseManager.php';
//require_once 'DatabaseManager.php';
require_once 'ProxyDP/DatabaseManagerProxy.php';
//require_once "../interfaces/IStoreObject.php";
//require_once "../interfaces/IReadObject.php";
//require_once "../interfaces/IUpdateObject.php";
//require_once "../interfaces/IDeleteObject.php";
require_once 'DonationItemChildren/Meal.php';
require_once 'DonationItemChildren/Money.php';
require_once 'DonationItemChildren/RawMaterials.php';
require_once 'DonationItemChildren/Sacrifice.php';
require_once 'DonationItemChildren/ClientReadyMeal.php';
require_once 'DecoratorDP/BasicBox.php';

class DonationDetails implements IStoreObject,IReadObject,IDeleteObject {

    private $id;

    private $totalCost;

    private $description;

    private $donationID;

    private $donationItems;

    public function __construct($id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donation_history WHERE id = $id")->fetch_assoc();
        if(isset($row)) {
            $this->id = $row['id'];
            $this->totalCost = $row['total_cost'];
            $this->description = $row['description'];
            $this->donationID = $row['donation_id'];
            $this->donationItems = [];
            if($row['meal_id'] != NULL) {
                $meal = new Meal($row['meal_id']);
                $meal->setCost($row['meal_cost']);
                $meal->setMealQuantity($row['meal_quantity']);
                $this->donationItems[] = $meal;
            }
            if($row['raw_materials_id'] != NULL) {
                $rawMaterials = new RawMaterials($row['raw_materials_id']);
                $rawMaterials->setCost($row['raw_materials_cost']);
                $rawMaterials->setMaterialType($row['material_type']);
                $rawMaterials->setQuantity($row['material_quantity']);
                $rawMaterials->setRawMaterialWeight($row['material_weight']);
                $rawMaterials->setSupplier($row['material_supplier']);
                $this->donationItems[] = $rawMaterials;
            }
            if($row['client_ready_meal_id'] != NULL) {
                $clientReadyMeal = new ClientReadyMeal($row['client_ready_meal_id']);
                $clientReadyMeal->setCost($row['client_ready_meal_cost']);
                $clientReadyMeal->setReadyMealType($row['ready_meal_type']);
                $clientReadyMeal->setReadyMealExpiration($row['ready_meal_expiration']);
                $clientReadyMeal->setReadyMealQuantity($row['ready_meal_quantity']);
                $clientReadyMeal->setPackagingType($row['ready_meal_packaging_type']);
                $this->donationItems[] = $clientReadyMeal;
            }
            if($row['money_id'] != NULL) {
                $money = new Money($row['money_id']);
                $money->setCost($row['money_amount']);
                $money->setDonationPurpose($row['money_donation_purpose']);
                $this->donationItems[] = $money;
            }
            if($row['sacrifice_id'] != NULL) {
                $sacrifice = new Sacrifice($row['sacrifice_id']);
                $sacrifice->setCost($row['sacrifice_cost']);
                $this->donationItems[] = $sacrifice;
            }
            if($row['box_id'] != NULL) {
                $box = new BasicBox($row['box_id']);
                $box->setCost($row['box_cost']);
                $box->setTotalCost($row['box_cost']);
                $box->setInitialBoxSize($row['final_box_size']);
                $box->setFinalBoxSize($row['final_box_size']);
                $box->setInitialItemList(explode(", ", $row['final_item_list']));
                $box->setFinalItemList(explode(", ", $row['final_item_list']));
                $this->donationItems[] = $box;

            }

        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTotalCost() {
        return $this->totalCost;
    }

    public function setTotalCost($totalCost) {
        $this->totalCost = $totalCost;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;

    }

    public function getDonationID() {
        return $this->donationID;
    }

    public function setDonationID($donationID) {
        $this->donationID = $donationID;
    }

    public function getDonationItems() {
        return $this->donationItems;
    }

    public function setDonationItems($donationItems) {
        $this->donationItems = $donationItems;
    }

    public static function readObject($id) {
        return new DonationDetails($id);
    }

    public static function storeObject(array $data) {
        $columns = implode(", ", array_map(fn($key) => "$key", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("INSERT INTO food_donation.donation_history ($columns) VALUES ($placeholders)");
        $lastInsertedId = $adminProxy->getLastInsertId();
        return new DonationDetails($lastInsertedId);
    }

    public static function deleteObject($id) {
        $adminProxy = new DatabaseManagerProxy('admin');
        $adminProxy->runQuery("DELETE FROM donation_history WHERE id = $id");
    }
}
?>