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

// Initialize the last action in the session if not set
if (!isset($_SESSION['last_action'])) {
    $_SESSION['last_action'] = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .section textarea {
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

        .appointment {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .appointment h4 {
            margin: 0 0 10px;
            color: #333;
        }

        .appointment p {
            margin: 5px 0;
        }

        .note-section {
            display: none;
            margin-top: 10px;
        }

        .note-section textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .note-section button {
            margin-right: 10px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            border: none;
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .dropdown-content button:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .assign-employee-section {
            margin-top: 10px;
        }

        .assign-employee-section select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .assign-employee-section button {
            margin-left: 10px;
        }

        .undo-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .undo-button:hover {
            background-color: #c82333;
        }

        .undo-button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <?php
        echo "<h2> Hello " . $_SESSION['user']->getName() . ",</h2>";
        ?>

        <!-- Undo Button -->
        <button class="undo-button" id="undoButton" onclick="undoLastAction()" <?php echo $_SESSION['last_action'] ? '' : 'disabled'; ?>>Undo Last Action</button>

        <div class="section">
            <h3>Filter Appointments</h3>
            <select id="filterStatus" onchange="filterAppointments()">
                <option value="All">All</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Postponed">Postponed</option>
                <option value="Upcoming">Upcoming</option>
                <option value="Past">Past</option>
            </select>
        </div>

        <div class="section">
            <h3>Appointments</h3>
            <div id="appointmentsList">
                <?php
                $controller = new AdminDashboardController();
                if (isset($_SESSION['filterStatus'])) {
                    $appointments = $controller->getAppointmentsByStatus($_SESSION['filterStatus']);
                } else {
                    $appointments = $controller->getAllAppointmentsData();
                }

                if (empty($appointments)) {
                    echo '<p>No appointments found.</p>';
                } else {
                    foreach ($appointments as $appointment) {
                        echo '<div class="appointment">';
                        echo '<h4>Appointment ID: ' . $appointment['appointmentID'] . '</h4>';
                        echo '<p>Date: ' . $appointment['date'] . '</p>';
                        echo '<p>User ID: ' . $appointment['userID'] . '</p>';
                        echo '<p>Status: ' . $appointment['status'] . '</p>';
                        echo '<p>Location: ' . $appointment['location'] . '</p>';
                        echo '<p>Note: ' . $appointment['note'] . '</p>';

                        // Assign Employee Section
                        echo '<div class="assign-employee-section">';
                        echo '<select id="employee-select-' . $appointment['appointmentID'] . '">';
                        echo '<option value="">Select Employee</option>';
                        $employees = $controller->getAllEmployees();
                        foreach ($employees as $employee) {
                            echo '<option value="' . $employee['employeeID'] . '">' . $employee['name'] . '</option>';
                        }
                        echo '</select>';
                        echo '<button onclick="assignEmployee(' . $appointment['appointmentID'] . ')" disabled>Assign Employee</button>';
                        echo '</div>';

                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Enable/Disable Assign Employee Button based on dropdown selection
        document.querySelectorAll('select[id^="employee-select-"]').forEach(select => {
            select.addEventListener('change', function () {
                const appointmentID = this.id.split('-')[2];
                const assignButton = document.querySelector(`button[onclick="assignEmployee(${appointmentID})"]`);
                assignButton.disabled = this.value === '';
            });
        });

        // Function to assign an employee to an appointment
        function assignEmployee(appointmentID) {
            const employeeID = document.getElementById(`employee-select-${appointmentID}`).value;
            if (!employeeID) {
                alert('Please select an employee.');
                return;
            }

            const formData = new FormData();
            formData.append('appointmentID', appointmentID);
            formData.append('employeeID', employeeID);
            formData.append('assign_employee', 'true');

            fetch('../Controller/AdminDashboardController.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Failed to assign employee');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Function to undo the last action
        function undoLastAction() {
            fetch('../Controller/AdminDashboardController.php', {
                method: 'POST',
                body: new URLSearchParams({ undo_last_action: 'true' }),
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Failed to undo last action');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>