<?php
require_once '../Bill.php';
require_once 'ProcessingState.php';
 class InitialState extends PaymentState{

    public function nextPaymentState(Bill $bill): void {
        $bill->setIsPaid(false);
        $bill->setNextState(new ProcessingState());
        $bill->proceedPayment();
    }
    public function __toString(): string {
        return "Initial";
    }
 }
?>