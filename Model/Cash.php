<?php
// classes/Cash.php
require_once __DIR__ . '/../interfaces/IPayment.php';

class Cash implements IPayment {
    private $receiptNumber; // Receipt identifier for cash payments

    public function pay($cost) {
        // Implement cash payment processing
        // For simplicity, we'll assume the payment is always successful

        // Generate a receipt number
        $this->receiptNumber = uniqid('receipt_');
        return true;
    }

    public function generateReceipt() {
        // Return the receipt details
        return "Receipt Number: " . $this->receiptNumber;
    }
}
?>
