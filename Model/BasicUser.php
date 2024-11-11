<?php
require_once 'UserEntity.php';

class BasicUser extends UserEntity {
    public function __construct($id, $name, $email, $phone, $password, ILogin $loginMethod) {
        parent::__construct($id, $name, $email, $phone, $password, $loginMethod);
    }

    public function makeDonation(Donate $donation) {
        $this->addDonation($donation);
        $donationManager = DonationManager::getInstance();
        $donationManager->createDonation($donation);
        return true;
    }

    public function viewDonationHistory() {
        return $this->getDonations();
    }
}
?>
