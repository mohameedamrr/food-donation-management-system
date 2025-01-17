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
        if (!isset($_SESSION['employee'])) {
            header('Location: ../View/LoginView.html');
            exit();
        }
        $this->employee = $_SESSION['employee'];
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
        header('Location: ../View/employee_dashboard.php');
        //$appointment->updateStatus($status);
    }

    public function addNoteToAppointment($appointmentID, $note) {
        $appointment = new Appointment($appointmentID);
        $this->employee->addNoteToAppointment($appointment,$note);
        header('Location: ../View/employee_dashboard.php');
    }

    public function convertAppointmentsToDictionary($appointments) {
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

    public function getAppointmentsByStatus($status) {
        $appointmentsData = [];

        switch ($status) {
            case 'Scheduled':
            case 'Completed':
            case 'Cancelled':
            case 'Postponed':
                $appointmentsData = $this->convertAppointmentsToDictionary($_SESSION['employee']->getAppointmentByStatus($status));
                break;
            case 'Upcoming':
                $appointmentsData = $this->convertAppointmentsToDictionary($_SESSION['employee']->getUpcomingAppointments());
                break;
            case 'Past':
                $appointmentsData = $this->convertAppointmentsToDictionary($_SESSION['employee']->getPastAppointments());
                break;
            case 'All':
            default:
            $appointmentsData = $this->getAllAppointmentsData();
                break;
        }
        return $appointmentsData;
    }

}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EmployeesDashboardController();

    if (isset($_POST['filterStatus'])) {
        $filterStatus = $_POST['filterStatus'];
        $_SESSION['filterStatus'] = $filterStatus;
    }

    if (isset($_POST['add_note'])) {
        $appointmentID = $_POST['appointmentID'];
        $note = $_POST['note'];
        $controller->addNoteToAppointment($appointmentID, $note);
    }

    if (isset($_POST['status'])) {
        $appointmentID = $_POST['appointmentID'];
        $status = $_POST['status'];
        $controller->updateAppointmentStatus($appointmentID, $status);
    }
    header('Location: ../View/employee_dashboard.php');
        exit();
}
?>