<?php
// controllers/DonationController.php
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

class DonationController {
    private $donationView;
    private $donationManager;

    public function __construct() {
        $this->donationView = new DonationView();
        $this->donationManager = DonationManager::getInstance();
    }

    public function displayDonationForm($errorMessage = '') {
        include __DIR__ . '/../View/donation_form.php';
    }

    public function processDonation($donationData, $user) {
        if ($user) {
            if ($donationData['type'] === 'money') {
                $this->createMonetaryDonation($donationData, $user);
            } elseif ($donationData['type'] === 'sacrifice') {
                $this->createSacrificeDonation($donationData, $user);
            }
        } else {
            header('Location: index.php?action=login');
        }
    }

    public function createMonetaryDonation($donationData, $user) {
        $donation = new DonateMoneyItem(
            rand(1000, 9999),
            new DateTime(),
            $user,
            $donationData['currency'],
            $donationData['amount'],
            $donationData['donationPurpose']
        );
        $this->donationManager->createDonation($donation);
        $this->donationView->displayDonationSuccess($donation);
    }

    public function createSacrificeDonation($donationData, $user) {
        $donation = new DonateSacrificeItem(
            rand(1000, 9999),
            new DateTime(),
            $user,
            $donationData['currency'],
            $donationData['cost'],
            $donationData['animalType'],
            $donationData['location']
        );
        $this->donationManager->createDonation($donation);
        $this->donationView->displayDonationSuccess($donation);
    }

    public function viewDonationHistory($user) {
        if ($user) {
            $donations = $this->donationManager->getDonationByID($user->getId());
            $this->donationView->displayDonationHistory($donations);
        } else {
            header('Location: index.php?action=login');
        }
    }

    public function updateDonationDescription($donationID, $description, $employee) {
        $donation = $this->donationManager->getDonationByID($donationID);
        if ($donation) {
            $donation->setDescription($description);
            $this->donationManager->updateDonation($donation);
            $this->donationView->displayDonationUpdateSuccess($donationID);
        } else {
            // Handle error
        }
    }

    public function editDonationCost($donationID, $cost, $admin) {
        $donation = $this->donationManager->getDonationByID($donationID);
        if ($donation) {
            if ($donation instanceof DonateSacrificeItem) {
                $donation->setCost($cost);
                $this->donationManager->updateDonation($donation);
                $this->donationView->displayDonationCostUpdateSuccess($donationID, $cost);
            } else {
                // Handle error
            }
        } else {
            // Handle error
        }
    }
}
?>