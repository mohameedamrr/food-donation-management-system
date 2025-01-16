<?php
require_once '../interfaces/IPayment.php';
require_once 'StateDP/PaymentState.php';
require_once 'StateDP/InitialState.php';
require_once 'PaymentStrategyDP/Cash.php';
require_once 'PaymentStrategyDP/Card.php';
require_once 'PaymentStrategyDP/InstaPay.php';
class Bill {
    private $paymentStrategy; // IPayment
    private $billAmount;      // float
    private $isPaid;  // Boolean
    private $paymentState; // PaymentState

    public function __construct(float $billAmount) {
        $this->paymentStrategy = new Cash();
        $this->billAmount = $billAmount;
        $this->paymentState = new InitialState();
    }

    // Getters and Setters
    public function getPaymentStrategy(): IPayment {
        return $this->paymentStrategy;
    }

    public function setPaymentStrategy(IPayment $strategy): void {
        $this->paymentStrategy = $strategy;
    }

    public function getBillAmount(): float {
        return $this->billAmount;
    }

    public function setBillAmount(float $amount): void {
        $this->billAmount = $amount;
    }

    public function getIsPaid(): bool {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): void {
        $this->isPaid = $isPaid;
    }

    public function getPaymentState(): PaymentState {
        return $this->paymentState;
    }

    public function setNextState(PaymentState $state): void {
        $this->paymentState = $state;
    }

    public function proceedPayment(): void {
        $this->paymentState->nextPaymentState($this);
    }
}

// $bill = new Bill(90);
// echo $bill->getPaymentState();
// // $date = new DateTime();
// // $bill->setPaymentStrategy(new Card("ADASDASD", "132123", $date->modify('+1 day')));
// // $bill->setPaymentStrategy(new InstaPay("123123123"));
// $bill->proceeedPayment();
// echo $bill->getPaymentState();
// $bill->proceeedPayment();
// echo $bill->getPaymentState();
// $bill->proceeedPayment();
// echo $bill->getIsPaid() ? 'true' : 'false';


?>
