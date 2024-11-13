<?php
require_once 'DonateBox.php';

abstract class BoxDecorator extends DonateBox {
    protected $ref; // DonateBox
    protected $additionalCost;
    protected $pricePerUnit;

    public function __construct(DonateBox $ref) {
        $this->ref = $ref;
    }

    public function getContentDetails(): array {
        // Return the contents including decorations
    }
}
?>
