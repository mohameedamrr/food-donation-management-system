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
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

// Autoload classes

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve the cart items from the session
// $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$controller = new CartController();
$cartItems = $controller->getCartData();

// Check if the cart contains items that require an appointment
$requiresAppointment = false;
foreach ($cartItems as $item) {
    if ($item['type'] === "ClientReadyMeal" || $item['type'] === "RawMaterials") {
        $requiresAppointment = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .cart-container {
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

        .cart-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item h3 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .cart-item p {
            margin: 5px 0;
        }

        .remove-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-button:hover {
            background-color: #c82333;
        }

        .donate-button {
            text-align: center;
            margin-top: 20px;
        }

        .donate-button button {
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .donate-button button:hover {
            background-color: #218838;
        }

        .cart-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-icon span {
            font-size: 14px;
        }

        .payment-method {
            margin-top: 20px;
        }

        .payment-method label {
            display: block;
            margin-bottom: 10px;
        }

        .payment-method input[type="radio"] {
            margin-right: 10px;
        }

        .payment-details {
            margin-top: 20px;
            display: none;
        }

        .payment-details input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .appointment-details {
            margin-top: 20px;
            display: <?php echo $requiresAppointment ? 'block' : 'none'; ?>;
        }

        .appointment-details input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Cart Icon -->
    <div class="cart-icon">
        <span id="cart-count"><?php echo count($cartItems); ?></span>
    </div>

    <div class="cart-container">
        <h2>Your Cart</h2>

        <div id="cart-items">
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <?php $totalCost=0 ?>
                <?php foreach ($cartItems as $index=>$item): ?>
                    <div class="cart-item">
                        <h3><?php echo htmlspecialchars($item['name'] ?? 'Unknown Item'); ?></h3>
                        <?php
                        // Display item details
                        if ($item['type'] === "Meal") {
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item['mealQuantity']) . '</p>';
                            echo '<p><strong>Cost:</strong> ' . htmlspecialchars($item['mealCost']) . '</p>';
                            $totalCost += $item['mealCost'];
                        } elseif ($item['type'] === "RawMaterials") {
                            echo '<p><strong>Material Type:</strong> ' . htmlspecialchars($item['materialType']) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item['materialQuantity']) . '</p>';
                            echo '<p><strong>Weight:</strong> ' . htmlspecialchars($item['materialWeight']) . '</p>';
                            echo '<p><strong>Supplier:</strong> ' . htmlspecialchars($item['supplier']) . '</p>';
                        } elseif ($item['type'] === "Money") {
                            echo '<p><strong>Amount:</strong> ' . htmlspecialchars($item["amount"]) . '</p>';
                            echo '<p><strong>Purpose:</strong> ' . htmlspecialchars($item['purpose']) . '</p>';
                            $totalCost += $item["amount"];
                        } elseif ($item['type'] === "Sacrifice") {
                            echo '<p><strong>Animal Type:</strong> ' . htmlspecialchars($item['animalType']) . '</p>';
                            echo '<p><strong>Weight:</strong> ' . htmlspecialchars($item['weight']) . '</p>';
                            echo '<p><strong>Cost:</strong> ' . htmlspecialchars($item['cost']) . '</p>';
                            $totalCost += $item['cost'];
                        } elseif ($item['type'] === "ClientReadyMeal") {
                            echo '<p><strong>Meal Type:</strong> ' . htmlspecialchars($item['readyMealType']) . '</p>';
                            echo '<p><strong>Expiration:</strong> ' . htmlspecialchars($item['expiration']) . '</p>';
                            echo '<p><strong>Packaging Type:</strong> ' . htmlspecialchars($item['packagingType']) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item['mealQuantity']) . '</p>';
                        }
                        ?>
                        <form method="POST" action="../Controller/CartController.php" style="display: inline;">
                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                            <button type="submit" name="remove_item" class="remove-button">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <?php echo '<h1><strong>Total Cost: '.$totalCost.'</strong></h1>'?>

            <?php endif; ?>
        </div>

        <!-- Appointment Details Section -->
        <?php if ($requiresAppointment): ?>
            <div class="appointment-details">
                <h3>Appointment Details</h3>
                <input type="date" name="appointmentDate" placeholder="Appointment Date">
                <input type="text" name="appointmentLocation" placeholder="Appointment Location">
            </div>
        <?php endif; ?>

        <div class="payment-method">
            <h3>Select Payment Method</h3>
            <label>
                <input type="radio" name="paymentType" value="Cash" checked> Cash
            </label>
            <label>
                <input type="radio" name="paymentType" value="Card"> Card
            </label>
            <label>
                <input type="radio" name="paymentType" value="InstaPay"> InstaPay
            </label>
            <label>
                <input type="radio" name="paymentType" value="Paypal"> PayPal
            </label>
        </div>

        <div class="payment-details" id="card-details">
            <input type="text" name="cardHolderName" placeholder="Card Holder Name">
            <input type="text" name="cardNumber" placeholder="Card Number">
            <input type="month" name="expiryDate" placeholder="Expiry Date (MM/YY)">
        </div>

        <div class="payment-details" id="instapay-details">
            <input type="text" name="instapayAddress" placeholder="InstaPay Address">
        </div>

        <div class="donate-button">
            <form method="POST" action="../Controller/CartController.php">
                <input type="hidden" name="paymentType" id="paymentType" value="Cash">
                <?php if ($requiresAppointment): ?>
                    <!-- Hidden inputs for appointment data -->
                    <input type="hidden" name="appointmentDate" id="appointmentDate">
                    <input type="hidden" name="appointmentLocation" id="appointmentLocation">
                <?php endif; ?>
                <div id="dynamic-fields"></div>
                <button type="submit" name="donate">Donate</button>
            </form>
        </div>
    </div>

    <script>
        // Automatically update the cart count when items are removed
        async function fetchCartItemCount() {
            const response = await fetch('../Controller/getCartItemCount.php');
            const data = await response.json();
            document.getElementById('cart-count').textContent = data.count;
        }

        // Update the cart count when the page loads
        fetchCartItemCount();

        function updatePaymentDetails() {
            const paymentType = document.querySelector('input[name="paymentType"]:checked').value;
            document.getElementById('paymentType').value = paymentType;

            const cardDetails = document.getElementById('card-details');
            const instapayDetails = document.getElementById('instapay-details');
            const dynamicFields = document.getElementById('dynamic-fields');

            cardDetails.style.display = 'none';
            instapayDetails.style.display = 'none';
            dynamicFields.innerHTML = '';

            if (paymentType === 'Card') {
                cardDetails.style.display = 'block';
                dynamicFields.innerHTML = `
                    <input type="hidden" name="cardHolderName" value="${cardDetails.querySelector('input[name="cardHolderName"]').value}">
                    <input type="hidden" name="cardNumber" value="${cardDetails.querySelector('input[name="cardNumber"]').value}">
                    <input type="hidden" name="expiryDate" value="${cardDetails.querySelector('input[name="expiryDate"]').value}">
                `;
            } else if (paymentType === 'InstaPay') {
                instapayDetails.style.display = 'block';
                dynamicFields.innerHTML = `
                    <input type="hidden" name="instapayAddress" value="${instapayDetails.querySelector('input[name="instapayAddress"]').value}">
                `;
            }
        }

        // Add event listeners to payment method radio buttons
        document.querySelectorAll('input[name="paymentType"]').forEach(radio => {
            radio.addEventListener('change', updatePaymentDetails);
        });

        // Initialize payment details on page load
        updatePaymentDetails();

        // Handle appointment details
        <?php if ($requiresAppointment): ?>
            const appointmentDateInput = document.querySelector('input[name="appointmentDate"]');
            const appointmentLocationInput = document.querySelector('input[name="appointmentLocation"]');
            const hiddenAppointmentDate = document.getElementById('appointmentDate');
            const hiddenAppointmentLocation = document.getElementById('appointmentLocation');

            appointmentDateInput.addEventListener('change', () => {
                hiddenAppointmentDate.value = appointmentDateInput.value;
            });

            appointmentLocationInput.addEventListener('input', () => {
                hiddenAppointmentLocation.value = appointmentLocationInput.value;
            });
        <?php endif; ?>
    </script>
</body>
</html>