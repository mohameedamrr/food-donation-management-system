<?php
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         '../Controller/',
//         '../View/',
//         '../../interfaces/',
//         '../AdapterDP/',
//         '../CommandDP/',
//         '../DecoratorDP/',
//         '../DonateStateDP/',
//         '../DonationItemChildren/',
//         '../FacadeDP/',
//         '../FactoryMethodDP/',
//         '../IteratorDP/',
//         '../PaymentStateDP/',
//         '../PaymentStrategyDP/',
//         '../ProxyDP/',
//         '../TemplateDP/',
//         '../',
//     ];
//     foreach ($directories as $directory) {
//         $file = __DIR__ . '/' . $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
// });


// // Step 1: Create a BasicDonator
// $donator = new BasicDonator('floppyfire52@gmail.com');

// // Step 2: Create a list of donation items
// $factory = new DonationItemFactory();

// $vegMealDetails = [
//         'mealQuantity' => 10,
// ];
// $meal = $factory->createAndValidate("Vegetarian Meal", $vegMealDetails);

// $rawMaterialsDetails = [
//     'materialType'     => 'Grain',
//     'quantity'         => 100,
//     'rawMaterialWeight'=> 50.5,
//     'supplier'         => 'Local Farm'
// ];

// $rawMaterials = $factory->createAndValidate("Raw Materials", $rawMaterialsDetails);

// echo $donator->getEmail();
// $donationItems = [
//     $meal,
//     $rawMaterials,
// ];

// // Step 3: Create a Cash payment method
// $paymentMethod = new Cash();

// // Step 4: Create a Donate object
// $donate = $donator->makeDonation($donationItems, $paymentMethod);

// echo "Initial State(Billing)" . get_class($donate->getDonationState()) . "\n";

// // Step 5: Start the donation process (initial state is TotalCostState)
// $donate->proceedDonation($donationItems, $paymentMethod);

// // Step 6: Verify the state transitions
// echo "(Create DD)" . get_class($donate->getDonationState()) . "\n";

// // Proceed to BillingState
// $donate->proceedDonation($donationItems, $paymentMethod);
// echo "(Mail)" . get_class($donate->getDonationState()) . "\n";

// // Proceed to CreateDonationDetailsState
// $donate->proceedDonation($donationItems, $paymentMethod);
// echo "(Completed)" . get_class($donate->getDonationState()) . "\n";

?>