<?php

// require_once 'DonateState.php';

class TotalCostState extends DonateState
{
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $totalCost = 0.0;

        foreach ($donationItems as $item) {
            $totalCost += $item->getCost();
        }
        $donate->setNextState(new BillingState($totalCost));
    }
}
