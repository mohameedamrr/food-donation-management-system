<?php
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
        return $this->processCardPayment($cost);
    }

    private function processCardPayment(float $cost): bool {
        if ($this->expiryDate > new DateTime()) {
            $authorized = $this->authorizeTransaction($cost);
            return $authorized;
        } else {
            return false;
        }
    }

    private function authorizeTransaction(float $cost): bool {
        if ($cost <= 4000) {
            return true;
        } else {
            return false;
        }
    }
}
?>
