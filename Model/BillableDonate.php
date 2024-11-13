<?php
require_once 'DonationItem.php';

abstract class BillableDonate extends DonationItem {
    protected $currency;
    protected $cost;

    public function calculateCost(): float {
        // Calculate and return the cost
    }
}
?>
