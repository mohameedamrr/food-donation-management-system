<?php
// Include necessary files
require_once '../Admin.php';
require_once '../Employee.php';
require_once '../Appointment.php';
require_once '../DonationDetails.php';

// Create an Admin object (only for setup, not for testing)
echo "Creating Admin for setup...<br>";
$admin = new Admin(2); // Assuming admin with ID 2 exists in the database

// Create a new Appointment object
echo "Creating a new appointment...<br>";
$date = new DateTime();
$appointment = Appointment::storeObject([
    'status' => 'pending',
    'date' => '2025-11-18 13:00:00',
    'employeeAssignedID' => null,
    'location' => '707 Fir St, City J',
    'userID' => 3,
    'note' => 'bbbbbbbb'
]);
echo "Appointment ID: " . $appointment->getAppointmentID() . "<br>";
echo "Appointment Status: " . $appointment->getStatus() . "<br>";

// Create another appointment for testing
$pastDate = new DateTime('2022-01-01');
$pastAppointment = Appointment::storeObject([
    'status' => 'completed',
    'date' => '2024-11-18 13:00:00',
    'employeeAssignedID' => null,
    'location' => '707 Fir St, City J',
    'userID' => 3,
    'note' => 'aaaaaaaa'
]);
echo "Past Appointment ID: " . $pastAppointment->getAppointmentID() . "<br>";
echo "Past Appointment Status: " . $pastAppointment->getStatus() . "<br>";

// Create an Employee object for testing
echo "<br>Creating Employee for testing...<br>";
$employee = new Employee('7amada@belganzabeel.com'); // Assuming this email exists in the database
echo "Employee ID: " . $employee->getId() . "<br>";
echo "Employee Name: " . $employee->getName() . "<br>";
echo "Employee Email: " . $employee->getEmail() . "<br>";
echo "Employee Phone: " . $employee->getPhone() . "<br>";
echo "Employee salary: " . $employee->getSalary() . "<br>";

// Assign the Admin to the Employee (for Observer pattern setup)
echo "<br>Assigning Admin to Employee...<br>";
$employee->setAdmin($admin);

// Add the appointments to the Admin's list and assign them to the Employee
echo "<br>Adding and assigning appointments to Employee...<br>";
$admin->addAppointment($appointment);
$admin->addAppointment($pastAppointment);
$admin->assignAppointment($appointment, $employee->getId());
$admin->assignAppointment($pastAppointment, $employee->getId());

// Test getAppointmentByStatus
echo "<br>Testing getAppointmentByStatus...<br>";
$pendingAppointments = $employee->getAppointmentByStatus('pending');
foreach ($pendingAppointments as $pendingAppointment) {
    echo "Pending Appointment ID: " . $pendingAppointment->getAppointmentID() . ", Status: " . $pendingAppointment->getStatus() . "<br>";
}

echo "<br>Testing getAppointmentByStatus...<br>";
$pendingAppointments = $employee->getAppointmentByStatus('completed');
foreach ($pendingAppointments as $pendingAppointment) {
    echo "Cpmpleted Appointment ID: " . $pendingAppointment->getAppointmentID() . ", Status: " . $pendingAppointment->getStatus() . "<br>";
}

// Test getUpcomingAppointments
echo "<br>Testing getUpcomingAppointments...<br>";
$upcomingAppointments = $employee->getUpcomingAppointments();
foreach ($upcomingAppointments as $upcomingAppointment) {
    echo "Upcoming Appointment ID: " . $upcomingAppointment->getAppointmentID() . ", Date: " . $upcomingAppointment->getDate(). "<br>";
}

// Test getPastAppointments
echo "<br>Testing getPastAppointments...<br>";
$pastAppointments = $employee->getPastAppointments();
foreach ($pastAppointments as $pastAppointment) {
    echo "Past Appointment ID: " . $pastAppointment->getAppointmentID() . ", Date: " . $pastAppointment->getDate(). "<br>";
}

// Test addNoteToAppointment
echo "<br>Testing addNoteToAppointment...<br>";
$employee->addNoteToAppointment($appointment, 'This is a test note.');
echo "Appointment Note: " . $appointment->getNote() . "<br>";

// Test changeDonationDescription
echo "<br>Testing changeDonationDescription...<br>";
$donationDetails = new DonationDetails(1); // Assuming donation details with ID 1 exists in the database
$employee->changeDonationDescription($donationDetails, 'Updated donation description.');
echo "Donation Description: " . $donationDetails->getDescription() . "<br>";

echo "<br>Testing complete.<br>";
?>