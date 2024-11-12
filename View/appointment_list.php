<!DOCTYPE html>
<html>
<head>
    <title>Your Appointments</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Your Appointments</h1>
    <?php if (empty($appointments)): ?>
        <p>You have no scheduled appointments.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($appointments as $appointment): ?>
                <li>
                    <?php echo htmlspecialchars($appointment->getDate()->format('Y-m-d')); ?> at
                    <?php echo htmlspecialchars($appointment->getTime()->format('H:i')); ?> -
                    <?php echo htmlspecialchars($appointment->getLocation()->getName()); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>