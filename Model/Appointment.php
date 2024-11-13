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

    public function __construct($appointmentID) {
        
    }

    public function updateStatus(string $status): void {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    // Time (DateTime)
    public function getTime(): ?DateTime {
        return $this->time;
    }

    public function setTime(DateTime $time) {
        $this->time = $time;
    }

    // Date (DateTime)
    public function getDate(): ?DateTime {
        return $this->date;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    // Appointment ID
    public function getAppointmentID() {
        return $this->appointmentID;
    }

    public function setAppointmentID($appointmentID) {
        $this->appointmentID = $appointmentID;
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

    public function assignEmployee(Employee $employee): void {
        $this->employeeAssigned = $employee;
    }
}
?>
