<?php
require_once 'BillableDonate.php';

class DonateMoney extends BillableDonate {
    private $amount;
    private $donationPurpose;


    public function __construct($donationID, $donationDate, UserEntity $user, $currency, $amount, $donationPurpose) {
        parent::__construct($donationID, $donationDate, $user, $currency, $amount);
        $this->amount = $amount;
        $this->donationPurpose = $donationPurpose;
    }

    public function donateAmount() {
        // Process the monetary donation
        // For example, update database records
        return true;
    }

    // public function getReceipt() {
    //     return "Receipt: Donated {$this->amount} {$this->currency} for {$this->donationPurpose}";
    // }
    public function getAmount() {
		return $this->amount;
	}

	public function setAmount($value) {
		$this->amount = $value;
	}

	public function getDonationPurpose() {
		return $this->donationPurpose;
	}

	public function setDonationPurpose($value) {
		$this->donationPurpose = $value;
	}

}
?>
