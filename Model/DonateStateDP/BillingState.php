<?php

require_once 'DonateState.php';
require_once '../Bill.php';

class BillingState extends DonateState
{
    private float $amountToPay;

    public function __construct(float $amountToPay)
    {
        $this->amountToPay = $amountToPay;
    }

    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        $bill = new Bill($this->amountToPay);
        $bill->setPaymentStrategy($paymentMethod);
        $bill->setNextState(new InitialState());
        $bill->proceedPayment();

        if ($bill->getIsPaid() === true) {
            $donate->setNextState(new CreateDonationDetailsState($this->amountToPay));
        } else {
            $donate->setNextState(new DonateFailedState("Payment Failed. Please retry."));
        }
    }

}
