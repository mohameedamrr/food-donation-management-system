<?php
// Ensure the correct paths for required files
require __DIR__ . '/ReportTemplate.php';
require __DIR__ . '/EmployeesReport.php';

// Create a DonationReport object
$donationReport = new EmployeesReport();

// Generate the report (this will automatically save it to a text file)
$donationReport->generateReport();
?>
