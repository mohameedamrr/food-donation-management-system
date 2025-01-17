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

if (!isset($_SESSION)) {
    session_start();
}

class DonationHistoryController {
    private $donor;

    public function __construct() {
        // if (!isset($_SESSION['donor'])) {
        //     header('Location: ../View/LoginView.html');
        //     exit();
        // }
        $this->donor = $_SESSION['user'];
    }

    public function getDonationHistory() {
        $donationHistory = $this->donor->getDonationHistory();
        $iterator = $this->donor->createIterator();
        $historyData = [];

        while ($iterator->hasNext()) {
            $donation = $iterator->next();
            $historyData[] = [
                'id' => $donation->getId(),
                'totalCost' => $donation->getTotalCost(),
                //'description' => $donation->getDescription(),
                'donationID' => $donation->getDonationID(),
                'donationItems' => $donation->getDonationItems(),
            ];
        }

        return $historyData;
    }
}
?>