<?php
// require_once '../../interfaces/IStoreObject.php';
// require_once '../../interfaces/IUpdateObject.php';
// require_once '../../interfaces/IDeleteObject.php';
// require_once '../../interfaces/IReadObject.php';
// require_once '../../interfaces/ICommand.php';
// require_once '../ProxyDP/DatabaseManagerProxy.php';
// require_once '../Appointment.php';
// require_once '../Employee.php';
// require_once '../Admin.php';
// require_once 'ChangeAppointmentNoteCommand.php';
// require_once 'ChangeDonationDescriptionCommand.php';

class ChangeAppointmentStatusCommand implements ICommand {
    private $employee;
    private $appointment;
    private $newStatus;
    private $previousStatus;

    public function __construct(Appointment $appointment,string $newStatus, string $previousStatus) {
        $this->appointment = $appointment;
        $this->newStatus = $newStatus;
        $this->previousStatus = $previousStatus;
        $donorProxy = new DatabaseManagerProxy('admin');
        $row = $donorProxy->run_select_query("SELECT * FROM users WHERE id = 1")->fetch_assoc();
        if(isset($row)) {
            $this->employee = new Employee($row['email']);
        }
    }
    public function execute(): void{
        $this->employee->changeAppointmentStatus($this->appointment, $this->newStatus);
    }
    public function undo(): void{
        $this->employee->changeAppointmentStatus($this->appointment, $this->previousStatus);
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    public function getAppointment()
    {
        return $this->appointment;
    }

    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
    }

    public function getNewStatus()
    {
        return $this->newStatus;
    }

    public function setNewStatus($newStatus)
    {
        $this->newStatus = $newStatus;

    }

    public function getPreviousStatus()
    {
        return $this->previousStatus;
    }

    public function setPreviousStatus($previousStatus)
    {
        $this->previousStatus = $previousStatus;

    }
}

// // Simulate an appointment
// $appointment = new Appointment(1);
// echo "Initial Appointment Status: " . $appointment->getStatus() . "\n";
// echo "Initial Appointment Note: " . $appointment->getNote() . "\n\n";

// // Simulate donation details
// $donationDetails = new DonationDetails(1);
// echo "Initial Donation Description: " . $donationDetails->getDescription() . "\n\n";

// // Create an Admin (invoker)
// $admin = new Admin(1); // Assuming Admin ID is 1

// // Create an Employee (receiver)
// $employee = new Employee("7amada@belganzabeel.com");

// // Create commands
// $changeNoteCommand = new ChangeAppointmentNoteCommand($appointment, "Updated Note", $appointment->getNote());
// $changeStatusCommand = new ChangeAppointmentStatusCommand($appointment, "Confirmed", $appointment->getStatus());
// $changeDescriptionCommand = new ChangeDonationDescriptionCommand($donationDetails, "Updated Description", $donationDetails->getDescription());



// echo "Executing commands...\n";
// $admin->addToCommandsHistory($changeNoteCommand);
// $admin->executeCommand(); // Execute ChangeAppointmentNoteCommand
// $admin->addToCommandsHistory($changeStatusCommand);
// $admin->executeCommand(); // Execute ChangeAppointmentStatusCommand
// $admin->addToCommandsHistory($changeDescriptionCommand);
// $admin->executeCommand(); // Execute ChangeDonationDescriptionCommand

// $appointment = new Appointment(1);
// $donationDetails = new DonationDetails(1);
// // Display results after execution
// echo "\nAfter Execution:\n";
// echo "Appointment Status: " . $appointment->getStatus() . "\n";
// echo "Appointment Note: " . $appointment->getNote() . "\n";
// echo "Donation Description: " . $donationDetails->getDescription() . "\n\n";

// // Undo the last command
// echo "Undoing last command...\n";
// $admin->undoCommand(); // Undo ChangeDonationDescriptionCommand

// $appointment = new Appointment(1);
// $donationDetails = new DonationDetails(1);
// // Display results after undo
// echo "\nAfter Undo:\n";
// echo "Appointment Status: " . $appointment->getStatus() . "\n";
// echo "Appointment Note: " . $appointment->getNote() . "\n";
// echo "Donation Description: " . $donationDetails->getDescription() . "\n\n";

// // Undo the second last command
// echo "Undoing second last command...\n";
// $admin->undoCommand(); // Undo ChangeAppointmentStatusCommand

// $appointment = new Appointment(1);
// $donationDetails = new DonationDetails(1);
// // Display results after second undo
// echo "\nAfter Second Undo:\n";
// echo "Appointment Status: " . $appointment->getStatus() . "\n";
// echo "Appointment Note: " . $appointment->getNote() . "\n";
// echo "Donation Description: " . $donationDetails->getDescription() . "\n\n";

// // Undo the third last command
// echo "Undoing third last command...\n";
// $admin->undoCommand(); // Undo ChangeAppointmentNoteCommand

// $appointment = new Appointment(1);
// $donationDetails = new DonationDetails(1);
// // Display results after third undo
// echo "\nAfter Third Undo:\n";
// echo "Appointment Status: " . $appointment->getStatus() . "\n";
// echo "Appointment Note: " . $appointment->getNote() . "\n";
// echo "Donation Description: " . $donationDetails->getDescription() . "\n\n";
?>