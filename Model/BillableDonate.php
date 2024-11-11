<?php
require_once 'Donate.php';

abstract class BillableDonate extends Donate {
    protected $currency;
    protected $cost;

    public function __construct($donationID, $donationDate, UserEntity $user, $currency, $cost) {
        parent::__construct($donationID, $donationDate, $user);
        $this->currency = $currency;
        $this->cost = $cost;
    }

    public function calculateCost() {
        return $this->cost;
    }

    public function setCost($cost) {
        $this->cost = $cost;
    }
}
?>
