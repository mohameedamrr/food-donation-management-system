<?php
class NoPaymentMethod implements IPayment {

    public function pay(float $cost): bool {
        return true;
    }
}
?>
