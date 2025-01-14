<?php
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         '../Model/',
//         '../Controller/',
//         '../View/',
//         '../interfaces/',
//     ];
//     foreach ($directories as $directory) {
//         $file = __DIR__ . '/' . $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
// });

// class SignUpMethod {
    
//     public function signUp(array $userData): UserEntity {
//         // Validate required fields
//         if (empty($userData['name']) || empty($userData['email']) || empty($userData['password'])) {
//             throw new Exception("Name, email, and password are required.");
//         }

//         // Hash the password (optional, depending on your security requirements)
//         $hashedPassword = md5($userData['password']); // You can use a stronger hashing algorithm like password_hash()


//         // Prepare the user data for insertion
//         $userData['password'] = $hashedPassword;

//         // Determine the user type and create the appropriate user object
//         switch ($userData['role'] ?? 'basic_donator') {
//             case 'employee':
//                 {
//                 $userData['role'] = $userData['role_title'];
//                 return Employee::storeObject($userData);
//                 }
//             case 'admin':{
//                 unset($userData['role']);
//                 return Admin::storeObject($userData);
//             }
                
//             case 'basic_donator':
//             default:
//             {
//                 unset($userData['role']);
//                 return BasicDonator::storeObject($userData);
//             }
                
//         }
//     }
// }

// // try {
// //     // Example: Register a new employee
// //     $employeeData = [
// //         'name' => 'Alice Johnson',
// //         'email' => 'alice.johnson@example.com',
// //         'phone' => '+9876543210',
// //         'password' => 'securepassword123',
// //         'role' => 'employee', // Specify the role as 'employee'
// //         'department' => 'Logistics', // Additional fields for employees
// //         'role_title' => 'Driver', // Additional fields for employees
// //         'salary' => 7000
// //     ];

// //     // Create a SignUpMethod instance
// //     $signUpMethod = new SignUpMethod();

// //     // Register the employee
// //     $newEmployee = $signUpMethod->signUp($employeeData);
// // } catch (Exception $e) {
// //     // Handle errors
// //     echo "Error: " . $e->getMessage();
// // }


// try {
//     // Example: Register a new basic donator
//     $userData = [
//         'name' => 'John Doe',
//         'email' => 'john.doe@example.com',
//         'phone' => '+1234567890',
//         'password' => 'password123',
//         'role' => 'basic_donator', // Optional, defaults to 'basic_donator'
//     ];

//     $signUpMethod = new SignUpMethod();
//     $newUser = $signUpMethod->signUp($userData);

//     echo "User registered successfully! User ID: " . $newUser->getId();
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
?>