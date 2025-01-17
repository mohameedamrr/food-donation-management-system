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

class CartController {
    private $cart;

    public function __construct() {
        // Initialize the cart from the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart'];
    }

    /**
     * Remove an item from the cart by its index.
     *
     * @param int $index The index of the item to remove.
     */
    public function removeItem($index) {
        if (isset($this->cart[$index])) {
            array_splice($this->cart, $index, 1);
        }
    }

    /**
     * Get all items in the cart.
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

    /**
     * Start the donation process.
     */
    public function startDonation() {
        if (empty($this->cart)) {
            return false; // No items to donate
        }
        $paymentMethod = new Cash();
        // Create a new Donate object and proceed with the donation
        $donate = $_SESSION['user']->makeDonation($this->cart, $paymentMethod);
        // $paymentMethod = new Cash(); // Default payment method (can be changed based on user selection)
        $donate->proceedDonation($this->cart, $paymentMethod);
        $donate->proceedDonation($this->cart, $paymentMethod);
        $donate->proceedDonation($this->cart, $paymentMethod);

        // Clear the cart after donation
        $this->clearCart();

        return true;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();

    if (isset($_POST['remove_item'])) {
        $index = $_POST['item_index'];
        $controller->removeItem($index);
    }

    if (isset($_POST['donate'])) {
        if ($controller->startDonation()) {
            header('Location: donation_success.php'); // Redirect to success page
            exit();
        } else {
            header('Location: donation_failed.php'); // Redirect to failure page
            exit();
        }
    }

    // Redirect back to the cart page to avoid form resubmission
    header('Location: ../View/Checkout.php');
    exit();
}
?>