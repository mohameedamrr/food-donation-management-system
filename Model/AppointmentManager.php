<?php
// classes/AppointmentManager.php
require_once 'Appointment.php';

class AppointmentManager {
    private static $instance = null;
    private $appointments; // List of Appointment objects

    private function __construct() {
        $this->appointments = array();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new AppointmentManager();
        }
        return self::$instance;
    }

    public function createAppointment(Appointment $appointment) {
        $this->appointments[$appointment->getAppointmentID()] = $appointment;
        return true;
    }

    public function getAppointmentByID($id) {
        if (isset($this->appointments[$id])) {
            return $this->appointments[$id];
        }
        return null;
    }

    public function updateAppointmentStatus($id, $status) {
        if (isset($this->appointments[$id])) {
            $this->appointments[$id]->updateStatus($status);
            return true;
        }
        return false;
    }

    public function assignEmployee($appointmentID, Employee $employee) {
        if (isset($this->appointments[$appointmentID])) {
            $this->appointments[$appointmentID]->assignEmployee($employee);
            return true;
        }
        return false;
    }

    public function getAllAppointments() {
        return $this->appointments;
    }
}
?>
