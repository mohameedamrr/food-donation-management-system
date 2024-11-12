<?php
abstract class Donate {
    protected $donationID;
    protected $donationDate;
    protected $user;
    protected $description;

    public function __construct($donationID, $donationDate, UserEntity $user) {
        $this->donationID = $donationID;
        $this->donationDate = $donationDate;
        $this->user = $user;
        $this->description = '';
    }

    // public function getDonationID(): int {
    //     return $this->donationID;
    // }

    // public function setDescription($description) {
    //     $this->description = $description;
    // }

    // public function getDescription() {
    //     return $this->description;
    // }

    // public function getDate(): mixed {
    //     return $this->donationDate;
    // }

    public function getDonationID() {
		return $this->donationID;
	}

	public function setDonationID($value) {
		$this->donationID = $value;
	}

	public function getDonationDate() {
		return $this->donationDate;
	}

	public function setDonationDate($value) {
		$this->donationDate = $value;
	}

	public function getUser() {
		return $this->user;
	}

	public function setUser($value) {
		$this->user = $value;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($value) {
		$this->description = $value;
	}
}
?>

