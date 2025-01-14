<?php
// AdminTest.php

require_once '../Admin.php'; // Include the Admin class

// Helper function to display test results
//function displayTestResult(string $testName, bool $passed): void {
//echo $testName . ": \n";
//echo $passed ? "PASSED\n" : "FAILED\n";
//}

// Test storeObject with valid data
try {
$data = [
'name' => 'John Doe',
'email' => 'john.doe@example.com',
'phone' => '1234567890',
'password' => 'password123'
];
$result = Admin::storeObject($data); // Assuming storeObject is in Admin class
displayTestResult("storeObject with valid data", $result instanceof Admin);
} catch (Exception $e) {
displayTestResult("storeObject with valid data", false);
echo "Error: " . $e->getMessage() . "\n";
}

// Test storeObject with missing data (invalid data)
try {
$data = [
'name' => '', // Empty name to simulate error
'email' => 'invalid@example.com',
'phone' => '1234567890',
'password' => 'password123'
];
$result = Admin::storeObject($data); // Should throw an error
displayTestResult("storeObject with missing data", false);
} catch (Exception $e) {
displayTestResult("storeObject with missing data", true);
echo "Error: " . $e->getMessage() . "\n";
}

// Test updateObject with valid data
try {
$data = [
'id' => 1, // Assuming the ID 1 exists in the users table
'name' => 'John Updated',
'email' => 'john.updated@example.com',
'phone' => '0987654321'
];
$result = Admin::updateObject($data); // Should succeed
displayTestResult("updateObject with valid data", $result);
} catch (Exception $e) {
displayTestResult("updateObject with valid data", false);
echo "Error: " . $e->getMessage() . "\n";
}

// Test updateObject with non-existent ID
try {
$data = [
'id' => 9999, // Assuming ID 9999 doesn't exist
'name' => 'Non-existent Admin',
'email' => 'nonexistent@example.com',
'phone' => '1122334455'
];
$result = Admin::updateObject($data); // Should throw an error
displayTestResult("updateObject with non-existent ID", false);
} catch (Exception $e) {
displayTestResult("updateObject with non-existent ID", true);
echo "Error: " . $e->getMessage() . "\n";
}

// Test deleteObject with valid ID
try {
$id = 1; // Assuming the ID 1 exists in the users table
$result = Admin::deleteObject($id); // Should succeed
displayTestResult("deleteObject with valid ID", $result);
} catch (Exception $e) {
displayTestResult("deleteObject with valid ID", false);
echo "Error: " . $e->getMessage() . "\n";
}

// Test deleteObject with non-existent ID
try {
$id = 9999; // Assuming ID 9999 doesn't exist
$result = Admin::deleteObject($id); // Should throw an error
displayTestResult("deleteObject with non-existent ID", false);
} catch (Exception $e) {
displayTestResult("deleteObject with non-existent ID", true);
echo "Error: " . $e->getMessage() . "\n";
}
