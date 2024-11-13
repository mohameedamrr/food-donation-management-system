<?php
require_once 'BoxDecorator.php';

class BoxAdditionalRice extends BoxDecorator {
    protected $weight;

    public function __construct(DonateBox $ref)
    {
        $this->ref = $ref;
    }
    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getContentDetails(): string {
        return $this->ref->getContentDetails()." , additional rice of weight ".$this->weight;
    }

    public function calculateCost(): float
    {
        return ($this->weight * 30) + $this->ref->calculateCost(); //30 is price of 1 kg of rice

    }
}
?>
