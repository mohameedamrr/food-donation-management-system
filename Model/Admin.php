<?php
require_once 'UserEntity.php';
require_once 'Employee.php';

class Admin extends UserEntity {
    protected $role;
    protected $tasksList;
    protected $permissions;

    public function __construct($id, $name, $email, $phone, $password, ILogin $loginMethod, $role, $permissions) {
        parent::__construct($id, $name, $email, $phone, $password, $loginMethod);
        $this->role = $role;
        $this->permissions = $permissions;
        $this->tasksList = array();
    }

    public function assignAppointment($appointmentID, Employee $employee) {
        $appointmentManager = AppointmentManager::getInstance();
        $appointment = $appointmentManager->getAppointmentByID($appointmentID);
        if ($appointment) {
            $appointment->assignEmployee($employee);
            return true;
        }
        return false;
    }

    public function editDonationCost($donationID, $cost) {
        $donationManager = DonationManager::getInstance();
        $donation = $donationManager->getDonationByID($donationID);
        if ($donation && $donation instanceof BillableDonate) {
            $donation->setCost($cost);
            $donationManager->updateDonation($donation);
            return true;
        }
        return false;
    }

    public function createEmployee($employeeData) {
        $loginMethod = new NormalMethod(password_hash($employeeData['password'], PASSWORD_DEFAULT));
        $employee = new Employee(
            $employeeData['id'],
            $employeeData['name'],
            $employeeData['email'],
            $employeeData['phone'],
            $employeeData['password'],
            $loginMethod,
            $employeeData['role'],
            $employeeData['department']
        );
        // Add employee to employee list or database
        // For simplicity, we'll assume it's successful
        return $employee;
    }

    public function deleteEmployee($employeeID) {
        // Implement logic to delete an employee
        // For simplicity, we'll assume it's successful
        return true;
    }
}
?>
