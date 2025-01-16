<?php

// require_once 'DonateState.php';

class DonateFailedState extends DonateState
{

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $donate->setIsDonationSuccessful(false);
    }
}
