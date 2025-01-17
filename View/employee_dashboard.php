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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Employee Dashboard</h1>

        <?php
        session_start();
            echo "<h2> Hello ".$_SESSION['employee']->getName().",</h2>";
        ?>

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
            <h3>Assigned Appointments</h3>
            <div id="appointmentsList">
                <?php
                $controller = new EmployeesDashboardController();
                if(isset($_SESSION['filterStatus'])){
                    $appointments =$controller->getAppointmentsByStatus($_SESSION['filterStatus']);
                }else{
                    $appointments = $controller->getAllAppointmentsData();
                }
                //$appointments = $controller->getAllAppointmentsData();

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
                        echo '<div class="dropdown">';
                        echo '<button>Change Status</button>';
                        echo '<div class="dropdown-content">';
                        echo '<button onclick="changeStatus(' . $appointment['appointmentID'] . ', \'Scheduled\')">Scheduled</button>';
                        echo '<button onclick="changeStatus(' . $appointment['appointmentID'] . ', \'Completed\')">Completed</button>';
                        echo '<button onclick="changeStatus(' . $appointment['appointmentID'] . ', \'Cancelled\')">Cancelled</button>';
                        echo '<button onclick="changeStatus(' . $appointment['appointmentID'] . ', \'Postponed\')">Postponed</button>';
                        echo '</div>';
                        echo '</div>';

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
    </div>

    <script>
        var filterData = <?php echo json_encode($_SESSION['filterStatus']); ?>;
        document.getElementById('filterStatus').value = filterData;
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
                } else {
                    console.error('Failed to submit note');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function filterAppointments() {
            const filterStatus = document.getElementById('filterStatus').value;
            const formData = new FormData();
            formData.append('filterStatus', filterStatus);

            fetch('../Controller/EmployeesDashboardController.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                   window.location.reload();
                } else {
                    console.error('Failed to submit note');
                }
            })
            // .then(response => response.text())
            // .then(data => {
            //     document.getElementById('appointmentsList').innerHTML = data;
            // })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function changeStatus(appointmentID, status) {
            const formData = new FormData();
            formData.append('appointmentID', appointmentID);
            formData.append('status', status);

            fetch('../Controller/EmployeesDashboardController.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    console.error('Failed to change status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>