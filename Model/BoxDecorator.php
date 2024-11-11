<?php
// classes/BoxDecorator.php
require_once 'DonateBox.php';

abstract class BoxDecorator extends DonateBox {
    protected $ref; // DonateBox
    protected $additionalCost;
    protected $pricePerUnit;

    public function __construct(DonateBox $donateBox) {
        $this->ref = $donateBox;
    }

    public function getContentDetails() {
        return $this->ref->getContentDetails();
    }

    // Additional methods as needed
}
?>
