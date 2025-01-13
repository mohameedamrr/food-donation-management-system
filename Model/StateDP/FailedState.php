<?php
require_once 'Bill.php';
require_once 'ProcessingState.php';
 class FailedState extends PaymentState{

    public function nextPaymentState(Bill $bill): void {
        $bill->setIsPaid(false);
    }
    public function __toString(): string {
        return "Failed";
    }
 }
?>