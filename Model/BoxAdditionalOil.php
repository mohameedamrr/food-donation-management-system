<?php
// classes/BoxAdditionalOil.php
require_once 'BoxDecorator.php';

class BoxAdditionalOil extends BoxDecorator {
    private $numBottles;

    public function __construct(DonateBox $donateBox, $numBottles) {
        parent::__construct($donateBox);
        $this->numBottles = $numBottles;
    }

    public function getAdditionalContent() {
        return "Additional Oil: {$this->numBottles} bottles";
    }

    public function getContentDetails() {
        $details = parent::getContentDetails();
        $details['additionalOil'] = $this->numBottles;
        return $details;
    }

    // Additional methods as needed
}
?>
