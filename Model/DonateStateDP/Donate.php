<?php

require_once 'DonateState.php';
require_once 'totalCostState.php';
require_once 'billingState.php';
require_once 'createDonationDetailsState.php';
require_once 'storeDonationDetailsState.php';
require_once 'sendMailState.php';
require_once 'failedState.php';

class Donate
{
    private int $donationID;
    private DateTime $donationDate;
    private string $userId;
    private DonateState $donationState;

    public function __construct(int $donationID, string $userId)
    {
        $this->donationID    = $donationID;
        $this->userId        = $userId;
        $this->donationDate  = new DateTime();
        
        // Initial state: let's assume we start by calculating total cost
        $this->donationState = new totalCostState();
    }

    /**
     * Set the next state of the donation process.
     *
     * @param DonateState $state
     */
    public function setNextState(DonateState $state): void
    {
        $this->donationState = $state;
    }

    /**
     * Proceed with donation process by calling the current state's logic.
     *
     * @param DonationItem[] $donationItems
     * @param IPayment       $paymentMethod
     * @return bool
     */
    public function proceedDonation(array $donationItems, IPayment $paymentMethod): void
    {

        $this->donationState->nextDonationState($this, $donationItems, $paymentMethod);
            
    }

    // Getters and Setters (if needed)
    public function getDonationID(): int
    {
        return $this->donationID;
    }

    public function setDonationID(int $donationID): void
    {
        $this->donationID = $donationID;
    }

    public function getDonationDate(): DateTime
    {
        return $this->donationDate;
    }

    public function setDonationDate(DateTime $donationDate): void
    {
        $this->donationDate = $donationDate;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getDonationState(): DonateState
    {
        return $this->donationState;
    }
}
