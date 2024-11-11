<?php
// classes/BoxAdditionalPasta.php
require_once 'BoxDecorator.php';

class BoxAdditionalPasta extends BoxDecorator {
    private $numPackets;

    public function __construct(DonateBox $donateBox, $numPackets) {
        parent::__construct($donateBox);
        $this->numPackets = $numPackets;
    }

    public function getAdditionalContent() {
        return "Additional Pasta: {$this->numPackets} packets";
    }

    public function getContentDetails() {
        $details = parent::getContentDetails();
        $details['additionalPasta'] = $this->numPackets;
        return $details;
    }

    // Additional methods as needed
}
?>
