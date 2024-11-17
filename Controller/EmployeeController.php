<?php
require_once '../Model/DatabaseManager.php';

class EmployeeController {
    private $db;

    public function __construct() {
        $this->db = DatabaseManager::getInstance();
    }

    // Add a new employee
    public function addEmployee($id, $role, $department, $email) {
        $sql = "INSERT INTO `employees` (`id`, `role`, `department`, `email`) 
                VALUES ('$id', '$role', '$department', '$email')";
        $this->db->runQuery($sql);
        echo "Employee added successfully.";
    }

    // Get employee details by ID
    public function getEmployeeDetails($employeeId) {
        $sql = "SELECT * FROM `employees` WHERE `id` = '$employeeId'";
        $employeeDetails = $this->db->run_select_query($sql)->fetch_assoc();
        return $employeeDetails;
    }

    // Update employee role or department
    public function updateEmployee($employeeId, $role, $department) {
        $sql = "UPDATE `employees` SET `role` = '$role', `department` = '$department' WHERE `id` = '$employeeId'";
        $this->db->runQuery($sql);
        echo "Employee details updated successfully.";
    }
}
?>
