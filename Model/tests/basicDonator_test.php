<?php

require_once '../BasicDonator.php';
require_once '../DonationDetails.php';
require_once '../IteratorDP/DonationHistoryIterator.php';
require_once '../Appointment.php';

// Test 1: Create a BasicDonator object
echo "Test 1: Creating a BasicDonator object...\n";
$donator = new BasicDonator('bertha.wilkinson@example.com');
//echo "BasicDonator created with email: " . $donator->getPhone() . "\n";
//print_r($donator->getDonationHistory());
echo "--------------------------\n";

print_r($donator->getAppointments());

$donator->deleteAppointment(12);

//// Test 2: Update the BasicDonator object
echo "Test 2: Updating the BasicDonator object...\n";
$updateData = [
    'name' => 'Bertha Wilkinson Updated',
    'phone' => '1234567888'
];
$donator->updateObject($updateData);
echo "Updated BasicDonator name: " . $donator->getName() . "\n";
echo "Updated BasicDonator phone: " . $donator->getPhone() . "\n";
echo "--------------------------\n";

//// Test 3: Create an appointment
echo "Test 3: Creating an appointment...\n";
$appointmentCreated = $donator->createAppointment('New Location', '2024-12-25 10:00:00');
if ($appointmentCreated) {
    echo "Appointment created successfully.\n";
} else {
    echo "Failed to create appointment.\n";
}
echo "--------------------------\n";
print_r($donator->getAppointments());
//// Test 4: Delete an appointment
echo "Test 4: Deleting an appointment...\n";
/// Assuming the last created appointment ID is 1 (you may need to fetch this dynamically in a real scenario)
$appointmentID = 14; // Replace with a valid appointment ID
$appointmentDeleted = $donator->deleteAppointment($appointmentID);
if ($appointmentDeleted) {
    echo "Appointment deleted successfully.\n";
} else {
    echo "Failed to delete appointment.\n";
}
echo "--------------------------\n";
print_r($donator->getAppointments());

// Test 5: Add a donation to the history
echo "Test 5: Adding a donation to the history...\n";
$donationDetails = new DonationDetails(1); // Replace with a valid donation ID
print_r($donationDetails->getDonationItems()) ;
$donator->addToHistory($donationDetails);
print_r($donator->getDonationHistory());
echo "Donation added to history.\n";
echo "--------------------------\n";

// Test 6: Create an iterator for the donation history
echo "Test 6: Creating an iterator for the donation history...\n";
$iterator = $donator->createIterator();
while ($iterator->hasNext()) {
    $donation = $iterator->next();
    echo "Donation ID: " . $donation->getId() . "\n";
    echo "Total Cost: " . $donation->getTotalCost() . "\n";
    echo "Description: " . $donation->getDescription() . "\n";
    print_r($donation->getDonationItems());
    echo "--------------------------\n";
}
echo "--------------------------\n";

//// Test 7: Delete the BasicDonator object
//echo "Test 7: Deleting the BasicDonator object...\n";
//$donatorID = $donator->getId();
//BasicDonator::deleteObject($donatorID);
//echo "BasicDonator with ID $donatorID deleted.\n";
//echo "--------------------------\n";
