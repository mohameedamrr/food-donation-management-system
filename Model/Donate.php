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

    public function getDonationID() {
        return $this->donationID;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }
}
?>
