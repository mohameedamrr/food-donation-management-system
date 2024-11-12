<?php
// views/BusinessView.php

spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        'View/',
        '../interfaces/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
// View/BusinessView.php

//require_once __DIR__ . '/../Model/Location.php';

class BusinessView {

    public function displayLocationAdded(Location $location) {
        echo "<h1>Location Added</h1>";
        echo "<p>A new location has been added to your business.</p>";
        echo "<p><strong>Location Details:</strong></p>";
        echo "<ul>";
        echo "<li>ID: " . htmlspecialchars($location->getId()) . "</li>";
        echo "<li>Name: " . htmlspecialchars($location->getName()) . "</li>";
        echo "<li>Parent ID: " . htmlspecialchars($location->getParentID()) . "</li>";
        echo "<li>Address Line: " . htmlspecialchars($location->getAddressLine()) . "</li>";
        echo "<li>Postal Code: " . htmlspecialchars($location->getPostalCode()) . "</li>";
        echo "</ul>";
    }

    public function displayLocationUpdated($locationID) {
        echo "<h1>Location Updated</h1>";
        echo "<p>Location with ID " . htmlspecialchars($locationID) . " has been updated successfully.</p>";
    }

    public function displayLocationDeleted($locationID) {
        echo "<h1>Location Deleted</h1>";
        echo "<p>Location with ID " . htmlspecialchars($locationID) . " has been deleted successfully.</p>";
    }
}
?>