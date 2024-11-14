<?php
require_once 'BoxDecorator.php';

class BoxAdditionalPasta extends BoxDecorator {
    private $numPackets;

    public function __construct(DonateBox $ref)
    {
        $this->ref = $ref;
    }
    /**
     * @return mixed
     */
    public function getNumPackets()
    {
        return $this->numPackets;
    }

    /**
     * @param mixed $numPackets
     */
    public function setNumPackets($numPackets): void
    {
        $this->numPackets = $numPackets;
    }


    public function getContentDetails(): string {
        return $this->ref->getContentDetails()." , additional pasta packets ".$this->numPackets;
    }

    public function calculateCost(): float
    {
        return ($this->numPackets * 10) + $this->ref->calculateCost(); //10 is price of 1 packet of pasta

    }

    public function getWeight()
    {
        return $this->numPackets*2 + $this->ref->getWeight(); //  1 pasta packet = 2 kilo
    }
}
?>
