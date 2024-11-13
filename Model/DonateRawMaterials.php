<?php
require_once 'NonBillableDonate.php';

class DonateRawMaterials extends NonBillableDonate {
    private $materialType;
    private $quantity;
    private $supplier;

    public function checkStock(): bool {
        // Check the stock availability
    }

    public function donateMaterial(): bool {
        // Donate the raw materials
    }
}
?>
