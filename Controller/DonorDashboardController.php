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
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart'];
    }

    public function addToCart($item) {
        $this->cart[] = $item;
    }

    public function getCartItemCount() {
        return count($this->cart);
    }

    public function getCartItems() {
        return $this->cart;
    }

    public function setCartItem($index, $item) {
        $this->cart[$index] = $item;
    }

    public function clearCart() {
        $this->cart = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DonorDashboardController();
    $factory = new DonationItemFactory();

    if (isset($_POST['add_meal'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof Meal){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $meal = [
            'mealQuantity' => $_POST['mealQuantity'],
        ];
        $item = $factory->createAndValidate($_POST['mealType'], $meal);
        if($item) {
            $controller->addToCart($item);
        }
    }

    if (isset($_POST['add_raw_materials'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof RawMaterials){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $rawMaterials = [
            'materialType' => $_POST['materialType'],
            'quantity' => $_POST['quantity'],
            'rawMaterialWeight' => $_POST['rawMaterialWeight'],
            'supplier' => $_POST['supplier']
        ];
        $item = $factory->createAndValidate("Raw Materials", $rawMaterials);
        if($item) {
            $controller->addToCart($item);
        }
    }

    if (isset($_POST['add_money'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof Money){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $money = [
            'amount' => $_POST['amount'],
            'donationPurpose' => $_POST['donationPurpose']
        ];
        $item = $factory->createAndValidate("Money", $money);
        if($item) {
            $controller->addToCart($item);
        }
    }

    if (isset($_POST['add_sacrifice'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof Sacrifice){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $sacrifice = [];
        $item = $factory->createAndValidate($_POST['animalType'], $sacrifice);
        if($item) {
            $controller->addToCart($item);
        }
    }

    if (isset($_POST['add_ready_meal'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof ClientReadyMeal){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $readymeal = [
            'readyMealType' => $_POST['readyMealType'],
            'readyMealExpiration' => $_POST['readyMealExpiration'],
            'packagingType' => $_POST['packagingType'],
            'readyMealQuantity' => $_POST['readyMealQuantity']
        ];
        $item = $factory->createAndValidate("Client Ready Meal", $readymeal);
        if($item) {
            $controller->addToCart($item);
        }
    }

    if (isset($_POST['add_box'])) {
        foreach($controller->getCartItems() as $item){
            if($item instanceof Box){
                header('Location: ../View/donor_dashboard.html');
                exit();
            }
        }
        $box = [
            'intialItemList'=> [],
        ];
        $item = $factory->createAndValidate("Box", $box);
        $controller->addToCart($item);
    }

    if (isset($_POST['additional_oil'])) {
        $cartItems = $controller->getCartItems();
        for($i = 0; $i < $controller->getCartItemCount(); $i++) {
            if ($cartItems[$i] instanceof Box) {
                $newItem = new BoxAdditionalOil($cartItems[$i], (int)$_POST['oilQuantity']);
                $newItem->addItem('Oil Bottle');
                $newItem->calculateCost();
                $controller->setCartItem($i, $newItem);
            }
        }
    }

    if (isset($_POST['additional_pasta'])) {
        $cartItems = $controller->getCartItems();
        for($i = 0; $i < $controller->getCartItemCount(); $i++) {
            if ($cartItems[$i] instanceof Box) {
                $newItem = new BoxAdditionalOil($cartItems[$i], (int)$_POST['pastaQuantity']);
                $newItem->addItem('Pasta');
                $newItem->calculateCost();
                $controller->setCartItem($i, $newItem);
            }
        }
    }

    if (isset($_POST['additional_rice'])) {
        $cartItems = $controller->getCartItems();
        for($i = 0; $i < $controller->getCartItemCount(); $i++) {
            if ($cartItems[$i] instanceof Box) {
                $newItem = new BoxAdditionalOil($cartItems[$i], (int)$_POST['riceQuantity']);
                $newItem->addItem('Rice');
                $newItem->calculateCost();
                $controller->setCartItem($i, $newItem);
            }
        }
    }

    if (isset($_POST['proceed'])) {
        $donate = $user->makeDonation($controller->getCartItems());
        $_SESSION['donate'] = $donate;
        header('Location: ../View/Checkout.php');
        exit();
    }

    header('Location: ../View/donor_dashboard.html');
    exit();
}
?>
