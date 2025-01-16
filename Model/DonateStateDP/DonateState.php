<?php

abstract class DonateState
{
    /**
     * This method will handle the main logic for a given state and
     * then transition the Donate object to the next state.
     *
     * @param Donate         $donate
     * @param DonationItem[] $donationItems
     * @param IPayment       $paymentMethod
     */
    abstract public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void;
}
