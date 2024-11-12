<!DOCTYPE html>
<html>
<head>
    <title>Your Donations</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Your Donations</h1>
    <?php if (empty($donations)): ?>
        <p>You have not made any donations yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($donations as $donation): ?>
                <li>
                    Donation ID: <?php echo htmlspecialchars($donation->getDonationID()); ?> -
                    Amount: <?php echo htmlspecialchars($donation->getCurrency() . ' ' . $donation->getAmount()); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>