<?php
// classes/Card.php
require_once __DIR__ . '/../interfaces/IPayment.php';

class Card implements IPayment {
    private $cardHolderName;
    private $cardNumber;

    public function __construct($cardHolderName, $cardNumber) {
        $this->cardHolderName = $cardHolderName;
        $this->cardNumber = $cardNumber;
    }

    public function pay($cost) {
        // Implement card payment processing
        // For simplicity, we'll assume the payment is always successful

        // Process the card payment
        if ($this->processCardPayment($this->cardNumber, $cost)) {
            return true;
        } else {
            return false;
        }
    }

    public function processCardPayment($cardNumber, $cost) {
        // Simulate processing the card payment
        // Check if card number is valid
        if ($this->validateCardNumber($cardNumber)) {
            // Assume payment is successful
            return true;
        } else {
            // Payment failed
            return false;
        }
    }

    private function validateCardNumber($cardNumber) {
        // Implement card number validation
        // For simplicity, let's check if the card number is numeric and 16 digits
        if (is_numeric($cardNumber) && strlen($cardNumber) == 16) {
            return true;
        } else {
            return false;
        }
    }
}
?>
