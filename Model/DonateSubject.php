<?php
// classes/DonationSubject.php
require_once __DIR__ . '/../interfaces/Subject.php';

class DonationSubject implements Subject {
    private $observers; // List of Observer
    private $donationStatus;

    public function __construct() {
        $this->observers = array();
        $this->donationStatus = 'Pending';
    }

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function setStatus($status) {
        $this->donationStatus = $status;
        $this->notify();
    }

    public function getStatus() {
        return $this->donationStatus;
    }
}
?>
