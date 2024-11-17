<?php

require_once "DatabaseManager.php";
require_once "DonationDetails.php";
require_once "Donate.php";
require_once "DonateMeal.php";
require_once "DonateRawMaterials.php";
require_once "DonateReadyMeal.php";

class TestDonations {
    public static function runTests() {
        echo "Running tests for donation system...\n";

        // Create donation items
        echo "Creating donation items...\n";

        $donateMeal = new DonateMeal(1);
        $donateMeal->createMealItems("Meal Package", 10, NULL, NULL, "Vegetarian", 5, ["Rice", "Vegetables"]);

        $donateRawMaterials = new DonateRawMaterials(2);
        $donateRawMaterials->createRawMaterialItems("Raw Material Package", 100, NULL, NULL, "Plastic", 50, "Supplier A");

        $donateReadyMeal = new DonateReadyMeal(3);
        $donateReadyMeal->createReadyMealItems("Ready Meal Package", 2, NULL, NULL, "Vegan", "Box");

        // Map donation items to quantities
        echo "Mapping donation items to quantities...\n";
        $donationItems = [
            1 => 5,         // 5 meal packages
            2 => 10, // 10 raw material packages
            3 => 3     // 3 ready meal packages
        ];

        // Create donation details
        echo "Testing DonationDetails...\n";
        $donationDetails = new DonationDetails();
        $donationDetails->setId(1);
        $donationDetails->setDescription("General donation");
        $donationDetails->setTotalCost(500.0);
        assert($donationDetails->getId() === 1, "Failed: DonationDetails ID");
        assert($donationDetails->getDescription() === "General donation", "Failed: DonationDetails Description");
        assert($donationDetails->getTotalCost() === 500.0, "Failed: DonationDetails TotalCost");


        
        // Create and test Donate class
        echo "Testing Donate class...\n";
        $donationDate = new DateTime('2024-01-01');
        $userId = 123;

        // Pass the map of donation items to the Donate class
        $donate = new Donate($donationDate, $userId, $donationItems);
        $donate->donate($donationItems); // Assuming this method processes the donation
        assert($donate->getDonationDate() == $donationDate, "Failed: Donation Date");
        assert($donate->getUserId() === $userId, "Failed: Donation User ID");

        $donationDetails->setDetails($donate);

        echo "All tests passed.\n";
    }
}

// Run the test suite
TestDonations::runTests();

?>
