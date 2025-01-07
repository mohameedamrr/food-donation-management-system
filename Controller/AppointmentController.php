<?php
require_once '../Model/DatabaseManager.php';

class AppointmentController {
    private $db;

    public function __construct() {
        $this->db = DatabaseManager::getInstance();
    }

    // Create a new appointment
    public function createAppointment($employeeAssignedID, $status, $date, $location) {
        $sql = "INSERT INTO `appointments` (`status`, `date`, `employeeAssignedID`, `location`) 
                VALUES ('$status', '$date', '$employeeAssignedID', '$location')";
        $this->db->runQuery($sql);
        echo "Appointment created successfully.";
    }

    // Get all appointments for a specific employee
    public function getAppointmentsForEmployee($employeeAssignedID) {
        $sql = "SELECT * FROM `appointments` WHERE `employeeAssignedID` = '$employeeAssignedID'";
        $appointments = $this->db->run_select_query($sql)->fetch_all(MYSQLI_ASSOC);
        return $appointments;
    }

    // Update appointment status
    public function updateAppointmentStatus($appointmentID, $status) {
        $sql = "UPDATE `appointments` SET `status` = '$status' WHERE `appointmentID` = '$appointmentID'";
        $this->db->runQuery($sql);
        echo "Appointment status updated successfully.";
    }
}
?>
