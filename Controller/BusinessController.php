<?php
// controllers/BusinessController.php

spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
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

class BusinessController {
    private $businessView;

    public function __construct() {
        $this->businessView = new BusinessView();
    }

    public function addLocation($locationData, Business $business) {
        $location = new Location(
            $locationData['id'],
            $locationData['name'],
            $locationData['parentID'],
            $locationData['addressLine'],
            $locationData['postalCode']
        );
        $business->addLocation($location);
        $this->businessView->displayLocationAdded($location);
    }

    public function updateLocation($locationID, $locationData, Business $business) {
        $business->updateLocation($locationID, $locationData);
        $this->businessView->displayLocationUpdated($locationID);
    }

    public function deleteLocation($locationID, Business $business) {
        $business->deleteLocation($locationID);
        $this->businessView->displayLocationDeleted($locationID);
    }
}
?>
