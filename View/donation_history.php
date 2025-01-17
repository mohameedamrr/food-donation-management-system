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
// if (!isset($_SESSION['user'])) {
//     header('Location: LoginView.html');
//     exit();
// }
$user = $_SESSION['user'];
$user->setDonationHistory();
$_SESSION['user'] = $user;
$controller = new DonationHistoryController();
$donationHistory = $controller->getDonationHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section h3 {
            color: #28a745;
            margin-bottom: 15px;
        }

        .donation {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .donation h4 {
            margin: 0 0 10px;
            color: #333;
        }

        .donation p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Donation History</h1>

        <?php
        echo "<h2>Hello " . $_SESSION['user']->getName() . ",</h2>";
        ?>

        <div class="section">
            <h3>Your Donation History</h3>
            <div id="donationHistoryList">
                <?php
                if (empty($donationHistory)) {
                    echo '<p>No donation history found.</p>';
                } else {
                    foreach ($donationHistory as $donation) {
                        echo '<div class="donation">';
                        echo '<h4>Donation ID: ' . $donation['id'] . '</h4>';
                        echo '<p>Total Cost: ' . $donation['totalCost'] . '</p>';
                        //echo '<p>Description: ' . $donation['description'] . '</p>';
                        echo '<p>Donation Items:</p>';
                        echo '<ul>';
                        foreach ($donation['donationItems'] as $item) {
                            echo '<li>' . $item->getItemName() . ' : ' . $item->getCost() . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>