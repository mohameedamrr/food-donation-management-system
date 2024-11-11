<?php
// classes/Location.php

class Location {
    private $id;
    private $parentID;
    private $name;
    private $addressLine;
    private $postalCode;

    private static $locations = array();

    public function __construct($id, $name, $parentID = null, $addressLine = '', $postalCode = '') {
        $this->id = $id;
        $this->name = $name;
        $this->parentID = $parentID;
        $this->addressLine = $addressLine;
        $this->postalCode = $postalCode;

        self::$locations[$id] = $this;
    }

    public function getFullAddress($id) {
        $addressParts = array();
        $location = self::$locations[$id];
        while ($location != null) {
            $addressParts[] = $location->name;
            $parentID = $location->parentID;
            $location = $parentID ? self::$locations[$parentID] : null;
        }
        return implode(', ', array_reverse($addressParts));
    }

    public function addLocation($name, $parentID) {
        $id = uniqid();
        $location = new Location($id, $name, $parentID);
        return $id;
    }

    public function deleteLocation($id) {
        if (isset(self::$locations[$id])) {
            unset(self::$locations[$id]);
            return true;
        }
        return false;
    }

    public function updateLocation($id, $name) {
        if (isset(self::$locations[$id])) {
            self::$locations[$id]->name = $name;
            return true;
        }
        return false;
    }

    // Additional methods as needed
}
?>
