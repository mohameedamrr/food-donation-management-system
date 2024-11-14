<?php

require ("DonationItem.php");
require_once 'DatabaseManager.php';
require_once 'DonationItem.php';
require_once 'DonateRawMaterials.php';
require_once 'DonateMeal.php';
require_once 'DonateReadyMeal.php';
// $donationItem = new DonationItem();

// $donationItem->getDonationItemInstance(1);
// echo $donationItem->getItemDetails();

$rawMaterialItem = new DonateRawMaterials();

$readyMealItem = new DonateReadyMeal();
$readyMealItem-> createReadyMealItems("AYHAGA", 15.0, NULL, NULL, "healthy", "Bowl");
$readyMealItem->getReadyMealItemsInstance(2);

echo $readyMealItem->getItemDetails();

// $donateMeal = new DonateMeal();
// $donateMeal-> createMealItems( "yarab",0, NULL, NULL,"","","");


?>