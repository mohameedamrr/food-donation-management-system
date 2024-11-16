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



// $readyMealItem = new DonateReadyMeal(6);
// // $readyMealItem-> createReadyMealItems("AYHAGA", 15.0, NULL, NULL, "healthy", "Bowl");
// $readyMealItem->setItemName('test');
// $ready_meal = $readyMealItem->getReadyMealItemsInstance();



$donateMeal = new DonateMeal(55);
$donateMeal-> createMealItems( "yarab",40, NULL, NULL,"any","any","any");
$donateMeal = $donateMeal->getDonationItemInstance(2);
echo $donateMeal->getItemDetails(). "\n";
$donateMeal->setItemName("anything");
echo $donateMeal->getItemDetails(). "\n";

//echo $ready_meal->getItemDetails();






$rawMaterialItem = new DonateRawMaterials(88);
$rawMaterialItem->createRawMaterialItems("rice", 40, NULL, NULL, "food", 3, "jjd");
$rawMaterialItem = $rawMaterialItem->getRawMaterialItemsInstance(3);
echo $rawMaterialItem->getItemDetails(). "\n";
$rawMaterialItem->setWeight(50);
echo $rawMaterialItem->getItemDetails(). "\n";




?>