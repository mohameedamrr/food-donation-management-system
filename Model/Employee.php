<?php
require_once 'UserEntity.php';

class Employee extends UserEntity {
    protected $role;
    protected $appointmentList;
    protected $department;

    public function __construct($id, $name, $email, $phone, $password, ILogin $loginMethod, $role, $department) {
        parent::__construct($id, $name, $email, $phone, $password, $loginMethod);
        $this->role = $role;
        $this->department = $department;
        $this->appointmentList = array();
    }

    public function changeDonationDescription($donationID, $description) {
        $donationManager = DonationManager::getInstance();
        $donation = $donationManager->getDonationByID($donationID);
        if ($donation) {
            $donation->setDescription($description);
            $donationManager->updateDonation($donation);
            return true;
        }
        return false;
    }

    public function changeAppointmentStatus($appointmentID, $status) {
        $appointmentManager = AppointmentManager::getInstance();
        $appointment = $appointmentManager->getAppointmentByID($appointmentID);
        if ($appointment) {
            $appointment->updateStatus($status);
            return true;
        }
        return false;
    }

    public function viewAssignedAppointments() {
        return $this->appointmentList;
    }

    public function addAppointment(Appointment $appointment) {
        $this->appointmentList[] = $appointment;
    }
}
?>
