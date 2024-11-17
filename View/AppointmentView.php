<?php
class AppointmentView {
    // Display appointment details
    public function showAppointmentDetails($appointmentDetails) {
        echo '<h2>Appointment Details</h2>';
        echo '<p>Status: ' . $appointmentDetails['status'] . '</p>';
        echo '<p>Location: ' . $appointmentDetails['location'] . '</p>';
        echo '<p>Date: ' . $appointmentDetails['date'] . '</p>';
    }

    // Display form to create a new appointment
    public function showCreateAppointmentForm($employeeIds) {
        echo '
        <form method="post" action="createAppointment.php">
            <select name="employeeAssignedID">';
        foreach ($employeeIds as $employee) {
            echo '<option value="' . $employee['id'] . '">' . $employee['name'] . '</option>';
        }
        echo '</select>
            <input type="date" name="date" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit">Create Appointment</button>
        </form>';
    }

    // Display all appointments for an employee
    public function showEmployeeAppointments($appointments) {
        echo '<h2>Your Appointments</h2>';
        foreach ($appointments as $appointment) {
            echo '<div>';
            echo '<p>Status: ' . $appointment['status'] . '</p>';
            echo '<p>Date: ' . $appointment['date'] . '</p>';
            echo '<p>Location: ' . $appointment['location'] . '</p>';
            echo '<a href="editAppointment.php?id=' . $appointment['id'] . '">Edit</a>';
            echo '</div>';
        }
    }

    // Display success message for appointment status update
    public function showAppointmentStatusUpdateSuccess() {
        echo '<p>Appointment status updated successfully!</p>';
    }
}
?>
