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


class AdminDashboardController {
    private $admin;

    public function __construct() {
        if (!isset($_SESSION['admin'])) {
            header('Location: ../View/LoginView.html');
            exit();
        }
        $this->admin = $_SESSION['admin'];
    }

    public function getAllAppointmentsData() {
        $appointments = $this->admin->getAppointmentsList();
        $appointmentsData = [];

        foreach ($appointments as $appointment) {
            $appointmentsData[] = [
                'appointmentID' => $appointment->getAppointmentID(),
                'date' => $appointment->getDate(),
                'userID' => $appointment->getUserId(),
                'status' => $appointment->getStatus(),
                'location' => $appointment->getAppointmentLocation(),
                'note' => $appointment->getNote(),
                'employeeAssignedID' => $appointment->getEmployeeAssignedID(),
            ];
        }
        return $appointmentsData;
    }

    public function getEmployeesData() {
        $employees = $this->admin->getAllEmployees();
        $employeesData = [];

        foreach ($employees as $employee) {
            $employeesData[] = [
                'name' => $employee->getName(),
                'id' => $employee->getId(),
                'email' => $employee->getEmail(),
                'phone' => $employee->getPhone(),
                'role' => $employee->getRole(),
                'department' => $employee->getDepartment(),
                'salary' => $employee->getSalary(),
            ];
        }
        return $employeesData;
    }

    public function saveEmployee($data) {
        $employees = $this->admin->createEmployee($data);
    }

    public function deleteEmployee($id) {
        $this->admin->deleteEmployee($id);
        
    }

    public function updateAppointmentStatus($appointmentID, $status) {
        $appointment = new Appointment((int)$appointmentID);
        $changeStatusCommand = new ChangeAppointmentStatusCommand($appointment, $status, $appointment->getStatus());
        $this->admin->addToCommandsHistory($changeStatusCommand);
        $this->admin->executeCommand();
        $appointments = $this->admin->getAppointmentsList();
        foreach ($appointments as $appointment) {
            if($appointment->getAppointmentID() == $appointmentID) {
                $appointment->updateStatus($status);
            }
        }
        $this->admin->setAppointmentsList($appointments);
        error_log($appointments[0]->getAppointmentID());
        error_log($appointments[0]->getStatus());
    }

    public function addNoteToAppointment($appointmentID, $note) {
        $appointment = new Appointment($appointmentID);
        $prevNote = $appointment->getNote();
        $appointments = $this->admin->getAppointmentsList();
        $changeNoteCommand = new ChangeAppointmentNoteCommand($appointment, $note, $prevNote);
        $this->admin->addToCommandsHistory($changeNoteCommand);
        $this->admin->executeCommand();
        foreach ($appointments as $appointment) {
            if($appointment->getAppointmentID() == $appointmentID) {
                $appointment->setNote($note);
            }
        }
        $this->admin->setAppointmentsList($appointments);
    }

    public function undoCommand() {
        $this->admin->undoCommand();
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

    public function assignAppointment($appointmentID, $employeeID) {
        $appointment = new Appointment($appointmentID);
        $this->admin->addAppointment($appointment);
        $this->admin->assignAppointment($appointment, $employeeID);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AdminDashboardController();

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

    if (isset($_POST['undo'])) {
        $controller->undoCommand();
    }

    if (isset($_POST['assign_employee'])) {
        $appointmentID = $_POST['appointmentID'];
        $employeeID = $_POST['employeeID'];
        $controller->assignAppointment($appointmentID, $employeeID);
    }

    if (isset($_POST['employees'])) {
        header('Location: ../View/employess.php');
        exit();
    }

    if (isset($_POST['donation_history'])) {
        header('Location: ../View/donation_history_dashboard.php');
        exit();
    }

    if (isset($_POST['saveEmployee'])) {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'password' => $_POST['password'],
            'role' => $_POST['role'],
            'department' => $_POST['department'],
            'salary' => $_POST['salary']
        ];
        $controller->saveEmployee($data);
        header('Location: ../View/admin_employees_dashboard.php');
        exit();
    }

    if (isset($_POST['delete_employee'])) {
        $controller->deleteEmployee($_POST['employeeID']);
        header('Location: ../View/admin_employees_dashboard.php');
        exit();
    }

    header('Location: ../View/admin_dashboard.php');
    exit();
}
?>