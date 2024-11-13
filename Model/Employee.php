<?php
require_once 'UserEntity.php';
require_once 'IObserver.php';
require_once 'Appointment.php';

class Employee extends UserEntity implements IObserver {
    private $role;
    private $appointmentList; // array of Appointment objects
    private $department;
    private $admin; // ISubject

    public function changeDonationDescription(int $donationID, string $description): void {
        // Change the description of a donation
    }

    public function changeAppointmentStatus(int $appointmentID, string $status): void {
        // Change the status of an appointment
    }

    public function viewAssignedAppointments(): array {
        // Return assigned appointments
    }

    public function update(ISubject $subject): void {
        // Update method as per IObserver interface
    }
}
?>
