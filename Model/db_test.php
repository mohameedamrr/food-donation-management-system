<?php
// Include necessary files
require_once 'DonateSacrificeItem.php';
require_once 'DatabaseManager.php';
require_once 'BillableDonate.php';
require_once 'BasicBox.php';
require_once 'BoxAdditionalOil.php';
require_once 'BoxAdditionalPasta.php';
require_once 'BoxAdditionalRice.php';

$basic_box = new BasicBox();
$basic_box ->executeDonation();

$boxAddOil = new BoxAdditionalOil($basic_box);
$boxAddOil->setNumBottles(3);
$boxAddOil->executeDonation();

$boxAddPasta = new BoxAdditionalPasta($boxAddOil);
$boxAddPasta->setNumPackets(6);
$boxAddPasta->executeDonation();

$boxAddRice = new BoxAdditionalRice($boxAddPasta);
$boxAddRice->setWeight(15);



// Test the donation execution
$success = $boxAddRice->executeDonation();

// Output result of the test
if ($success) {
    echo "Donation was successfully executed and stored in the database.";
} else {
    echo "Failed to execute the donation.";
}
?>
