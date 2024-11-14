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



$readyMealItem = new DonateReadyMeal();
$readyMealItem-> createReadyMealItems("AYHAGA", 15.0, NULL, NULL, "healthy", "Bowl");
$ready_meal = $readyMealItem->getReadyMealItemsInstance(1);

echo $ready_meal->getItemDetails();




$donateMeal = new DonateMeal();
$donateMeal-> createMealItems( "yarab",40, NULL, NULL,"any","any","any");
$donateMeal = $donateMeal->getDonationItemInstance(2);
echo $donateMeal->getItemDetails();


$rawMaterialItem = new DonateRawMaterials();
$rawMaterialItem->createRawMaterialItems("rice", 40, NULL, NULL, "food", 3, "jjd");
$rawMaterialItem = $rawMaterialItem->getRawMaterialItemsInstance(3);
echo $rawMaterialItem->getItemDetails();


?>