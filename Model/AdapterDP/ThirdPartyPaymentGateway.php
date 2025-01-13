<?php
 class ThirdPartyPaymentGateway {

    public function processPayment(float $amount, string $currency): bool{
        if($currency != 'EGP'){
            return false;
        }
        if ($amount <= 1000) {
            return true;
        } else {
            return false;
        }
    }
    }
?>