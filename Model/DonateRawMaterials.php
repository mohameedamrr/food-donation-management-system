<?php
require_once 'NonBillableDonate.php';

class DonateRawMaterials extends NonBillableDonate {
    private $materialType;
    private $quantity;
    private $supplier;

    public function __construct(
        int $itemID,
        string $itemName,
        float $weight,
        DateTime $expiryDate,
        $itemImage,
        string $materialType,
        int $quantity,
        string $supplier
    ) {
        parent::__construct($itemID, $itemName, $weight, $expiryDate, $itemImage);
        $this->materialType = $materialType;
        $this->quantity = $quantity;
        $this->supplier = $supplier;
    }

}
?>
