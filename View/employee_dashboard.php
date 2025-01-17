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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
            margin-right: 10px; /* Add spacing between buttons */
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Employee Dashboard</h1>

        <?php 
        session_start();
            echo "<h2> Hello ".$_SESSION['user']->getName().",</h2>";
        ?>

        <div class="section">
            <h3>Assigned Appointments</h3>
            <?php
            $controller = new EmployeesDashboardController();
            $appointments = $controller->getAllAppointmentsData();

            if (empty($appointments)) {
                echo '<p>No appointments assigned.</p>';
            } else {
                foreach ($appointments as $appointment) {
                    echo '<div class="appointment">';
                    echo '<h4>Appointment ID: ' . $appointment['appointmentID'] . '</h4>';
                    echo '<p>Date: ' . $appointment['date'] . '</p>';
                    echo '<p>User ID: ' . $appointment['userID'] . '</p>';
                    echo '<p>Status: ' . $appointment['status'] . '</p>';
                    echo '<p>Location: ' . $appointment['location'] . '</p>';
                    echo '<p>Note: ' . $appointment['note'] . '</p>';

                    echo '<button onclick="showNoteForm(' . $appointment['appointmentID'] . ')">Add Note</button>';
                    echo '<form method="POST" action="../Controller/EmployeesDashboardController.php" style="display:inline;">';
                    echo '<input type="hidden" name="appointmentID" value="' . $appointment['appointmentID'] . '">';
                    echo '<button type="submit" name="complete_appointment">Complete</button>';
                    echo '</form>';

                    echo '<div id="note-section-' . $appointment['appointmentID'] . '" class="note-section">';
                    echo '<textarea name="note" placeholder="Enter note"></textarea>';
                    echo '<button onclick="submitNote(' . $appointment['appointmentID'] . ')">Done</button>';
                    echo '<button onclick="hideNoteForm(' . $appointment['appointmentID'] . ')">Cancel</button>';
                    echo '</div>';

                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <script>
        function showNoteForm(appointmentID) {
            document.getElementById('note-section-' + appointmentID).style.display = 'block';
        }

        function hideNoteForm(appointmentID) {
            document.getElementById('note-section-' + appointmentID).style.display = 'none';
        }

        function submitNote(appointmentID) {
            const note = document.querySelector('#note-section-' + appointmentID + ' textarea').value;
            const formData = new FormData();
            formData.append('appointmentID', appointmentID);
            formData.append('note', note);
            formData.append('add_note', 'true');

            fetch('../Controller/EmployeesDashboardController.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                   window.location.reload();
                   //header('Location: ../View/employee_dashboard.php');
                } else {
                    console.error('Failed to submit note');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>