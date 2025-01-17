<?php
session_start();
require_once '../Controller/DonationHistoryController.php';

$controller = new DonationHistoryController();
$donationHistory = $controller->getDonationHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History</title>
    <style>
        /* Use the same styles as in the attached files */
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
            max-width: 1200px;
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

        .donation {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .donation h4 {
            margin: 0 0 10px;
            color: #333;
        }

        .donation p {
            margin: 5px 0;
        }

        .edit-section {
            display: none;
            margin-top: 10px;
        }

        .edit-section textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .edit-section button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Donation History</h1>

        <div class="section">
            <h3>All Donations</h3>
            <?php
            if (empty($donationHistory)) {
                echo '<p>No donation history available.</p>';
            } else {
                foreach ($donationHistory as $donation) {
                    echo '<div class="donation">';
                    echo '<h4>Donation ID: ' . $donation['id'] . '</h4>';
                    echo '<p>Total Cost: ' . $donation['total_cost'] . '</p>';
                    echo '<p>Description: ' . $donation['description'] . '</p>';
                    echo '<p>Donation Date: ' . $donation['donation_date'] . '</p>';

                    echo '<button onclick="showEditForm(' . $donation['id'] . ')">Edit Description</button>';
                    echo '<div id="edit-section-' . $donation['id'] . '" class="edit-section">';
                    echo '<form method="POST" action="../Controller/DonationHistoryController.php">';
                    echo '<input type="hidden" name="donation_id" value="' . $donation['id'] . '">';
                    echo '<textarea name="new_description" placeholder="Enter new description"></textarea>';
                    echo '<button type="submit" name="update_description">Save</button>';
                    echo '<button type="button" onclick="hideEditForm(' . $donation['id'] . ')">Cancel</button>';
                    echo '</form>';
                    echo '</div>';

                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <script>
        // Function to show the edit form
        function showEditForm(donationId) {
            document.getElementById('edit-section-' + donationId).style.display = 'block';
        }

        // Function to hide the edit form
        function hideEditForm(donationId) {
            document.getElementById('edit-section-' + donationId).style.display = 'none';
        }
    </script>
</body>
</html>