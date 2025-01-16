<?php
abstract class PaymentState{

    public abstract function nextPaymentState(Bill $bill): void;

}
?>
