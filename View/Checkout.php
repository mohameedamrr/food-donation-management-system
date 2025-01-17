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
session_start();

// Autoload classes

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve the cart items from the session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
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
                <?php foreach ($cartItems as $index => $item): ?>
                    <div class="cart-item">
                        <h3><?php echo htmlspecialchars($item->getItemName() ?? 'Unknown Item'); ?></h3>
                        <?php
                        // Display item details
                        if ($item instanceof Meal) {
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item->getMealQuantity()) . '</p>';
                        } elseif ($item instanceof RawMaterials) {
                            echo '<p><strong>Material Type:</strong> ' . htmlspecialchars($item->getMaterialType()) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item->getQuantity()) . '</p>';
                            echo '<p><strong>Weight:</strong> ' . htmlspecialchars($item->getRawMaterialWeight()) . '</p>';
                            echo '<p><strong>Supplier:</strong> ' . htmlspecialchars($item->getSupplier()) . '</p>';
                        } elseif ($item instanceof Money) {
                            echo '<p><strong>Amount:</strong> ' . htmlspecialchars($item->getAmount()) . '</p>';
                            echo '<p><strong>Purpose:</strong> ' . htmlspecialchars($item->getDonationPurpose()) . '</p>';
                        } elseif ($item instanceof Sacrifice) {
                            echo '<p><strong>Animal Type:</strong> ' . htmlspecialchars($item->getAnimalType()) . '</p>';
                            echo '<p><strong>Weight:</strong> ' . htmlspecialchars($item->getWeight()) . '</p>';
                        } elseif ($item instanceof ClientReadyMeal) {
                            echo '<p><strong>Meal Type:</strong> ' . htmlspecialchars($item->getReadyMealType()) . '</p>';
                            echo '<p><strong>Expiration:</strong> ' . htmlspecialchars($item->getReadyMealExpiration()) . '</p>';
                            echo '<p><strong>Packaging Type:</strong> ' . htmlspecialchars($item->getPackagingType()) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($item->getReadyMealQuantity()) . '</p>';
                        }
                        ?>
                        <form method="POST" action="../Controller/CheckoutController.php" style="display: inline;">
                            <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                            <button type="submit" name="remove_item" class="remove-button">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="donate-button">
            <form method="POST" action="../Controller/CheckoutController.php">
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
    </script>
</body>
</html>