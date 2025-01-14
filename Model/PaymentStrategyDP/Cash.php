<?php

class Cash implements IPayment {

    public function pay(float $cost): bool {
        // Minimum amount to pay in cash is 100
        if($cost >= 100){
            return true;
        } else {
            return false;
        }
    }
}
?>
