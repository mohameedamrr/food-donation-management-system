<?php
require_once ("DatabaseManager.php");
abstract class DonationItem implements IReadObject, IStoreObject, IDeleteObject, IUpdateObject{

    private $itemID;
    private $itemName;
	private $currency;
    private $expiryDate;
    private $cost;

	public static function storeObject(array $data) {
		$columns = implode(", ", array_map(fn($key) => "$key", array_keys($data)));
		$placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
		$sql = "INSERT INTO food_donation.users ($columns) VALUES ($placeholders)";
		$db = DatabaseManager::getInstance();
		$db->runQuery($sql);
		$lastInsertedId = $db->getLastInsertId();
		return new BasicDonator($lastInsertedId, null);
	}

	public function createDonationItems($itemName, $weight, $rawmaterials = 0, $readymeals = 0, $meals = 0, $money = 0, $sacrifice = 0, $box = 0): bool {

		$sql = "INSERT INTO donation_items (itemName, itemWeight, israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox, isDeleted) VALUES
			('$itemName', '$weight', '$rawmaterials', '$readymeals', '$meals','$money', '$sacrifice', '$box', 0)";
		$conn = DatabaseManager::getInstance();
		if($conn === NULL){
			echo "no connection";
		}
		$isSuccess = $conn->runQuery($sql);
		$this->itemID = $conn->getLastInsertId();
		return $isSuccess;
	}

	public static function deleteObject($id){
        $sql = "UPDATE donation_items
        SET isDeleted = 1 
        WHERE itemID = '$id'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}
	public function getDonationItemInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM donation_items WHERE itemID = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->itemName = $row['itemName'];
		$this->weight = $row['itemWeight'];
		//$this->expiryDate = $row['expiryDate'];
		//$this->cost = $row['cost'];
		$this->itemID = $row['itemID'];
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}

	public function getFlags() {
		$sql = "SELECT israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox, isDeleted
				FROM donation_items
				WHERE itemID = '$this->itemID'";
	
		$conn = DatabaseManager::getInstance();
		$result = $conn->run_select_query($sql);
	
		if ($result->num_rows > 0) {
			// Fetch the result as an associative array
			return $result->fetch_assoc();
		} else {
			// Return null or an appropriate response if no flags are found
			return null;
		}
	}

    public function getItemDetails() {
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}";
    }

    public function setItemID($value) {
		$this->itemID = $value;
	}

	public function setItemName($value) {
		$this->itemName = $value;
        $sql = "UPDATE donation_items 
        SET itemName = '$value'
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
	}


	public function setCost($value) {
		$this->cost = $value;

	}

	public function getItemID() {
		return $this->itemID;
	}

	public function getItemName() {
		return $this->itemName;
	}

	public function getWeight() {
		return $this->weight;
	}


	public function getCost() {
		return $this->cost;
	}
}
?>


