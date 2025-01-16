<?php

require_once 'DonateState.php';

class createDonationDetailsState extends DonateState
{
    private float $amountPaid;

    public function __construct(float $amountPaid)
    {
        $this->amountPaid = $amountPaid;
    }

    /**
     * 3 - If payment succeeded, create donation details
     */
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        
        // Create a DonationDetails object
        $donationDetails =DonationDetails::storeObject();
        $donationDetails->totalCost   = $this->amountPaid;
        $donationDetails->description = "Donation for user: " . $donate->getUserId();
        $donationDetails->DonationId  = $donate->getDonationID();
        $donationDetails->DonationItems = $donationItems; // an array of items

        // Transition to storeDonationDetailsState
        $donate->setNextState(new storeDonationDetailsState($donationDetails));
    }
}
