<?php
require_once 'IPayment.php';

class Card implements IPayment {
    private $cardHolderName;
    private $cardNumber;

    public function pay(float $cost): bool {
        // Process card payment
    }

    public function processCardPayment(string $cardNumber, DateTime $expiryDate): bool {
        // Process the card payment with provided details
    }
}
?>
