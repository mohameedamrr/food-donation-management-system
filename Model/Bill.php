<?php
require_once 'IPayment.php';
require_once 'DonationDetails.php';

class Bill {
    private $paymentStrategy; // IPayment
    private $billAmount;      // float
    private $donationDetails;  // DonationDetails

    public function __construct(IPayment $paymentStrategy, float $billAmount, DonationDetails $donationDetails) {
        $this->paymentStrategy = $paymentStrategy;
        $this->billAmount = $billAmount;
        $this->donationDetails = $donationDetails;
    }

    // Getters and Setters
    public function getPaymentStrategy(): IPayment {
        return $this->paymentStrategy;
    }

    public function setPaymentStrategy(IPayment $strategy): void {
        $this->paymentStrategy = $strategy;
    }

    public function getBillAmount(): float {
        return $this->billAmount;
    }

    public function setBillAmount(float $amount): void {
        $this->billAmount = $amount;
    }

    public function getDonationDetails(): DonationDetails {
        return $this->donationDetails;
    }

    public function setDonationDetails(DonationDetails $donationDetails): void {
        $this->donationDetails = $donationDetails;
    }

    // Methods
    public function executePayment(): bool {
        // if (!$this->paymentStrategy) {
        //     throw new Exception("Payment strategy not set.");
        // }

        // if (!$this->donationDetails) {
        //     throw new Exception("Billable donation not set.");
        // }

        $this->billAmount = $this->donationDetails->getTotalCost();
        return $this->paymentStrategy->pay($this->billAmount);
    }
}
?>
