<?php
require_once 'DonationItem.php';

abstract class BillableDonate extends DonationItem {
    protected $currency;
    protected $cost;

    public abstract function calculateCost(): float;
    public abstract function executeDonation(): bool;

}
?>
