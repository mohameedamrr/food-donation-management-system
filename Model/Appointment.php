<?php
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

class Appointment {
    private $appointmentID;
    private $status;
    private $date; // DateTime object
    private $appointmentLocation; // Location object
    private $employeeAssignedID; // Employee object
    private $note;
    private $userId;


    public function __construct($appointmentID) {
        $sql = "SELECT * FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        $db = new DatabaseManagerProxy('admin');
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            $this->appointmentID = $row["appointmentID"];
            $this->userId = $row["userID"];
            $this->note = $row["note"];
            $this->status = $row["status"];
            $this->date = $row["date"];
            $this->appointmentLocation = $row["location"];
            if(isset($row["employeeAssignedID"])) {
                $this->employeeAssignedID = $row["employeeAssignedID"];
            } else {
                $this->employeeAssignedID = null;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }

    public function getUserId(): mixed
    {
        return $this->userId;
    } // Employee object

    public function updateStatus($status){
        $this->status = $status;
        $sql = "UPDATE `food_donation`.`appointments` SET status = '$this->status' WHERE appointmentID = $this->appointmentID";
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
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
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
    }

    // Appointment ID
    public function getAppointmentID() {
        return $this->appointmentID;
    }

    public function setAppointmentID($appointmentID) {
        $this->appointmentID = $appointmentID;
        $sql = "UPDATE `food_donation`.`appointments` SET appointmentID = '$this->appointmentID' WHERE appointmentID = $this->appointmentID";
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
    }

    // Appointment Location (Location object)
    public function getAppointmentLocation() {
        return $this->appointmentLocation;
    }

    public function setAppointmentLocation($appointmentLocation) {
        $this->appointmentLocation = $appointmentLocation;
    }

    // Employee Assigned (Employee object)
    public function getEmployeeAssignedID() {
        return $this->employeeAssignedID;
    }

    public function assignEmployee($employeeID): void {
        $sql = "UPDATE `food_donation`.`appointments` SET employeeAssignedID = $employeeID WHERE appointmentID = $this->appointmentID";
        $this->employeeAssignedID = $employeeID;
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
    }

    public function updateObject(array $data) {
        $updates = [];
        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE `food_donation`.`appointments` SET " . implode(", ", $updates) . " WHERE appointmentID = $this->appointmentID";
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
    }

    public static function deleteObject($appointmentID) {
        $sql = "DELETE FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
    }

    public static function readObject($appointmentID) {
        // $sql = "SELECT * FROM `food_donation`.`appointments` WHERE appointmentID = $appointmentID";
        // $db = DatabaseManager::getInstance();
        // $row = $db->run_select_query($sql)->fetch_assoc();
        // if(isset($row)) {
        //     return new Appointment($row["appointmentID"], null);
        // }
        // return null;
        return new Appointment($appointmentID, null);
    }

    public static function storeObject(array $data) {
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`appointments` ($columns) VALUES ($placeholders)";
        $db = new DatabaseManagerProxy('admin');
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new Appointment($lastInsertedId, null);
    }
}
?>
