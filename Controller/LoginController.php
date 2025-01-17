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

session_start(); // Start the session


class LoginController {
    private $authenticator;

    public function __construct() {
        // Initialize the Authenticator with a default login method (NormalMethod)
        $this->authenticator = new Authenticator(new NormalMethod());
    }

    /**
     * Handles the login process.
     *
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @param string $userType The type of user (Donator, Employee, Admin).
     * @param string $loginMethod The login method (Normal, Google).
     * @return void
     */
    public function handleLogin($email, $password, $userType, $loginMethod) {
        // Set the login strategy based on the selected method
        if ($loginMethod === 'Google') {
            $this->authenticator->setProvider(new GoogleMethod());
        } else {
            $this->authenticator->setProvider(new NormalMethod());
        }

        // Attempt to authenticate the user
        $user = $this->authenticator->login($email, $password, $userType);

        if ($user) {
            // Redirect based on user type
            $this->redirectUser($userType, $user);
        } else {
            // Redirect back to the login page with an error message
            $this->redirectToLoginPage('invalid_credentials');
        }
    }

    /**
     * Redirects the user to the appropriate dashboard based on their type.
     *
     * @param string $userType The type of user (Donator, Employee, Admin).
     * @return void
     */
    private function redirectUser($userType, $user) {
        $_SESSION['cart']= [];
        switch ($userType) {
            case 'Donator':
                $_SESSION['user'] = $user;
                header('Location: ../View/donor_dashboard.html');
                break;
            case 'Employee':
                $_SESSION['employee'] = $user;
                if (isset($_SESSION["admin"])) {
                    $user->setAdmin($_SESSION["admin"]);
                }
                header('Location: ../View/employee_dashboard.php');
                break;
            case 'Admin':
                $_SESSION['admin'] = $user;
                header('Location: ../View/admin_dashboard.php');
                break;
            default:
                // Default redirection if user type is invalid
                header('Location: ../View/LoginView.html?error=invalid_user_type');
                break;
        }
        exit();
    }

    /**
     * Redirects the user back to the login page with an error message.
     *
     * @param string $error The error message to display.
     * @return void
     */
    private function redirectToLoginPage($error) {
        header('Location: ../View/LoginView.html?error=' . $error);
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];
    $loginMethod = $_POST['loginMethod'];

    // Create an instance of LoginController and handle the login
    $loginController = new LoginController();
    $loginController->handleLogin($email, $password, $userType, $loginMethod);
}
?>