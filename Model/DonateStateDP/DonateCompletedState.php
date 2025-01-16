<?php

require_once 'DonateState.php';

class DonateCompletedState extends DonateState
{

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $donate->setIsDonationSuccessful(true);
    }
}
