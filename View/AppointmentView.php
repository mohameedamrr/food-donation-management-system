<?php
// View/AppointmentView.php
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
class AppointmentView {

    public function displayAppointmentScheduled(Appointment $appointment) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Appointment Scheduled</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
                ul { list-style-type: none; padding: 0; }
                li { margin-bottom: 5px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Food Donation Management System</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="schedule_appointment.php">Schedule Appointment</a> |
                    <a href="view_appointments.php">View Appointments</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Appointment Scheduled</h2>
                <p>Your appointment has been scheduled successfully.</p>
                <p><strong>Appointment Details:</strong></p>
                <ul>
                    <li><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment->getAppointmentID()); ?></li>
                    <li><strong>Date:</strong> <?php echo htmlspecialchars($appointment->getDate()->format('Y-m-d')); ?></li>
                    <li><strong>Time:</strong> <?php echo htmlspecialchars($appointment->getTime()->format('H:i')); ?></li>
                    <li><strong>Location:</strong> <?php echo htmlspecialchars($appointment->getLocation()->getName()); ?></li>
                    <li><strong>Address:</strong> <?php echo htmlspecialchars($appointment->getLocation()->getAddressLine()); ?></li>
                    <li><strong>Postal Code:</strong> <?php echo htmlspecialchars($appointment->getLocation()->getPostalCode()); ?></li>
                </ul>
                <p><a href="schedule_appointment.php">Schedule Another Appointment</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Food Donation Management System</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayEmployeeAssignment($appointmentID, Employee $employee) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Employee Assigned</title>
            <style>
                /* Add your CSS styles here */
                /* Same as above or adjust as needed */
            </style>
        </head>
        <body>
            <header>
                <h1>Employee Assignment</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="assign_employee.php">Assign Employee</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Employee Assigned to Appointment</h2>
                <p>The following employee has been assigned to appointment ID <?php echo htmlspecialchars($appointmentID); ?>:</p>
                <ul>
                    <li><strong>Employee ID:</strong> <?php echo htmlspecialchars($employee->getId()); ?></li>
                    <li><strong>Name:</strong> <?php echo htmlspecialchars($employee->getName()); ?></li>
                    <li><strong>Role:</strong> <?php echo htmlspecialchars($employee->getRole()); ?></li>
                    <li><strong>Department:</strong> <?php echo htmlspecialchars($employee->getDepartment()); ?></li>
                </ul>
                <p><a href="view_appointments.php">Back to Appointments</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Food Donation Management System</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayAppointmentStatusUpdate($appointmentID, $status) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Appointment Status Updated</title>
            <style>
                /* Add your CSS styles here */
            </style>
        </head>
        <body>
            <header>
                <h1>Appointment Status Update</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="view_appointments.php">View Appointments</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Status Updated</h2>
                <p>The status for appointment ID <?php echo htmlspecialchars($appointmentID); ?> has been updated to <strong><?php echo htmlspecialchars($status); ?></strong>.</p>
                <p><a href="view_appointments.php">Back to Appointments</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Food Donation Management System</p>
            </footer>
        </body>
        </html>
        <?php
    }
}
?>