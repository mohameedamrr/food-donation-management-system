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
if(!isset($_SESSION))
{
    session_start();
}

class CartController {
    private $cart;
    private $donate;
    private $cartDict = [];

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart'];
        $this->donate = $_SESSION['donate'];
    }

    public function getCartData(){
        if (empty($this->cart)){
            return [];
        }
        else{
            foreach ($this->cart as $item){
                if ($item instanceof Meal) {
                    $this->cartDict [] = [
                        "type" => "Meal",
                        "name"=> $item->getItemName(),
                        "mealQuantity"=> $item->getMealQuantity(),
                        "mealCost"=> $item->getMealQuantity()*$item->getCost()
                    ];
                } elseif ($item instanceof RawMaterials) {
                    $this->cartDict [] = [
                        "type" => "RawMaterials",
                        "name"=> $item->getItemName(),
                        "materialType" => $item->getMaterialType(),
                        "materialQuantity"=> $item->getQuantity(),
                        "materialWeight"=> $item->getRawMaterialWeight(),
                        "supplier"=>$item->getSupplier(),
                    ];
                } elseif ($item instanceof Money) {
                    $this->cartDict [] = [
                        "type" => "Money",
                        "name"=> $item->getItemName(),
                        "amount"=> $item->getAmount(),
                        "purpose"=> $item->getDonationPurpose()
                    ];
                } elseif ($item instanceof Sacrifice) {
                    $this->cartDict [] = [
                        "type" => "Sacrifice",
                        "name"=> $item->getItemName(),
                        "animalType"=> $item->getAnimalType(),
                        "weight"=> $item->getWeight(),
                        "cost"=> $item->getCost()*$item->getWeight(),
                    ];
                } elseif ($item instanceof ClientReadyMeal) {
                    $this->cartDict [] = [
                        "type" => "ClientReadyMeal",
                        "name"=> $item->getItemName(),
                        "readyMealType"=>$item->getReadyMealType(),
                        "expiration"=> $item->getReadyMealExpiration(),
                        "packagingType"=> $item->getPackagingType(),
                        "mealQuantity"=> $item->getReadyMealQuantity(),
                    ];
                }
            }
            return $this->cartDict;

        }
    }

    public function removeItem($index) {
        if (isset($this->cart[$index])) {
            array_splice($this->cart, $index, 1);
        }
    }

    public function getCartItems() {
        return $this->cart;
    }

    public function clearCart() {
        $this->cart = [];
    }

    public function proceedDonation() {
        if (empty($this->cart)) {
            return false;
        }
        $this->donate->proceedDonation($this->cart, $_SESSION['paymentMethod']);
        if ($this->donate->getDonationState() instanceof DonateFailedState) {
            header('Location: ../View/failedPayment.html');
            exit();
        } else {
            $this->donate->proceedDonation($this->cart, $_SESSION['paymentMethod']);
            $this->donate->proceedDonation($this->cart, $_SESSION['paymentMethod']);
            $this->donate->proceedDonation($this->cart, $_SESSION['paymentMethod']);
        }


        $this->clearCart();

        return true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();

    if (isset($_POST['remove_item'])) {
        $index = $_POST['item_index'];
        $controller->removeItem($index);
    }

    if (isset($_POST['donate']) && isset($_POST['paymentType'])) {
        $type = $_POST['paymentType'];
        if ($type == 'Card') {
            $cardHolderName = $_POST['cardHolderName'];
            $cardNumber = $_POST['cardNumber'];
            $expiryDate = $_POST['expiryDate'];
            $date = new DateTime($expiryDate);
            $_SESSION['paymentMethod'] = new Card($cardHolderName, $cardNumber, $date);
        } else if ($type == 'InstaPay') {
            $instapayAddress = $_POST['instapayAddress'];
            $_SESSION['paymentMethod'] = new InstaPay($instapayAddress);
        } else if ($type == 'Paypal') {
            $_SESSION['paymentMethod'] = new PaymentGatewayAdapter();
        } else {
            $_SESSION['paymentMethod'] = new Cash();
        }
        if ($controller->proceedDonation()) {
            header('Location: donation_success.php');
            exit();
        } else {
            header('Location: donation_failed.php');
            exit();
        }
    }

    header('Location: ../View/Checkout.html');
    exit();
}
?>