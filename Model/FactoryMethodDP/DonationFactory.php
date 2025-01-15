<?php
require_once '../../interfaces/IStoreObject.php';
require_once '../../interfaces/IUpdateObject.php';
require_once '../../interfaces/IDeleteObject.php';
require_once '../../interfaces/IReadObject.php';
require_once '../ProxyDP/DatabaseManagerProxy.php';
require_once "DonationItemFactory.php";
require_once '../DonationItemChildren/Meal.php';            
require_once '../DonationItemChildren/RawMaterials.php';    
require_once '../DonationItemChildren/ClientReadyMeal.php'; 
require_once '../DonationItemChildren/Money.php';           
require_once '../DonationItemChildren/Sacrifice.php';       
require_once '../DecoratorDP/BasicBox.php';

abstract class DonationFactory {

    public function createAndValidate(String $type, array $details): DonationItem | bool {
        $donationItem = $this->createDonationItem($type, $details);
        if($donationItem == false) {
            return false;
        }
        if($donationItem->validate()) {
            return $donationItem;
        } else {
            return false;
        }
    }

    protected abstract function createDonationItem(String $type, array $details): DonationItem | bool;

}
// 2. Create the factory
// $factory = new DonationItemFactory();
// function createAndPrintResult(DonationItemFactory $factory, string $type, array $details) {
//     $item = $factory->createAndValidate($type, $details);
//     if ($item !== false) {
//         echo "Success: Created a(n) '$type' object.\n";
//     } else {
//         echo "Failed: '$type' did not return a valid object (got false).\n";
//     }
//     return $item;
// }

// // 3. Test each type

// // 3.1 Vegetarian Meal
// $vegMealDetails = [
//     'mealType'     => 'Vegetarian',
//     'mealQuantity' => 10,
//     'expiration'   => new DateTime('+2 days'),
//     'ingredients'  => ['Tomato', 'Lettuce', 'Cheese']
// ];
// createAndPrintResult($factory, "Vegetarian Meal", $vegMealDetails);

// // 3.2 Chicken Meal
// $chickenMealDetails = [
//     'mealType'     => 'Chicken',
//     'mealQuantity' => 5,
//     'expiration'   => new DateTime('+1 week'),
//     'ingredients'  => ['Chicken', 'Spices', 'Oil']
// ];
// createAndPrintResult($factory, "Chicken Meal", $chickenMealDetails);

// // 3.3 Raw Materials
// $rawMaterialsDetails = [
//     'materialType'     => 'Grain',
//     'quantity'         => 100,
//     'rawMaterialWeight'=> 50.5,
//     'supplier'         => 'Local Farm'
// ];
// createAndPrintResult($factory, "Raw Materials", $rawMaterialsDetails);

// // 3.4 Client Ready Meal
// $clientReadyMealDetails = [
//     'readyMealType'      => 'Frozen Pizza',
//     'readyMealExpiration'=> new DateTime('+3 days'),
//     'packagingType'      => 'Plastic wrap',
//     'readyMealQuantity'  => 20
// ];
// createAndPrintResult($factory, "Client Ready Meal", $clientReadyMealDetails);

// // 3.5 Money
// $moneyDetails = [
//     'amount'          => 200.00,
//     'donationPurpose' => 'School Supplies'
// ];
// createAndPrintResult($factory, "Money", $moneyDetails);

// // 3.6 Sheep Sacrifice
// $sheepDetails = [
//     'animalType' => 'Sheep',
//     'weight'     => 45.0
// ];
// createAndPrintResult($factory, "Sheep Sacrifice", $sheepDetails);

// // 3.7 Cow Sacrifice
// $cowDetails = [
//     'animalType' => 'Cow',
//     'weight'     => 250.0
// ];
// createAndPrintResult($factory, "Cow Sacrifice", $cowDetails);

// // 3.8 Goat Sacrifice
// $goatDetails = [
//     'animalType' => 'Goat',
//     'weight'     => 30.5
// ];
// createAndPrintResult($factory, "Goat Sacrifice", $goatDetails);

// // 3.9 Box
// $boxDetails = [
//     'boxSize'         => 15.0,
//     'initialItemList' => ['Item1', 'Item2']
// ];
// createAndPrintResult($factory, "Box", $boxDetails);

// // 4. Test an invalid type (should return false)
// createAndPrintResult($factory, "Unknown Type", []);
// /**
//  * Helper function to handle the creation and print result.
//  */

?>
