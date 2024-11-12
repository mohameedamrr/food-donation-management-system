<?php
// View/PaymentView.php
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
class PaymentView {

    public function displayPaymentSuccess(BillableDonate $donation) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment Successful</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
                ul { list-style-type: none; padding: 0; }
                li { margin-bottom: 5px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Payment Confirmation</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="view_donations.php">View Donations</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Payment Successful</h2>
                <p>Your payment has been processed successfully.</p>
                <p><strong>Donation Details:</strong></p>
                <ul>
                    <li><strong>Donation ID:</strong> <?php echo htmlspecialchars($donation->getDonationID()); ?></li>
                    <li><strong>Date:</strong> <?php echo htmlspecialchars($donation->getDonationDate()->format('Y-m-d H:i:s')); ?></li>
                    <li><strong>Amount:</strong> <?php echo htmlspecialchars($donation->calculateCost()); ?></li>
                    <!-- Include additional details as needed -->
                </ul>
                <p><a href="make_donation.php">Make Another Donation</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Payment Processing</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayPaymentFailure() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment Failed</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; color: red; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Payment Error</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="contact_support.php">Contact Support</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Payment Failed</h2>
                <p>We're sorry, but your payment could not be processed at this time. Please try again later or use a different payment method.</p>
                <p><a href="make_donation.php">Try Again</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Payment Processing</p>
            </footer>
        </body>
        </html>
        <?php
    }
}
?>