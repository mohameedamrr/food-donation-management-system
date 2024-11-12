<?php
// index.php
spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        'View/',
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
// Start the session
// Create instances of the controllers
$userController = new UserController();
$appointmentController = new AppointmentController();
$boxController = new BoxController();
//$businessController = new BusinessController();
$donationController = new DonationController();
$paymentController = new PaymentController();

// Simple routing mechanism
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        displayHomePage();
        break;

    // User actions
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->login($_POST['username'], $_POST['password']);
        } else {
            $userController->displayLoginForm();
        }
        break;

    case 'logout':
        $userController->logout();
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->register($_POST);
        } else {
            $userController->displayRegistrationForm();
        }
        break;

    case 'profile':
        $userController->viewProfile($_SESSION['user'] ?? null);
        break;

    case 'update_profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->updateProfile($_SESSION['user'], $_POST);
        } else {
            // Display profile update form (implement as needed)
        }
        break;

    // Appointment actions
    case 'schedule_appointment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentController->scheduleAppointment($_POST, $_SESSION['user'] ?? null);
        } else {
            $appointmentController->displayAppointmentForm();
        }
        break;

    case 'view_appointments':
        $appointmentController->viewAppointments($_SESSION['user'] ?? null);
        break;

    // Donation actions
    case 'make_donation':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donationController->processDonation($_POST, $_SESSION['user'] ?? null);
        } else {
            $donationController->displayDonationForm();
        }
        break;

    case 'view_donations':
        $donationController->viewDonationHistory($_SESSION['user'] ?? null);
        break;

    // Box actions
    case 'create_box':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boxController->createBox($_POST);
        } else {
            // Display box creation form (implement as needed)
        }
        break;

    // Business actions
    case 'add_location':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assuming $business is available (e.g., from session or context)
            $businessController->addLocation($_POST, $business);
        } else {
            // Display location addition form (implement as needed)
        }
        break;

    // Payment actions
    case 'process_payment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assuming $donation is available (e.g., from session or context)
            $paymentController->processPayment($donation, $_POST);
        } else {
            // Display payment form (implement as needed)
        }
        break;

    // Default case for undefined actions
    default:
        displayNotFoundPage();
        break;
}

// Function to display the home page
function displayHomePage() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Food Donation Management System</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; }
            header, footer { background-color: #f8f8f8; padding: 10px; }
            nav a { margin: 0 10px; text-decoration: none; color: #333; }
            main { padding: 20px; }
        </style>
    </head>
    <body>
        <header>
            <h1>Welcome to the Food Donation Management System</h1>
            <nav>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="?action=profile">Profile</a> |
                    <a href="?action=schedule_appointment">Schedule Appointment</a> |
                    <a href="?action=make_donation">Make Donation</a> |
                    <a href="?action=view_donations">View Donations</a> |
                    <a href="?action=logout">Logout</a>
                <?php else: ?>
                    <a href="?action=login">Login</a> |
                    <a href="?action=register">Register</a>
                <?php endif; ?>
            </nav>
        </header>

        <main>
            <h2>Our Mission</h2>
            <p>We connect donors with those in need to alleviate hunger and reduce food waste.</p>
            <p>Join us in making a difference today!</p>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Food Donation Management System</p>
        </footer>
    </body>
    </html>
    <?php
}

// Function to display a 404 Not Found page
function displayNotFoundPage() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Page Not Found</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; }
            header, footer { background-color: #f8f8f8; padding: 10px; }
            nav a { margin: 0 10px; text-decoration: none; color: #333; }
            main { padding: 20px; }
        </style>
    </head>
    <body>
        <header>
            <h1>404 - Page Not Found</h1>
            <nav>
                <a href="index.php">Home</a>
            </nav>
        </header>

        <main>
            <p>Sorry, the page you're looking for does not exist.</p>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Food Donation Management System</p>
        </footer>
    </body>
    </html>
    <?php
}
?>
