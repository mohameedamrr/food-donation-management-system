<?php
 class ThirdPartyPaymentGateway {

    public function processPayment(float $amount, string $currency): bool{
        if($currency != 'EGP'){
            return false;
        }
        if ($amount <= 10000) {
            return true;
        } else {
            return false;
        }
    }
    }
?>