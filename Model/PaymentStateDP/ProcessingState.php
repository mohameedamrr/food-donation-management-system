<?php
// require_once '../Bill.php';
// require_once 'CompletedState.php';
// require_once 'FailedState.php';
 class ProcessingState extends PaymentState{

    public function nextPaymentState(Bill $bill): void {
        $isPaid = $bill->getPaymentStrategy()->pay($bill->getBillAmount());
        if($isPaid){
            $bill->setNextState(new CompletedState());
        } else {
            $bill->setNextState(new FailedState());
        }
        $bill->proceedPayment();
    }

    public function __toString(): string {
        return "Processing";
    }
}
?>