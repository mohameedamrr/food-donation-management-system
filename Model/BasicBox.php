<?php
require_once 'DonateBox.php';

class BasicBox extends DonateBox {

    public function __construct()
    {
    }

    public function getContentDetails(): string {
        return "this box consists of 2 kg rice, 2 pasta packets and 1 oil bottle";
    }

    public function calculateCost(): float
    {
        return 80; //price of basic box
    }

}
?>
