<?php
require_once 'Donate.php';

class DonationManager {
    private static $instance = null;
    private $donations;

    private function __construct() {
        $this->donations = array();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DonationManager();
        }
        return self::$instance;
    }

    public function createDonation(Donate $donation) {
        $this->donations[$donation->getDonationID()] = $donation;
        return true;
    }

    public function getDonationByID($id) {
        return $this->donations[$id] ?? null;
    }

    public function updateDonation(Donate $donation) {
        $id = $donation->getDonationID();
        if (isset($this->donations[$id])) {
            $this->donations[$id] = $donation;
            return true;
        }
        return false;
    }

    public function deleteDonation($id) {
        if (isset($this->donations[$id])) {
            unset($this->donations[$id]);
            return true;
        }
        return false;
    }

    public function getAllDonations() {
        return $this->donations;
    }
}
?>
