<?php
// Include necessary files
require_once '../Admin.php';
require_once '../Employee.php';
require_once '../Appointment.php';

// Create an Admin object (only for setup, not for testing)
echo "Creating Admin for setup...<br>";
$admin = new Admin(2); // Assuming admin with ID 2 exists in the database

// Create a new Appointment object
echo "Creating a new appointment...<br>";
$date = new DateTime();
$appointment = Appointment::storeObject([
    'status' => 'pending',
    'date' => $date->format('Y-m-d'),
    'employeeAssignedID' => null,
    'location' => 'Test Location'
]);
echo "Appointment ID: " . $appointment->getAppointmentID() . "<br>";
echo "Appointment Status: " . $appointment->getStatus() . "<br>";

// Print the admin's appointments list before any changes
echo "<br>Admin's appointments list before changes:<br>";
$adminAppointments = $admin->getAppointmentsList();
foreach ($adminAppointments as $adminAppointment) {
    echo "Appointment ID: " . $adminAppointment->getAppointmentID() . ", Status: " . $adminAppointment->getStatus() . "<br>";
}

// Create an Employee object for testing
echo "<br>Creating Employee for testing...<br>";
$employee = new Employee('testemployee@example.com'); // Assuming this email exists in the database
echo "Employee ID: " . $employee->getId() . "<br>";
echo "Employee Name: " . $employee->getName() . "<br>";
echo "Employee Email: " . $employee->getEmail() . "<br>";
echo "Employee Phone: " . $employee->getPhone() . "<br>";
echo "Employee salary: " . $employee->getSalary() . "<br>";

// Test setters and getters
echo "<br>Testing setters and getters...<br>";
$employee->setRole('Tester');
echo "Employee Role: " . $employee->getRole() . "<br>";

$employee->setDepartment('QA');
echo "Employee Department: " . $employee->getDepartment() . "<br>";

// Test updateObject
echo "<br>Testing updateObject...<br>";
$employee->updateObject([
    'role' => 'Senior Tester',
    'department' => 'Quality Assurance'
]);
$employee->setSalary(200000);
echo "Updated Employee Role: " . $employee->getRole() . "<br>";
echo "Updated Employee Department: " . $employee->getDepartment() . "<br>";
echo "Updated Employee Salary: " . $employee->getSalary() . "<br>";

// Assign the Admin to the Employee (for Observer pattern setup)
echo "<br>Assigning Admin to Employee...<br>";
$employee->setAdmin($admin);

// Add the appointment to the Admin's list and assign it to the Employee
echo "<br>Adding and assigning appointment to Employee...<br>";
$admin->addAppointment($appointment);
$admin->assignAppointment($appointment, $employee->getId());

// Print the admin's appointments list after adding the appointment
echo "<br>Admin's appointments list after adding appointment:<br>";
$adminAppointments = $admin->getAppointmentsList();
foreach ($adminAppointments as $adminAppointment) {
    echo "Appointment ID: " . $adminAppointment->getAppointmentID() . ", Status: " . $adminAppointment->getStatus() . "<br>";
}

// Test getAssignedAppointments
echo "<br>Testing getAssignedAppointments...<br>";
$assignedAppointments = $employee->getAssignedAppointments();
foreach ($assignedAppointments as $assignedAppointment) {
    echo "Assigned Appointment ID: " . $assignedAppointment->getAppointmentID() . ", Status: " . $assignedAppointment->getStatus() . "<br>";
}

// Test changeAppointmentStatus
echo "<br>Testing changeAppointmentStatus...<br>";
$employee->changeAppointmentStatus($appointment, 'cndsnvsjvn');
echo "Appointment Status after change: " . $appointment->getStatus() . "<br>";

// Test AppointmentDone
echo "<br>Testing AppointmentDone...<br>";
$employee->AppointmentDone($appointment->getAppointmentID());
echo "Appointment removed from Admin's list.<br>";

// Print the admin's appointments list after removing the appointment
echo "<br>Admin's appointments list after removal:<br>";
$adminAppointments = $admin->getAppointmentsList();
if (empty($adminAppointments)) {
    echo "No appointments in the admin's list.<br>";
} else {
    foreach ($adminAppointments as $adminAppointment) {
        echo "Appointment ID: " . $adminAppointment->getAppointmentID() . ", Status: " . $adminAppointment->getStatus() . "<br>";
    }
}

// Test getAssignedAppointments after removal
echo "<br>Testing getAssignedAppointments after removal...<br>";
$assignedAppointments = $employee->getAssignedAppointments();
if (empty($assignedAppointments)) {
    echo "No appointments assigned.<br>";
} else {
    foreach ($assignedAppointments as $assignedAppointment) {
        echo "Assigned Appointment ID: " . $assignedAppointment->getAppointmentID() . ", Status: " . $assignedAppointment->getStatus() . "<br>";
    }
}

echo "<br>Testing complete.<br>";
?>