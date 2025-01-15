<?php
// require_once '../DonationItemChildren/DonationItem.php';

abstract class Box extends DonationItem {

    protected $initialBoxSize;

    protected $initialItemList;

    protected $finalBoxSize;

    protected $finalItemList;

    protected $totalCost;


    public function __construct($item_id, $item_name, $currency, $cost, $initialBoxSize, $initialItemList) {
        $this->initialBoxSize = $initialBoxSize;
        $this->initialItemList = $initialItemList;
        parent::__construct($item_id, $item_name, $currency, $cost);
    }

    abstract public function addItem($item): array;

    abstract public function calculateCost(): float;


    public function getInitialBoxSize() {
        return $this->initialBoxSize;
    }

    public function setInitialBoxSize($initialBoxSize) {
        $this->initialBoxSize = $initialBoxSize;

    }

    public function getInitialItemList() {
        return $this->initialItemList;
    }

    public function setInitialItemList($initialItemList) {
        $this->initialItemList = $initialItemList;

    }

    public function getFinalBoxSize() {
        return $this->finalBoxSize;
    }

    public function setFinalBoxSize($finalBoxSize) {
        $this->finalBoxSize = $finalBoxSize;

    }

    public function getFinalItemList() {
        return $this->finalItemList;
    }

    public function setFinalItemList($finalItemList) {
        $this->finalItemList = $finalItemList;

    }

    public function getTotalCost() {
        return $this->totalCost;
    }

    public function setTotalCost($totalCost) {
        $this->totalCost = $totalCost;

    }
    public function validate(): bool{
        if ($this->initialBoxSize <= 0 || $this->finalBoxSize < 0 || $this->initialItemList == null || $this->finalItemList == null || $this->finalBoxSize >500){   
            return false;
        }
        else{
            return true;
        }
    }
}
    
?>
