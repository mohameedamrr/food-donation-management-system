<?php
// Include the necessary files
require 'ReportTemplate.php';
require 'DonationReport.php';

// Create a DonationReport object
$donationReport = new DonationReport();

// Generate the report (this will automatically save it to a text file)
$reportContent = $donationReport->generateReport();

// Output the report content (optional)
echo $reportContent;
?>