<?php
// View/DonationView.php
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
class DonationView {

    public function displayDonationSuccess($donation) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Donation Successful</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
                ul { list-style-type: none; padding: 0; }
                li { margin-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <header>
                <h1>Donation Management</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="view_donations.php">View Donations</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Donation Successful</h2>
                <p>Thank you for your generous donation!</p>
                <p><strong>Donation Details:</strong></p>
                <ul>
                    <li><strong>Donation ID:</strong> <?php echo htmlspecialchars($donation->getDonationID()); ?></li>
                    <li><strong>Date:</strong> <?php echo htmlspecialchars($donation->getDate()->format('Y-m-d H:i:s')); ?></li>
                    <li><strong>Donor:</strong> <?php echo htmlspecialchars($donation->getDonor()->getName()); ?></li>
                    <?php if ($donation instanceof DonateMoneyItem): ?>
                        <li><strong>Type:</strong> Monetary Donation</li>
                        <li><strong>Amount:</strong> <?php echo htmlspecialchars($donation->getCurrency()); ?> <?php echo htmlspecialchars($donation->getAmount()); ?></li>
                        <li><strong>Purpose:</strong> <?php echo htmlspecialchars($donation->getDonationPurpose()); ?></li>
                    <?php elseif ($donation instanceof DonateSacrificeItem): ?>
                        <li><strong>Type:</strong> Sacrifice Donation</li>
                        <li><strong>Cost:</strong> <?php echo htmlspecialchars($donation->getCurrency()); ?> <?php echo htmlspecialchars($donation->getCost()); ?></li>
                        <li><strong>Animal Type:</strong> <?php echo htmlspecialchars($donation->getAnimalType()); ?></li>
                        <li><strong>Location:</strong> <?php echo htmlspecialchars($donation->getLocation()); ?></li>
                    <?php endif; ?>
                </ul>
                <p><a href="make_donation.php">Make Another Donation</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Donation Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayDonationHistory($donations) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Your Donation History</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <header>
                <h1>Donation History</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Your Donation History</h2>
                <?php if (empty($donations)): ?>
                    <p>You have not made any donations yet.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>Donation ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount/Cost</th>
                            <th>Details</th>
                        </tr>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($donation->getDonationID()); ?></td>
                                <td><?php echo htmlspecialchars($donation->getDate()->format('Y-m-d H:i:s')); ?></td>
                                <?php if ($donation instanceof DonateMoneyItem): ?>
                                    <td>Monetary Donation</td>
                                    <td><?php echo htmlspecialchars($donation->getCurrency()); ?> <?php echo htmlspecialchars($donation->getAmount()); ?></td>
                                    <td>Purpose: <?php echo htmlspecialchars($donation->getDonationPurpose()); ?></td>
                                <?php elseif ($donation instanceof DonateSacrificeItem): ?>
                                    <td>Sacrifice Donation</td>
                                    <td><?php echo htmlspecialchars($donation->getCurrency()); ?> <?php echo htmlspecialchars($donation->getCost()); ?></td>
                                    <td>Animal: <?php echo htmlspecialchars($donation->getAnimalType()); ?>, Location: <?php echo htmlspecialchars($donation->getLocation()); ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </main>

            <footer>
                <p>&copy; 2024 Donation Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayDonationUpdateSuccess($donationID) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Donation Description Updated</title>
            <style>
                /* Add your CSS styles here */
            </style>
        </head>
        <body>
            <header>
                <h1>Donation Management</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="view_donations.php">View Donations</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Description Updated</h2>
                <p>The description for donation ID <?php echo htmlspecialchars($donationID); ?> has been updated successfully.</p>
                <p><a href="view_donations.php">Back to Donations</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Donation Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayDonationCostUpdateSuccess($donationID, $cost) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Donation Cost Updated</title>
            <style>
                /* Add your CSS styles here */
            </style>
        </head>
        <body>
            <header>
                <h1>Donation Management</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="view_donations.php">View Donations</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Cost Updated</h2>
                <p>The cost for donation ID <?php echo htmlspecialchars($donationID); ?> has been updated to <strong><?php echo htmlspecialchars($cost); ?></strong> successfully.</p>
                <p><a href="view_donations.php">Back to Donations</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Donation Management</p>
            </footer>
        </body>
        </html>
        <?php
    }
}
?>