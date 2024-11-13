<?php
require_once 'IPayment.php';
require_once 'BillableDonate.php';

class Bill {
    private $paymentStrategy; // IPayment
    private $billAmount;

    public function setPaymentStrategy(IPayment $strategy): void {
        $this->paymentStrategy = $strategy;
    }

    public function executePayment(BillableDonate $billableDonate): bool {
        // Execute payment using the chosen strategy
    }
}
?>
