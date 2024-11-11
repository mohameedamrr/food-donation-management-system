<?php
// classes/DonateBox.php

abstract class DonateBox {
    protected $components; // List of DonateBox

    public function __construct() {
        $this->components = array();
    }

    public function getContentDetails() {
        $details = array();
        foreach ($this->components as $component) {
            $details[] = $component->getContentDetails();
        }
        return $details;
    }

    // Additional methods as needed
}
?>
