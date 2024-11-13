<?php
class Location {
    private $id;
    private $name;
    private $parent; // Location object

    public function getFullAddress(int $id): Location {
        // Return the full address as a Location object
    }

    public function addLocation(string $name, int $parentID): int {
        // Add a new location and return its ID
    }

    public function deleteLocation(int $id): bool {
        // Delete a location by ID
    }

    public function updateLocation(int $id, string $name): bool {
        // Update a location's name
    }
}
?>
