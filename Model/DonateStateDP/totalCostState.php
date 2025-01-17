<?php

// require_once 'DonateState.php';

class TotalCostState extends DonateState
{
    static $totalCost = 0.0;
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $this->totalCost = 0.0;
        foreach ($donationItems as $item) {
            $this->totalCost += $item->getCost();
        }
        $donate->setNextState(new BillingState($this->totalCost));
    }
}
