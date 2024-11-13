<?php
require_once 'UserEntity.php';
require_once 'Location.php';

class BasicDonator extends UserEntity {
    private $location;
    private $donationHistory; // array of Donate objects

    public function makeDonation(array $items, int $id): bool {
        // Make a donation
    }

    public function viewDonationHistory(): array {
        // Return the donation history
    }

    public function createAppointment(): bool {
        // Create a new appointment
    }

    public function deleteAppointment(): bool {
        // Delete an appointment
    }
}
?>
