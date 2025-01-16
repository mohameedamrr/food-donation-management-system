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
// require_once '../Model/Authenticator.php';
// require_once '../Model/GoogleMethod.php';
// require_once '../Model/NormalMethod.php';
// require_once '../Model/ProxyDP/DatabaseManagerProxy.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];
    $loginMethod = $_POST['loginMethod'];

    // Initialize the appropriate login strategy
    if ($loginMethod === 'Google') {
        $authenticator = new Authenticator(new GoogleMethod());
    } else {
        $authenticator = new Authenticator(new NormalMethod());
    }

    // Attempt to authenticate the user
    $user = $authenticator->login($email, $password, $userType);

    if ($user) {
        // Redirect based on user type
        switch ($userType) {
            case 'Donator':
                echo "daret ya seya3";
                echo $user->getName();
                // header('Location: donator_dashboard.php');
                break;
            case 'Employee':
                header('Location: employee_dashboard.php');
                break;
            case 'Admin':
                header('Location: admin_dashboard.php');
                break;
        }
        exit();
    } else {
        // Redirect back to login page with an error message
        header('Location: login.php?error=invalid_credentials');
        exit();
    }
}
?>