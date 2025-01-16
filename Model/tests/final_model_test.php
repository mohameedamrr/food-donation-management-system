<?php
require_once '../../interfaces/IStoreObject.php';
require_once '../../interfaces/IUpdateObject.php';
require_once '../../interfaces/IDeleteObject.php';
require_once '../../interfaces/IReadObject.php';
require_once '../../interfaces/IPayment.php';
require_once '../Bill.php';
require_once '../PaymentStateDP/PaymentState.php';
require_once '../PaymentStateDP/InitialState.php';
require_once '../PaymentStateDP/CompletedState.php';
require_once '../PaymentStateDP/FailedState.php';
require_once '../PaymentStrategyDP/Cash.php';
require_once '../PaymentStrategyDP/Card.php';
require_once '../PaymentStrategyDP/InstaPay.php';
require_once '../ProxyDP/DatabaseManagerProxy.php';
require_once "../FactoryMethodDP/DonationFactory.php";
require_once "../FactoryMethodDP/DonationItemFactory.php";
require_once '../DonationItemChildren/Meal.php';            
require_once '../DonationItemChildren/RawMaterials.php';    
require_once '../DonationItemChildren/ClientReadyMeal.php'; 
require_once '../DonationItemChildren/Money.php';           
require_once '../DonationItemChildren/Sacrifice.php';       
require_once '../DecoratorDP/BasicBox.php';
require_once '../BasicDonator.php';
require_once '../PaymentStrategyDP/Cash.php';
require_once '../DonateStateDP/DonateState.php';
require_once '../DonateStateDP/TotalCostState.php';
require_once '../DonateStateDP/BillingState.php';
require_once '../DonateStateDP/CreateDonationDetailsState.php';
require_once '../DonateStateDP/SendMailState.php';
require_once '../DonateStateDP/DonateFailedState.php';
require_once '../DonateStateDP/DonateCompletedState.php';
require_once '../DonateStateDP/Donate.php';
require_once '../FacadeDP/phpmailerFacade.php';


// Step 1: Create a BasicDonator
$donator = new BasicDonator('ziadayman087@gmail.com');

// Step 2: Create a list of donation items
$factory = new DonationItemFactory();

$vegMealDetails = [
        'mealQuantity' => 10,
];
$meal = $factory->createAndValidate("Vegetarian Meal", $vegMealDetails);

$rawMaterialsDetails = [
    'materialType'     => 'Grain',
    'quantity'         => 100,
    'rawMaterialWeight'=> 50.5,
    'supplier'         => 'Local Farm'
];

$rawMaterials = $factory->createAndValidate("Raw Materials", $rawMaterialsDetails);

echo $donator->getEmail();
$donationItems = [
    $meal,
    $rawMaterials,
];

// Step 3: Create a Cash payment method
$paymentMethod = new Cash();

// Step 4: Create a Donate object
$donate = $donator->makeDonation($donationItems, $paymentMethod);

echo "Initial State(Billing)" . get_class($donate->getDonationState()) . "\n";

// Step 5: Start the donation process (initial state is TotalCostState)
$donate->proceedDonation($donationItems, $paymentMethod);

// Step 6: Verify the state transitions
echo "(Create DD)" . get_class($donate->getDonationState()) . "\n";

// Proceed to BillingState
$donate->proceedDonation($donationItems, $paymentMethod);
echo "(Mail)" . get_class($donate->getDonationState()) . "\n";

// Proceed to CreateDonationDetailsState
$donate->proceedDonation($donationItems, $paymentMethod);
echo "(Completed)" . get_class($donate->getDonationState()) . "\n";

?>