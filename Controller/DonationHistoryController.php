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

class DonationHistoryController {
    private $admin;

    public function __construct() {
        if (!isset($_SESSION['admin'])) {
            header('Location: ../View/LoginView.html');
            exit();
        }
        $this->admin = $_SESSION['admin'];
    }

    public function getDonationHistory() {
        $db = new DatabaseManagerProxy('admin');
        $sql = "SELECT * FROM `food_donation`.`donation_history`";
        $result = $db->run_select_query($sql)->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function updateDonationDescription($donationId, $newDescription) {
        $db = new DatabaseManagerProxy('admin');
        $sql = "SELECT * FROM `food_donation`.`donation_history` WHERE id = $donationId";
        $row = $db->run_select_query($sql)->fetch_assoc();

        if ($row) {
            $donationDetails = new DonationDetails($row['id']);
            $previousDescription = $row['description'];

            // Create and execute the command
            $command = new ChangeDonationDescriptionCommand($donationDetails, $newDescription, $previousDescription);
            $this->admin->addToCommandsHistory($command);
            $this->admin->executeCommand();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DonationHistoryController();

    if (isset($_POST['update_description'])) {
        $donationId = $_POST['donation_id'];
        $newDescription = $_POST['new_description'];
        $controller->updateDonationDescription($donationId, $newDescription);
    }

    header('Location: ../View/donation_history.php');
    exit();
}
?>