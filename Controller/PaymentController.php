<?php
// controllers/PaymentController.php

spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
        '../interfaces/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
class PaymentController {
    private $paymentView;

    public function __construct() {
        $this->paymentView = new PaymentView();
    }

    public function processPayment($donation, $paymentData) {
        $paymentSuccess = $this->handlePayment($donation, $paymentData);

        if ($paymentSuccess) {
            $this->paymentView->displayPaymentSuccess($donation);
        } else {
            $this->paymentView->displayPaymentFailure();
        }
    }

    private function handlePayment($donation, $paymentData) {
        // Payment processing logic
        // For demonstration purposes, we'll assume all payments succeed
        return true;
    }
}
?>