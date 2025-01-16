<?php
spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
        '../interfaces/',
        '../Model/AdapterDP/',
        '../Model/CommandDP/',
        '../Model/DecoratorDP/',
        '../Model/DonateStateDP/',
        '../Model/DonationItemChildren/',
        '../Model/FacadeDP/',
        '../Model/FactoryMethodDP/',
        '../Model/IteratorDP/',
        '../Model/PaymentStateDP/',
        '../Model/PaymentStrategyDP/',
        '../Model/ProxyDP/',
        '../Model/TemplateDP/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

session_start();

// Retrieve the user object from the session
$user = $_SESSION['user'];

class DonorDashboardController {
    private $cart;

    public function __construct() {
        // Initialize the cart in the session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart']; // Reference to the session cart
    }

    /**
     * Add a donation item to the cart.
     *
     * @param $item The donation item details.
     */
    public function addToCart($item) {
        $this->cart[] = $item;
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int The number of items in the cart.
     */
    public function getCartItemCount() {
        return count($this->cart);
    }

    /**
     * Get the cart items.
     *
     * @return array The cart items.
     */
    public function getCartItems() {
        return $this->cart;
    }

    /**
     * Clear the cart.
     */
    public function clearCart() {
        $this->cart = [];
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DonorDashboardController();
    $factory = new DonationItemFactory();

    if (isset($_POST['add_meal'])) {
        $meal = [
            'mealQuantity' => $_POST['mealQuantity'],
        ];
        $item = $factory->createAndValidate($_POST['mealType'], $meal);
        $controller->addToCart($item);
    }

    if (isset($_POST['add_raw_materials'])) {
        $rawMaterials = [
            'materialType' => $_POST['materialType'],
            'quantity' => $_POST['quantity'],
            'rawMaterialWeight' => $_POST['rawMaterialWeight'],
            'supplier' => $_POST['supplier']
        ];
        $item = $factory->createAndValidate("Raw Materials", $rawMaterials);
        $controller->addToCart($item);
    }

    if (isset($_POST['add_money'])) {
        $money = [
            'amount' => $_POST['amount'],
            'donationPurpose' => $_POST['donationPurpose']
        ];
        $item = $factory->createAndValidate("Money", $money);
        $controller->addToCart($item);
    }

    if (isset($_POST['add_sacrifice'])) {
        $sacrifice = [];
        $item = $factory->createAndValidate($_POST['animalType'], $sacrifice);
        $controller->addToCart($item);
    }

    if (isset($_POST['add_ready_meal'])) {
        $readymeal = [
            'readyMealType' => $_POST['readyMealType'],
            'readyMealExpiration' => $_POST['readyMealExpiration'],
            'packagingType' => $_POST['packagingType'],
            'readyMealQuantity' => $_POST['readyMealQuantity']
        ];
        $item = $factory->createAndValidate("Client Ready Meal", $readymeal);
        $controller->addToCart($item);
    }

    // if (isset($_POST['box'])) {
    //     $box = [
    //         'type' => 'Box',
    //         'intialItemList'=> '',
    //         'weight' => $_POST['weight']
    //     ];
    //     $item = $factory->createAndValidate($_POST['animalType'], $sacrifice);
    //     $controller->addToCart($item);
    // }

    // Redirect back to the dashboard to avoid form resubmission
    header('Location: ../View/donor_dashboard.html');
    exit();
}
?>