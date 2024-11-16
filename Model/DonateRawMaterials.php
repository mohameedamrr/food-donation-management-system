<?php
require_once 'NonBillableDonate.php';
require_once 'DatabaseManager.php';
class DonateRawMaterials extends NonBillableDonate {
    private $materialType;
    private $quantity;
    private $supplier;

    public function __construct(int $DonationItemID) {
        parent::__construct($DonationItemID);
    }


    public function createRawMaterialItems($itemName, $weight, $expiryDate, $itemImage, $materialType, $quantity, $supplier): bool {
        $isSuccess = $this->createDonationItems($itemName, $weight, 1, 0, 0, 0, 0, 0,0); // Set rawmaterials flag to 1
        
        if (!$isSuccess) {
            return false;
        }

        $sql = "INSERT INTO raw_materials_donation (itemID, expiryDate, itemImage, materialType, quantity, supplier, isDeleted) VALUES
            ('$this->itemID', '$expiryDate', '$itemImage', '$materialType', $quantity, '$supplier', 0)";

        $conn = DatabaseManager::getInstance();
        $isSuccess = $conn->run_select_query($sql);

        return $isSuccess;
    }


    public static function deleteObject($id){
        $sql = "UPDATE raw_materials_donation 
        SET isDeleted = 1 
        WHERE itemID = '$id'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);

	}
    
    public function getRawMaterialItemsInstance(): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM raw_materials_donation WHERE itemID = $this->itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->expiryDate = $row['expiryDate'];
		//$this->cost = $row['cost'];
		$this->itemID = $row['itemID'];
        $this->materialType = $row['materialType'];
        $this->quantity = $row['quantity'];
        $this->supplier = $row['supplier'];
        parent::getDonationItemInstance($this->itemID);
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}
    public function getItemDetails() {
        //$expiryDateStr = $this->expiryDate ? $this->expiryDate->format('Y-m-d') : 'N/A';
        return "ID: {$this->itemID}, Name: {$this->itemName}, Weight: {$this->weight}kg, Cost: {$this->cost}, Material Type: {$this->materialType}, Quantity: {$this->quantity}, Supplier: {$this->supplier}";
    }

    public function setmaterialType($value) :void{
		$this->materialType = $value;
		$sql = "UPDATE raw_materials_donation 
        SET materialType = '$value';
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
}
    public function setquantity($value) :void{
		$this->quantity = $value;
		$sql = "UPDATE raw_materials_donation 
        SET quantity = '$value';
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
}
    public function setsupplier($value) :void{
		$this->supplier = $value;
		$sql = "UPDATE raw_materials_donation 
        SET supplier = '$value';
        WHERE itemID = '$this->itemID'";

        $conn = DatabaseManager::getInstance();
        $conn->run_select_query($sql);
}


}
?>
