<?php
class InstaPay implements IPayment {
    private $instapayAddress;
    public function __construct(string $instapayAddress){
        $this->instapayAddress = $instapayAddress;
    }
    public function pay(float $cost): bool {
        return $this->processTransaction();
    }

    private function processTransaction(): bool {
        $success = rand(1, 100) <= 90;
        return $success;
    }
}
?>
