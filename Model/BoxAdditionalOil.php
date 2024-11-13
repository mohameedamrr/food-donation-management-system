<?php
require_once 'BoxDecorator.php';

class BoxAdditionalOil extends BoxDecorator {
    private $numBottles;

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
        return $this->ref->getContentDetails()." , additional oil bottels ".$this->numBottles;
    }

    public function calculateCost(): float
    {
        return ($this->numBottles * 20) + $this->ref->calculateCost(); //20 is price of 1 bottle

    }
}
?>

