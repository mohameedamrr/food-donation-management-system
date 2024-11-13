<?php
require_once 'NonBillableDonate.php';

class DonateRawMaterials extends NonBillableDonate {
    private $materialType;
    private $quantity;
    private $supplier;

    public function __construct(
    ) {}


    public function createDonationItems( $itemName, $weight, $expiryDate, $itemImage, $materialType,$quantity, $supplier): bool {
        $sql = "INSERT INTO `raw_materials_donation` (itemName, itemWeight, expiryDate, itemImage, materialType, quantity, supplier) VALUES
        ($itemName,$weight,$expiryDate,$itemImage,$materialType,$quantity,$supplier)";
        
        $conn = DatabaseManager::getInstance();	

		$isSuccess =$conn->run_select_query($sql);
        
        return $isSuccess;


    }
    public function getDonationItemInstance($itemID): DonationItem {
		$conn = DatabaseManager::getInstance();
		$sql ="SELECT * FROM raw_materials_donation WHERE id = $itemID";
		
		$row = $conn->run_select_query($sql);
		if($row->num_rows > 0) {
		$row = $row->fetch_assoc();	
		$this->itemName = $row['itemName'];
		$this->weight = $row['itemWeight'];
		$this->expiryDate = $row['expiryDate'];
		$this->cost = $row['cost'];
		$this->itemID = $row['id'];
        $this->materialType = $row['materialType'];
        $this->quantity = $row['quantity'];
        $this->supplier = $row['supplier'];
		}

		// $row = $conn->fetchAssoc($sql);
		return $this; 
    }

}
?>
