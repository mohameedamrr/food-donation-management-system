<?php
require_once 'Bill.php';
require_once 'ProcessingState.php';
 class InitialState extends PaymentState{

    public function nextPaymentState(Bill $bill): void {
        $bill->setIsPaid(false);
        $bill->setNextState(new ProcessingState());
    }
    public function __toString(): string {
        return "Initial";
    }
 }
?>