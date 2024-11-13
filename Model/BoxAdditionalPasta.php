<?php
require_once 'BoxDecorator.php';

class BoxAdditionalPasta extends BoxDecorator {
    private $numPackets;

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
}
?>
