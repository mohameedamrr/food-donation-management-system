<?php

require_once 'DonateState.php';
require_once '../Bill.php';

class billingState extends DonateState
{
    private float $amountToPay;

    public function __construct(float $amountToPay)
    {
        $this->amountToPay = $amountToPay;
    }

    /**
     * 2 - Create bill and pay
     * If the payment is successful, move to createDonationDetailsState
     * If payment fails, move to failedState
     */
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        // Create Bill
        $bill = new Bill($this->amountToPay);
        $bill->setPaymentStrategy($paymentMethod);
        $bill->setNextState(new InitialState()); 
        // or set some initial PaymentState like new ProcessingState() if you want to follow PaymentState pattern
        $bill->proceedPayment();

        if ($bill->getIsPaid() === true) {
            // Payment succeeded
            $donate->setNextState(new createDonationDetailsState($this->amountToPay));
        } else {
            // Payment failed
            $donate->setNextState(new DonateFailedState("Payment Failed. Please retry."));
        }
    }

// $bill = new Bill(90);
// echo $bill->getPaymentState();
// // $date = new DateTime();
// // $bill->setPaymentStrategy(new Card("ADASDASD", "132123", $date->modify('+1 day')));
// // $bill->setPaymentStrategy(new InstaPay("123123123"));
// $bill->proceeedPayment();
// echo $bill->getPaymentState();
// $bill->proceeedPayment();
// echo $bill->getPaymentState();
// $bill->proceeedPayment();
// echo $bill->getIsPaid() ? 'true' : 'false';
}
