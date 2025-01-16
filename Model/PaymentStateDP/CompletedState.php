<?php
// require_once 'Bill.php';
// require_once 'ProcessingState.php';
 class CompletedState extends PaymentState{

    public function nextPaymentState(Bill $bill): void {
        $bill->setIsPaid(true);
    }
    public function __toString(): string {
        return "Completed";
    }
 }
?>