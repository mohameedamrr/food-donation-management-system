<?php
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         '../Model/',
//         '../Controller/',
//         '../View/',
//         '../interfaces/',
//     ];
//     foreach ($directories as $directory) {
//         $file = __DIR__ . '/' . $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
// });
require_once '../../interfaces/IPayment.php';
require_once 'ThirdPartyPaymentGateway.php';
class PaymentGatewayAdapter implements IPayment {

    private $paymentGateway; 
     public function __construct() {
        $this->paymentGateway = new ThirdPartyPaymentGateway();
    }

    public function pay(float $cost): bool{
        $currency = 'EGP';
        $isPaymentSuccessful = $this->paymentGateway->processPayment($cost, $currency);
        return $isPaymentSuccessful;
    }
}
// $paymentGateway = new PaymentGatewayAdapter();
// echo $paymentGateway->pay(900) ? 'true' : 'false';
?>