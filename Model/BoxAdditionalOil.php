<?php
require_once 'BoxDecorator.php';

class BoxAdditionalOil extends BoxDecorator {
    private $numBottles;

    public function __construct(DonateBox $ref)
    {
        $this->ref = $ref;
    }

    /**
     * @return mixed
     */
    public function getNumBottles()
    {
        return $this->numBottles;
    }

    /**
     * @param mixed $numBottles
     */
    public function setNumBottles($numBottles): void
    {
        $this->numBottles = $numBottles;
    }

    public function getContentDetails(): string {
        return $this->ref->getContentDetails()." , additional oil bottles ".$this->numBottles;
    }

    public function calculateCost(): float
    {
        return ($this->numBottles * 20) + $this->ref->calculateCost(); //20 is price of 1 bottle

    }

    public function getWeight()
    {
        return $this->numBottles*1 + $this->ref->getWeight(); //  1 kilo oil bottle
    }
}
?>

