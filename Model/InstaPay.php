<?php
// classes/InstaPay.php
require_once __DIR__ . '/../interfaces/IPayment.php';

class InstaPay implements IPayment {
    private $transactionID; // Transaction ID for InstaPay

    public function pay($cost) {
        // Implement InstaPay payment processing
        // For simplicity, we'll assume the payment is always successful

        // Process the transaction
        if ($this->processTransaction($cost)) {
            return true;
        } else {
            return false;
        }
    }

    public function processTransaction($cost) {
        // Simulate processing the transaction
        // Generate a transaction ID
        $this->transactionID = uniqid('instapay_');
        // Assume transaction is successful
        return true;
    }
}
?>
