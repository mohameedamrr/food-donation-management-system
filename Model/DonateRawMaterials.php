<?php
require_once 'NonBillableDonate.php';

class DonateRawMaterials extends NonBillableDonate {
    private $materialType;
    private $quantity;
    private $supplier;

    public function __construct(
    ) {}


    public function createRawMaterialItems($itemName, $weight, $expiryDate, $itemImage, $materialType, $quantity, $supplier): bool {
        $isSuccess = $this->createDonationItems($itemName, $weight, 1, 0, 0, 0, 0, 0); // Set rawmaterials flag to 1
        
        if (!$isSuccess) {
            return false;
        }

        $sql = "INSERT INTO raw_materials_donation (itemID, expiryDate, itemImage, materialType, quantity, supplier) VALUES
            ($this->itemID, '$expiryDate', '$itemImage', '$materialType', $quantity, '$supplier')";

        $conn = DatabaseManager::getInstance();
        $isSuccess = $conn->run_select_query($sql);

        return $isSuccess;
    }

    
    public function getRawMaterialItemsInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM raw_materials_donation WHERE id = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->expiryDate = $row['expiryDate'];
		$this->cost = $row['cost'];
		$this->itemID = $row['id'];
        $this->materialType = $row['materialType'];
        $this->quantity = $row['quantity'];
        $this->supplier = $row['supplier'];
        parent::getDonationItemInstance($itemID);
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
	}

}
?>
