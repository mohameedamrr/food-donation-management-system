<?php
// AdminTest.php

// require_once '../Admin.php'; // Include the Admin class

// // Helper function to display test results
// //function displayTestResult(string $testName, bool $passed): void {
// //echo $testName . ": \n";
// //echo $passed ? "PASSED\n" : "FAILED\n";
// //}

// // Test storeObject with valid data
// try {
// $data = [
// 'name' => 'John Doe',
// 'email' => 'john.doe@example.com',
// 'phone' => '1234567890',
// 'password' => 'password123'
// ];
// $result = Admin::storeObject($data); // Assuming storeObject is in Admin class
// displayTestResult("storeObject with valid data", $result instanceof Admin);
// } catch (Exception $e) {
// displayTestResult("storeObject with valid data", false);
// echo "Error: " . $e->getMessage() . "\n";
// }

// // Test storeObject with missing data (invalid data)
// try {
// $data = [
// 'name' => '', // Empty name to simulate error
// 'email' => 'invalid@example.com',
// 'phone' => '1234567890',
// 'password' => 'password123'
// ];
// $result = Admin::storeObject($data); // Should throw an error
// displayTestResult("storeObject with missing data", false);
// } catch (Exception $e) {
// displayTestResult("storeObject with missing data", true);
// echo "Error: " . $e->getMessage() . "\n";
// }

// // Test updateObject with valid data
// try {
// $data = [
// 'id' => 1, // Assuming the ID 1 exists in the users table
// 'name' => 'John Updated',
// 'email' => 'john.updated@example.com',
// 'phone' => '0987654321'
// ];
// $result->updateObject($data); // Should succeed
// displayTestResult("updateObject with valid data", $result);
// } catch (Exception $e) {
// displayTestResult("updateObject with valid data", false);
// echo "Error: " . $e->getMessage() . "\n";
// }

// // Test updateObject with non-existent ID
// try {
// $data = [
// 'id' => 9999, // Assuming ID 9999 doesn't exist
// 'name' => 'Non-existent Admin',
// 'email' => 'nonexistent@example.com',
// 'phone' => '1122334455'
// ];
// $result->updateObject($data); // Should throw an error
// displayTestResult("updateObject with non-existent ID", false);
// } catch (Exception $e) {
// displayTestResult("updateObject with non-existent ID", true);
// echo "Error: " . $e->getMessage() . "\n";
// }

// // Test deleteObject with valid ID
// try {
// $id = 1; // Assuming the ID 1 exists in the users table
// $result = Admin::deleteObject($id); // Should succeed
// displayTestResult("deleteObject with valid ID", $result);
// } catch (Exception $e) {
// displayTestResult("deleteObject with valid ID", false);
// echo "Error: " . $e->getMessage() . "\n";
// }

// // Test deleteObject with non-existent ID
// try {
// $id = 9999; // Assuming ID 9999 doesn't exist
// $result = Admin::deleteObject($id); // Should throw an error
// displayTestResult("deleteObject with non-existent ID", false);
// } catch (Exception $e) {
// displayTestResult("deleteObject with non-existent ID", true);
// echo "Error: " . $e->getMessage() . "\n";
// }




// Include necessary files
require_once '../Admin.php';
require_once '../Employee.php';
require_once '../Appointment.php';

// Create an Admin object
echo "\nCreating Admin...\n";
$admin = new Admin(1); // Assuming admin with ID 1 exists in the database
echo "\nAdmin Name: " . $admin->getName() . "\n";
echo "Admin Email: " . $admin->getEmail() . "\n";
echo "Admin Phone: " . $admin->getPhone() . "\n";

// Test Admin functionalities
echo "\nTesting Admin functionalities...\n";

// Create a new user
// echo "Creating a new user...\n";
// $newUser = $admin->createUser([
//     'name' => 'Test User',
//     'email' => 'testuser@example.com',
//     'phone' => '+123456789',
//     'password' => 'password123'
// ]);
// echo "New User ID: " . $newUser->getId() . "\n";

// Create a new employee
echo "\nCreating a new employee...\n";
$newEmployee = $admin->createEmployee([
    'name' => 'Test Employee',
    'email' => 't979@example.com',
    'phone' => '+987654321',
    'password' => 'password123',
    'role' => 'Tester',
    'department' => 'QA',
    'salary' => 50000
]);
echo "New Employee ID: " . $newEmployee->getId() . "\n";
echo "New Employee Name: " . $newEmployee->getName() . "\n";

// Assign the Admin to the Employee (so the Employee can observe changes)
echo "\nAssigning Admin to Employee...\n";
$newEmployee->setAdmin($admin);

// Create a new appointment
echo "\nCreating a new appointment...\n";
$date = new DateTime();
$newAppointment = Appointment::storeObject([
    'status' => 'pending',
    'date' => $date->format('Y-m-d'),
    'employeeAssignedID' => null,
    'location' => 'Test Location'
]);
echo "New Appointment ID: " . $newAppointment->getAppointmentID() . "\n";

// Add the appointment to the Admin's list
echo "Adding appointment to Admin's list...\n";
$admin->addAppointment($newAppointment);

// Assign the appointment to the Employee
echo "Assigning appointment to Employee...\n";
$admin->assignAppointment($newAppointment, $newEmployee->getId());

// Test Observer pattern
echo "\nTesting Observer pattern...\n";

// Check if the Employee's appointment list is updated
echo "Employee's assigned appointments:\n";
$assignedAppointments = $newEmployee->getAssignedAppointments();
foreach ($assignedAppointments as $appointment) {
    echo "Appointment ID: " . $appointment->getAppointmentID() . ", Status: " . $appointment->getStatus() . "\n";
}

// Change the status of the appointment and notify observers
echo "\nChanging appointment status to 'ongoing'...\n";
$newAppointment->updateStatus('ongoing');

// Check if the Employee's appointment list reflects the status change
echo "Employee's assigned appointments after status change:\n";
foreach ($assignedAppointments as $appointment) {
    echo "Appointment ID: " . $appointment->getAppointmentID() . ", Status: " . $appointment->getStatus() . "\n";
}

// Remove the appointment and notify observers
echo "\nRemoving appointment from Admin's list...\n";
$admin->removeAppointment($newAppointment->getAppointmentID());

// Check if the Employee's appointment list is updated after removal
echo "Employee's assigned appointments after removal:\n";
$assignedAppointments = $newEmployee->getAssignedAppointments();
if (empty($assignedAppointments)) {
    echo "No appointments assigned.\n";
} else {
    foreach ($assignedAppointments as $appointment) {
        echo "Appointment ID: " . $appointment->getAppointmentID() . ", Status: " . $appointment->getStatus() . "\n";
    }
}

$employees = $admin->getAllEmployees();
foreach ($employees as $employee) {
    echo "Employee: " . $employee->getName() ."salary: " . $employee->getSalary() . " id: " . $employee->getId() . "\n";
}

echo "\nTesting complete.\n";
?>