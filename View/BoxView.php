<?php
// View/BoxView.php
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
class BoxView {

    public function displayBoxCreated($donateBox) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Box Created</title>
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
                    <a href="create_box.php">Create Box</a> |
                    <a href="view_boxes.php">View Boxes</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <h2>Box Created</h2>
                <p>Your box has been created successfully.</p>
                <p><strong>Box Details:</strong></p>
                <ul>
                    <li><strong>Size:</strong> <?php echo htmlspecialchars($donateBox->getSize()); ?></li>
                    <li><strong>Weight Limit:</strong> <?php echo htmlspecialchars($donateBox->getWeightLimit()); ?> kg</li>
                    <li><strong>Items:</strong>
                        <ul>
                            <?php foreach ($donateBox->getItems() as $item): ?>
                                <li><?php echo htmlspecialchars($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><strong>Total Weight:</strong> <?php echo htmlspecialchars($donateBox->getTotalWeight()); ?> kg</li>
                </ul>
                <?php
                // Display additional items added via decorators
                $additionalItems = $this->getAdditionalItems($donateBox);
                if (!empty($additionalItems)):
                ?>
                    <p><strong>Additional Items:</strong></p>
                    <ul>
                        <?php foreach ($additionalItems as $additionalItem): ?>
                            <li><?php echo htmlspecialchars($additionalItem); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <p><a href="create_box.php">Create Another Box</a></p>
            </main>

            <footer>
                <p>&copy; 2024 Food Donation Management System</p>
            </footer>
        </body>
        </html>
        <?php
    }

    private function getAdditionalItems($box) {
        $additionalItems = [];
        $currentBox = $box;
        while ($currentBox instanceof BoxDecorator) {
            $additionalItems[] = $currentBox->getAdditionalItem();
            $currentBox = $currentBox->getBox();
        }
        return array_reverse($additionalItems); // Reverse to maintain order
    }
}
?>