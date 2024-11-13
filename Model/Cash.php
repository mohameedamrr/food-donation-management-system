<?php
require_once 'IPayment.php';

class Cash implements IPayment {
    private $receiptNumber;

    public function pay(float $cost): bool {
        // Process cash payment
    }

    public function generateReceipt(): string {
        // Generate and return a receipt number
    }
}
?>
