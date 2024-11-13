<?php
interface IPayment {
    public function pay(float $cost): bool;
}
?>
