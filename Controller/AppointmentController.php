<?php
// controllers/AppointmentController.php

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

class AppointmentController {
    private $appointmentView;
    private $appointmentManager;

    public function __construct() {
        $this->appointmentView = new AppointmentView();
        $this->appointmentManager = AppointmentManager::getInstance();
    }

    public function scheduleAppointment($appointmentData, UserEntity $user) {
        $location = new Location(
            $appointmentData['locationID'],
            $appointmentData['locationName'],
            null,
            $appointmentData['addressLine'],
            $appointmentData['postalCode']
        );
        $appointment = new Appointment(
            $appointmentData['appointmentID'],
            new DateTime($appointmentData['date']),
            new DateTime($appointmentData['time']),
            $location
        );
        $this->appointmentManager->createAppointment($appointment);
        $this->appointmentView->displayAppointmentScheduled($appointment);
    }

    public function assignEmployeeToAppointment($appointmentID, Employee $employee, Admin $admin) {
        $admin->assignAppointment($appointmentID, $employee);
        $this->appointmentView->displayEmployeeAssignment($appointmentID, $employee);
    }

    public function updateAppointmentStatus($appointmentID, $status, Employee $employee) {
        $employee->changeAppointmentStatus($appointmentID, $status);
        $this->appointmentView->displayAppointmentStatusUpdate($appointmentID, $status);
    }
    public function displayAppointmentForm($errorMessage = '') {
        // Include or output the appointment scheduling form
        include __DIR__ . '/../View/appointment_form.php';
    }

    public function viewAppointments($user) {
        // Fetch appointments for the user and display them
        // Example:
        $appointments = []; // Retrieve from AppointmentManager
        include __DIR__ . '/../View/appointments_list.php';
    }
}
?>
