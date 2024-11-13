<?php
require_once 'DonateBox.php';

class BasicBox extends DonateBox {
    private $boxSize;
    private $itemList; // array of item names
    private $weightLimit;

    public function addItem(string $item): void {
        // Add an item to the box
    }

    public function getContentDetails(): array {
        // Return the contents of the box
    }
}
?>
