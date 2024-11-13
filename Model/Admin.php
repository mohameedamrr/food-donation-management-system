<?php
require_once 'UserEntity.php';
require_once 'ISubject.php';
require_once 'Employee.php';

class Admin extends UserEntity implements ISubject {
    private $role;
    private $tasksList; // array of tasks
    private $observersList; // array of IObserver objects

    public function assignAppointment(int $appointmentID, Employee $employee): void {
        // Assign an appointment to an employee
    }

    public function editDonationCost(int $donationID, float $cost): void {
        // Edit the cost of a donation
    }

    public function createEmployee(array $employeeData): Employee {
        // Create a new employee and return it
    }

    public function deleteEmployee(int $employeeID): void {
        // Delete an employee
    }

    public function addTask(string $task): void {
        // Add a new task
    }

    public function removeTask(string $task): void {
        // Remove a task
    }

    public function addObserver(IObserver $observer): void {
        // Add an observer
    }

    public function removeObserver(IObserver $observer): void {
        // Remove an observer
    }

    public function notifyObservers(): void {
        // Notify all observers
    }
}
?>

