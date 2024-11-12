<!DOCTYPE html>
<html>
<head>
    <title>Make a Donation</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Make a Donation</h1>
    <form action="index.php?action=make_donation" method="POST">
        <label>Amount:</label>
        <input type="number" name="amount" required><br><br>
        <label>Purpose:</label>
        <input type="text" name="donationPurpose" required><br><br>
        <!-- Add other fields as needed -->
        <button type="submit">Donate</button>
    </form>
</body>
</html>