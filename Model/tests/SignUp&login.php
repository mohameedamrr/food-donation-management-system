<?php
require_once '../Admin.php';
require_once '../Employee.php';
require_once '../Appointment.php';
require_once '../BasicDonator.php';
require_once '../NormalMethod.php';

// Create an Admin object
echo "<br>Creating Admin...<br>";
$admin = new Admin(1); // Assuming admin with ID 1 exists in the database
echo "<br>Admin Name: " . $admin->getName() . "<br>";
echo "Admin Email: " . $admin->getEmail() . "<br>";
echo "Admin Phone: " . $admin->getPhone() . "<br>";

// Test Admin functionalities
echo "<br>Testing Admin functionalities...<br>";

// Create a new user
// echo "Creating a new user...<br>";
// $newUser = $admin->createUser([
//     'name' => 'Test User',
//     'email' => 'testuser@example.com',
//     'phone' => '+123456789',
//     'password' => 'password123'
// ]);
// echo "New User ID: " . $newUser->getId() . "<br>";

// Create a new employee
// echo "<br>Creating a new employee...<br>";
// $newEmployee = $admin->createEmployee([
//     'name' => 'Test Employee',
//     'email' => 'testemployee48ee@example.com',
//     'phone' => '+987654456',
//     'password' => 'password123',
//     'role' => 'Driver',
//     'department' => 'Logistics',
//     'salary' => 6000
// ]);
// echo "New Employee ID: " . $newEmployee->getId() . "<br>";
// echo "New Employee Name: " . $newEmployee->getName() . "<br>";

$newEmployee = new Employee('testemployee48ee@example.com');
echo $newEmployee->login()?"Login Successfully":"Login Failed";

// $newDonor = BasicDonator::storeObject(
//     [
//         'name' => 'Test donator',
//         'email' => 'testDonator@example.com',
//         'phone' => '+123455479',
//         'password' => 'password123'
//     ]
//     );

//    echo $newDonor->login()?"Login Successfully":"Login Failed";

?>