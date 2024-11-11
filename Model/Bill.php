<?php
require_once __DIR__ . '/../interfaces/IPayment.php';

class Bill {
    private $paymentStrategy;
    private $billAmount;

    public function __construct($billAmount) {
        $this->billAmount = $billAmount;
        $this->paymentStrategy = null;
    }

    public function setPaymentStrategy(IPayment $strategy) {
        $this->paymentStrategy = $strategy;
    }

    public function executePayment(BillableDonate $billableDonate) {
        if ($this->paymentStrategy == null) {
            throw new Exception("Payment strategy not set.");
        }
        $cost = $billableDonate->calculateCost();
        $this->billAmount = $cost;
        return $this->paymentStrategy->pay($cost);
    }
}
?>
