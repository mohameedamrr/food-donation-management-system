<?php
session_start(); // Start the session at the top

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

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof Admin)) {
    header('Location: ../View/LoginView.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Employees Management</title>
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

        .section input[type="text"],
        .section input[type="email"],
        .section input[type="password"],
        .section input[type="number"],
        .section select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .section button {
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
        }

        .section button:hover {
            background-color: #218838;
        }

        .employee {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            position: relative;
        }

        .employee h4 {
            margin: 0 0 10px;
            color: #333;
        }

        .employee p {
            margin: 5px 0;
        }

        .add-employee-form {
            display: none;
            margin-top: 20px;
        }

        .add-employee-form.active {
            display: block;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Employees Management</h1>

        <?php
        echo "<h2> Hello " . $_SESSION['user']->getName() . ",</h2>";
        ?>

        <!-- Add Employee Button -->
        <button id="addEmployeeButton" onclick="toggleAddEmployeeForm()">Add Employee</button>

        <!-- Add Employee Form -->
        <div id="addEmployeeForm" class="add-employee-form">
            <h3>Add New Employee</h3>
            <form id="employeeForm" action="../Controller/AdminDashboardController.php" method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="role" placeholder="Role" required>
                <input type="text" name="department" placeholder="Department" required>
                <input type="number" name="salary" placeholder="Salary" required>
                <button type="submit">Save Employee</button>
            </form>
        </div>

        <!-- Employees List -->
        <div class="section">
            <h3>Employees List</h3>
            <div id="employeesList">
                <?php
                $controller = new AdminDashboardController();
                $employees = $controller->getEmployeesData();
                
                if (empty($employees)) {
                    echo '<p>No employees found.</p>';
                } else {
                    foreach ($employees as $employee) {
                        echo '<div class="employee">';
                        echo '<h4>Employee ID: ' . $employee['id'] . '</h4>';
                        echo '<p>Name: ' . $employee['name']  . '</p>';
                        echo '<p>Email: ' . $employee['email']  . '</p>';
                        echo '<p>Phone: ' . $employee['phone']  . '</p>';
                        echo '<p>Role: ' . $employee['role']  . '</p>';
                        echo '<p>Department: ' . $employee['department']  . '</p>';
                        echo '<p>Salary: ' . $employee['salary']  . '</p>';
                        echo '<button class="delete-button" onclick="deleteEmployee(' . $employee['id'] . ')">Delete</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle the visibility of the add employee form
        function toggleAddEmployeeForm() {
            const form = document.getElementById('addEmployeeForm');
            form.classList.toggle('active');
        }

        // Function to delete an employee
        function deleteEmployee(employeeID) {
            if (confirm('Are you sure you want to delete this employee?')) {
                const formData = new FormData();
                formData.append('employeeID', employeeID);
                formData.append('delete_employee', 'true');

                fetch('../Controller/AdminDashboardController.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload(); // Reload the page to reflect the changes
                    } else {
                        console.error('Failed to delete employee');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>