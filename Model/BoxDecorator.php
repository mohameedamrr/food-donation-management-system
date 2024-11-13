<?php
require_once 'DonateBox.php';

 abstract class BoxDecorator extends DonateBox {
   public DonateBox $ref; // DonateBox

     // protected $additionalCost;
    protected $pricePerUnit;

    public abstract function  getContentDetails(): string ;
}
?>
