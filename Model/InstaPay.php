<?php
require_once 'IPayment.php';

class InstaPay implements IPayment {
    private $transactionID;

    public function pay(float $cost): bool {
        // Process InstaPay payment
    }

    public function processTransaction(): bool {
        // Process the transaction
    }
}
?>
