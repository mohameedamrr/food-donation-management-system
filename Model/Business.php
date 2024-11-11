<?php
// classes/Business.php
require_once 'Location.php';
require_once 'Donate.php';

class Business {
    private $location; // Location

    public function __construct(Location $location) {
        $this->location = $location;
    }

    public function provideDonation(Donate $donation) {
        // Implement logic to provide a donation
    }

    public function addLocation(Location $location) {
        // Add location to the business
    }

    public function deleteLocation($locationID) {
        // Delete location from the business
    }

    public function updateLocation($locationID, Location $newLocation) {
        // Update location information
    }

    // Additional methods as needed
}
?>
