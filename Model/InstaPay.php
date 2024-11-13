<?php
require_once 'IPayment.php';

class InstaPay implements IPayment {
    private $transactionID;
    public static $transactionIDCounter =0;
    public function pay(float $cost): bool {
        // Process InstaPay payment
        if ($this->processTransaction($cost)) {
            echo "Payment of $$cost received via InstaPay.\n";
            echo "Transaction ID: " . $this->transactionID . "\n";
            return true;
        } else {
            echo "InstaPay payment failed.\n";
            return false;
        }
    }

    private function processTransaction(float $cost): bool {
        // Simulate transaction processing
        $this->transactionIDCounter++;
        $this-> transactionID = $this->transactionIDCounter;
        // Simulate a success rate of 90%
        $success = rand(1, 100) <= 90;
        return $success;
    }
}
?>
