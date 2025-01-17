<?php
spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
        '../interfaces/',
        '../Model/AdapterDP/',
        '../Model/CommandDP/',
        '../Model/DecoratorDP/',
        '../Model/DonateStateDP/',
        '../Model/DonationItemChildren/',
        '../Model/FacadeDP/',
        '../Model/FactoryMethodDP/',
        '../Model/IteratorDP/',
        '../Model/PaymentStateDP/',
        '../Model/PaymentStrategyDP/',
        '../Model/ProxyDP/',
        '../Model/TemplateDP/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
if(!isset($_SESSION)){
    session_start();
}


class EmployeesDashboardController {
    private $employee;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ../View/LoginView.html');
            exit();
        }
        $this->employee = $_SESSION['user'];
    }

    public function getAssignedAppointments() {
        return $this->employee->getAssignedAppointments();
    }

    public function getAllAppointmentsData() {
        $appointments = $this->employee->getAssignedAppointments();
        $appointmentsData = [];

        foreach ($appointments as $appointment) {
            $appointmentsData[] = [
                'appointmentID' => $appointment->getAppointmentID(),
                'date' => $appointment->getDate(),
                'userID' => $appointment->getUserId(),
                'status' => $appointment->getStatus(),
                'location' => $appointment->getAppointmentLocation(),
                'note' => $appointment->getNote(),
            ];
        }

        return $appointmentsData;
    }

    public function updateAppointmentStatus($appointmentID, $status) {
        $appointment = new Appointment($appointmentID);
        $this->employee->changeAppointmentStatus($appointment, $status);
        //$appointment->updateStatus($status);
    }

    public function addNoteToAppointment($appointmentID, $note) {
        $appointment = new Appointment($appointmentID);
        $this->employee->addNoteToAppointment($appointment,$note);
        header('Location: ../View/employee_dashboard.php');
       
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EmployeesDashboardController();

    if (isset($_POST['complete_appointment'])) {
        $appointmentID = $_POST['appointmentID'];
        $controller->updateAppointmentStatus($appointmentID, 'Completed');
    }

    if (isset($_POST['add_note'])) {
        $appointmentID = $_POST['appointmentID'];
        $note = $_POST['note'];
        $controller->addNoteToAppointment($appointmentID, $note);
    }

    header('Location: ../View/employee_dashboard.php');
    exit();
}
?>