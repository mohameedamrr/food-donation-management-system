<?php
class DonationItemFactory extends DonationFactory {

    protected function createDonationItem(String $type, array $details): DonationItem {
        if($type == "Vegetarian Meal") {
            $meal = new Meal(1);
            $meal->setMealType($details["mealType"]);
            $meal->setMealQuantity($details["mealQuantity"]);
            $meal->setExpiration($details["expiration"]);
            $meal->setIngredients($details["ingredients"]);
            return $meal;
        }
        else if($type == "Chicken Meal") {
            $meal = new Meal(2);
            $meal->setMealType($details["mealType"]);
            $meal->setMealQuantity($details["mealQuantity"]);
            $meal->setExpiration($details["expiration"]);
            $meal->setIngredients($details["ingredients"]);
            return $meal;
        }
        else if($type == "Raw Materials") {
            $rawMaterials = new RawMaterials(3);
            $rawMaterials->setMaterialType($details["materialType"]);
            $rawMaterials->setQuantity($details["quantity"]);
            $rawMaterials->setRawMaterialWeight($details["rawMaterialWeight"]);
            $rawMaterials->setSupplier($details["supplier"]);
            return $rawMaterials;
        }
        else if($type == "Client Ready Meal") {
            $clientReadyMeal = new ClientReadyMeal(4);
            $clientReadyMeal->setReadyMealType($details["readyMealType"]);
            $clientReadyMeal->setReadyMealExpiration($details["readyMealExpiration"]);
            $clientReadyMeal->setPackagingType($details["packagingType"]);
            $clientReadyMeal->setReadyMealQuantity($details["readyMealQuantity"]);
            return $clientReadyMeal;
        }
        else if($type == "Money") {
            $money = new Money(5);
            $money->setAmount($details["amount"]);
            $money->setDonationPurpose($details["donationPurpose"]);
            return $money;
        }
        else if($type == "Sheep Sacrifice") {
            $sacrifice = new Sacrifice(6);
            $sacrifice->setAnimalType($details["animalType"]);
            $sacrifice->setWeight($details["weight"]);
            return $sacrifice;
        }
        else if($type == "Cow Sacrifice") {
            $sacrifice = new Sacrifice(7);
            $sacrifice->setAnimalType($details["animalType"]);
            $sacrifice->setWeight($details["weight"]);
            return $sacrifice;
        }
        else if($type == "Goat Sacrifice") {
            $sacrifice = new Sacrifice(8);
            $sacrifice->setAnimalType($details["animalType"]);
            $sacrifice->setWeight($details["weight"]);
            return $sacrifice;
        }
        else if($type == "Box") {
            $box = new BasicBox(9);
            $box->setInitialBoxSize($details["boxSize"]);
            $box->setInitialItemList($details["initialItemList"]);
            $box->addItem($details["initialItemList"]);
            $box->calculateCost();
            return $box;
        }
    }
}
?>
