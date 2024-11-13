<?php
require_once 'BillableDonate.php';

class DonateMoney extends BillableDonate {
    private $amount;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }
    private $donationPurpose;


    public function getReceipt(): string {
        return $this->donationPurpose." The cost of this is:".$this->amount;
    }

    public function calculateCost(): float
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getDonationPurpose()
    {
        return $this->donationPurpose;
    }

    /**
     * @param mixed $donationPurpose
     */
    public function setDonationPurpose($donationPurpose): void
    {
        $this->donationPurpose = $donationPurpose;
    }
}
?>
