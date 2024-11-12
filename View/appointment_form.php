<!DOCTYPE html>
<html>
<head>
    <title>Schedule Appointment</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Schedule an Appointment</h1>
    <form action="index.php?action=schedule_appointment" method="POST">
        <label>Date:</label>
        <input type="date" name="date" required><br><br>
        <label>Time:</label>
        <input type="time" name="time" required><br><br>
        <!-- Add other fields as needed -->
        <button type="submit">Schedule Appointment</button>
    </form>
</body>
</html>