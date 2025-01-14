<?php

abstract class BoxDecorator extends Box {
  protected $box;

  protected $additionalCost;

  protected $pricePerUnit;

  public function __construct(Box $box, $pricePerUnit) {
    $this->box = $box;
    $this->pricePerUnit = $pricePerUnit;
  }

  abstract public function addItem($item): array;

  abstract public function calculateCost(): float;
}


?>
