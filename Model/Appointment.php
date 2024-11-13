<?php
require_once 'Location.php';
require_once 'Employee.php';

class Appointment {
    private $status;
    private $time; // DateTime object
    private $date; // DateTime object
    private $appointmentID;
    private $appointmentLocation; // Location object
    private $employeeAssigned; // Employee object

    public function updateStatus(string $status): void {
        // Update the status of the appointment
    }

    public function assignEmployee(Employee $employee): void {
        // Assign an employee to the appointment
    }
}
?>
