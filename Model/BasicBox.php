<?php
// classes/BasicBox.php
require_once 'DonateBox.php';

class BasicBox extends DonateBox {
    private $boxSize;
    private $itemList; // List of items
    private $weightLimit;

    public function __construct($boxSize, $weightLimit) {
        parent::__construct();
        $this->boxSize = $boxSize;
        $this->weightLimit = $weightLimit;
        $this->itemList = array();
    }

    public function addItem($item) {
        $this->itemList[] = $item;
    }

    public function getContentDetails() {
        return array(
            'boxSize' => $this->boxSize,
            'items' => $this->itemList,
            'weightLimit' => $this->weightLimit,
        );
    }

    // Additional methods as needed
}
?>
