<?php

require_once 'DonateState.php';

class totalCostState extends DonateState
{
    /**
     * 1 - Calculate total cost of the donation items
     * After calculating, move to the billing state.
     */
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $totalCost = 0.0;

        foreach ($donationItems as $item) {
            // We assume $item->validate() has been called or is valid by this point
            $totalCost += $item->getCost(); 
        }
        $donate->setNextState(new billingState($totalCost));
        // Immediately proceed to next step or let the client call proceedDonation() again.
    }
}
