<?php
require_once 'Location.php';
require_once 'Employee.php';

class Appointment {
    private $appointmentID;
    private $status;
    private $date; // DateTime object
    private $appointmentLocation; // Location object
    private $employeeAssigned; // Employee object

    public function __construct($appointmentID, $location) {
        $sql = "SELECT * FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            $this->appointmentID = $row["appointmentID"];
            $this->status = $row["status"];
            $this->date = $row["date"];
            if(isset($row["employeeAssignedID"])) {
                $this->employeeAssigned = Employee::readObject($row["employeeAssignedID"]);
            } else {
                $this->employeeAssigned = null;
            }
        }
        $this->appointmentLocation = $location;
    }


    public function updateStatus($status){
        $this->status = $status;
        $sql = "UPDATE `food_donation`.`appointments` SET status = '$this->status' WHERE appointmentID = $this->appointmentID";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getStatus() {
        return $this->status;
    }

    // Date (DateTime)
    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        $sql = "UPDATE `food_donation`.`appointments` SET date = '".$this->date."' WHERE appointmentID = $this->appointmentID";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    // Appointment ID
    public function getAppointmentID() {
        return $this->appointmentID;
    }

    public function setAppointmentID($appointmentID) {
        $this->appointmentID = $appointmentID;
        $sql = "UPDATE `food_donation`.`appointments` SET appointmentID = '$this->appointmentID' WHERE appointmentID = $this->appointmentID";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    // Appointment Location (Location object)
    public function getAppointmentLocation() {
        return $this->appointmentLocation;
    }

    public function setAppointmentLocation($appointmentLocation) {
        $this->appointmentLocation = $appointmentLocation;
    }

    // Employee Assigned (Employee object)
    public function getEmployeeAssigned() {
        return $this->employeeAssigned;
    }

    public function assignEmployee($employeeID): void {
        $this->employeeAssigned = Employee::readObject($employeeID);
        $sql = "UPDATE `food_donation`.`appointments` SET employeeAssignedID = '".$employeeID."' WHERE appointmentID = $this->appointmentID";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function updateObject(array $data) {
        $updates = [];
        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE `food_donation`.`appointments` SET " . implode(", ", $updates) . " WHERE appointmentID = $this->appointmentID";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public static function deleteObject($appointmentID) {
        $sql = "DELETE FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
    }

    public static function readObject($appointmentID) {
        $sql = "SELECT * FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            return new Appointment($row["appointmentID"], null);
        }
        return null;
    }

    public static function storeObject(array $data) {
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`appointments` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new Appointment($lastInsertedId, null);
    }
}
?>
