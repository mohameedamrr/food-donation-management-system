<?php

require_once 'DonateState.php';

/**
 * 6 - Failed state 
 * This will stop the normal donation flow, or
 * you can optionally allow re-tries from here.
 */
class DonateFailedState extends DonateState
{
    private string $errorMessage;

    public function __construct(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        // Log the failure, show error message, or handle re-tries
        error_log("[FAILED STATE] DonationID {$donate->getDonationID()} - {$this->errorMessage}");

        // Typically, you do not transition to another state unless you have logic to fix the error
        // So we might end the donation process here.
    }
}
