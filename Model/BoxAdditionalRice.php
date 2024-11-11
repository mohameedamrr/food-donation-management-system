<?php
// classes/BoxAdditionalRice.php
require_once 'BoxDecorator.php';

class BoxAdditionalRice extends BoxDecorator {
    private $weight; // Weight in kg

    public function __construct(DonateBox $donateBox, $weight) {
        parent::__construct($donateBox);
        $this->weight = $weight;
    }

    public function getAdditionalContent() {
        return "Additional Rice: {$this->weight} kg";
    }

    public function getContentDetails() {
        $details = parent::getContentDetails();
        $details['additionalRice'] = $this->weight;
        return $details;
    }

    // Additional methods as needed
}
?>
