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
        '../Model/tests/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
// SignupController.php
// require_once 'BasicDonator.php';

session_start();

class SignupController {
    /**
     * Handles the signup process.
     *
     * @param array $userData The user's data (name, email, phone, password, confirm_password).
     * @return void
     */
    public function handleSignup($userData) {
        // Validate input
        if ($userData['password'] !== $userData['confirm_password']) {
            $this->redirectToSignupPage('password_mismatch');
            return;
        }

        // Check if email already exists (optional, can be implemented in BasicDonator::storeObject)
        // For now, assume the storeObject function handles this.

        // Create the user
        try {
            $basicDonator = BasicDonator::storeObject([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => $userData['password'],
            ]);
            $_SESSION['user'] = $basicDonator;
            // Redirect to the user dashboard
            $this->redirectToDashboard();
        } catch (Exception $e) {
            // Handle errors (e.g., email already exists)
            $this->redirectToSignupPage('email_exists');
        }
    }

    /**
     * Redirects the user to their dashboard.
     *
     * @return void
     */
    private function redirectToDashboard() {
        $_SESSION['cart']= [];
        header('Location: ../View/donor_dashboard.html');
        exit();
    }

    /**
     * Redirects the user back to the signup page with an error message.
     *
     * @param string $error The error message to display.
     * @return void
     */
    private function redirectToSignupPage($error) {
        header('Location: signup.php?error=' . $error);
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password'],
    ];

    // Create an instance of SignupController and handle the signup
    $signupController = new SignupController();
    $signupController->handleSignup($userData);
}
?>