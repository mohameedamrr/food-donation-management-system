<?php
require_once 'IPayment.php';

class Cash implements IPayment {
    private $receiptNumber;

    public static $receiptNumberCounter = 0;

    public function pay(float $cost): bool {
        // Process cash payment
        $this->generateReceipt();
        //echo "Payment of $$cost received in cash.\n";
        //echo "Receipt Number: " . $this->receiptNumber . "\n";
        return true;
    }

    private function generateReceipt(): string {
        // Generate a random receipt number
        $this->receiptNumberCounter++;
        $this->receiptNumber = $this->receiptNumberCounter %10;
        return $this->receiptNumber;
    }
}
?>
