<?php
require_once 'BillableDonate.php';

class DonateMoney extends BillableDonate {
    private $amount;
    private $donationPurpose;

    public function donateAmount(): bool {
        // Process the monetary donation
    }

    public function getReceipt(): string {
        // Generate and return a receipt
    }
}
?>
