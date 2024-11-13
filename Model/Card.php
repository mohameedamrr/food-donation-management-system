<?php
require_once 'IPayment.php';

class Card implements IPayment {
    private $cardHolderName;
    private $cardNumber;
    private $expiryDate;

    public function __construct(string $cardHolderName, string $cardNumber, DateTime $expiryDate) {
        $this->cardHolderName = $cardHolderName;
        $this->cardNumber = $cardNumber;
        $this->expiryDate = $expiryDate;
    }

    public function pay(float $cost): bool {
        // Process card payment
        if ($this->processCardPayment($this->cardNumber, $this->expiryDate, $cost)) {
            echo "Payment of $$cost received via Card.\n";
            return true;
        } else {
            echo "Card payment failed.\n";
            return false;
        }
    }

    private function processCardPayment(string $cardNumber, DateTime $expiryDate, float $cost): bool {
        // Simulate card payment processing
        if ($expiryDate > new DateTime()) {
            // Simulate authorization process
            $authorized = $this->authorizeTransaction($cost);
            return $authorized;
        } else {
            // Card expired
            echo "Card has expired.\n";
            return false;
        }
    }

    private function authorizeTransaction(float $cost): bool {
        // Simulate transaction authorization
        // Let's assume all transactions under $1000 are authorized
        if ($cost <= 1000) {
            return true;
        } else {
            echo "Transaction declined by bank.\n";
            return false;
        }
    }
}
?>
