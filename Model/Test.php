<?php
// Include all required classes and interfaces

// ... (require_once statements) ...

// User login
$hashedPassword = password_hash('user_password', PASSWORD_DEFAULT);
$loginMethod = new NormalMethod($hashedPassword);
$user = new BasicUser(1, 'John Doe', 'john@example.com', '1234567890', $hashedPassword, $loginMethod);

if ($user->login('john@example.com', 'user_password')) {
    echo "User logged in successfully.\n";
} else {
    echo "User login failed.\n";
}

// Admin setup
$adminLoginMethod = new NormalMethod(password_hash('admin_password', PASSWORD_DEFAULT));
$admin = new Admin(3, 'Admin User', 'admin@example.com', '5555555555', password_hash('admin_password', PASSWORD_DEFAULT), $adminLoginMethod, 'Administrator', array('manage_users', 'manage_appointments'));

// Create a donation
$donationPurpose = 'Support Education';
$donateMoney = new DonateMoney(1, new DateTime(), $user, 'USD', 100.0, $donationPurpose);

$donationManager = DonationManager::getInstance();
$donationManager->createDonation($donateMoney);

$admin->editDonationCost(1, 150.0);

$updatedDonation = $donationManager->getDonationByID(1);
echo "Updated Donation Cost: " . $updatedDonation->calculateCost() . "\n";

// Process payment
$bill = new Bill($donateMoney->calculateCost());
$paymentMethod = new Card('John Doe', '1234567890123456');
$bill->setPaymentStrategy($paymentMethod);

if ($bill->executePayment($donateMoney)) {
    echo "Payment successful.\n";
    echo $donateMoney->getReceipt() . "\n";
} else {
    echo "Payment failed.\n";
}

// Create an appointment
$location = new Location(1, 'Main Office', null, '123 Charity St.', '10001');
$appointment = new Appointment(1, new DateTime('2023-12-01'), new DateTime('10:00:00'), $location);

$appointmentManager = AppointmentManager::getInstance();
$appointmentManager->createAppointment($appointment);

// Assign employee to appointment
$employeeLoginMethod = new NormalMethod(password_hash('employee_password', PASSWORD_DEFAULT));
$employee = new Employee(2, 'Jane Smith', 'jane@example.com', '0987654321', password_hash('employee_password', PASSWORD_DEFAULT), $employeeLoginMethod, 'Field Worker', 'Logistics');

$admin->assignAppointment(1, $employee);

echo "Appointment assigned to employee.\n";

$assignedAppointments = $employee->viewAssignedAppointments();
foreach ($assignedAppointments as $appt) {
    echo "Assigned Appointment ID: " . $appt->getAppointmentID() . "\n";
}
?>
