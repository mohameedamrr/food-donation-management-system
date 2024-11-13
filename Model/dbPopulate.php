<?php
require "DatabaseManager.php";
$db = DatabaseManager::getInstance();
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`users`");
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`donation_items`");
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`raw_materials_donation`");

// $db->runQuery("CREATE DATABASE `food_donation`");
$db->runQuery(
    "CREATE TABLE `food_donation`.`users` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NULL DEFAULT NULL,
    `email` VARCHAR(50) NULL DEFAULT NULL,
    `phone` VARCHAR(50) NULL,
    `password` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `uq_email` (`email` ASC)
);"
);
$db->runQuery(
    "CREATE TABLE `food_donation`.`raw_materials_donation` (
    itemID INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(255) NOT NULL,
    itemWeight DECIMAL(10, 2),
    expiryDate DATE,
    itemImage BLOB,
    materialType VARCHAR(100),
    quantity INT,
    supplier VARCHAR(255)
);");
$db->runQuery(
    "CREATE TABLE `food_donation`.`ready_meals_donation` (
    itemID INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(255) NOT NULL,
    itemWeight DECIMAL(10, 2),
    expiryDate DATE,
    itemImage BLOB,
    mealType VARCHAR(100),
    packagingType VARCHAR(255)
);");
$db->runQuery(
    "CREATE TABLE `food_donation`.`meals_donation` (
    itemID INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(255) NOT NULL,
    itemWeight DECIMAL(10, 2),
    expiryDate DATE,
    itemImage BLOB,
    mealType VARCHAR(100),
    servings INT,
    ingredients TEXT,       -- Array of ingredient names stored as serialized text (e.g., JSON)
);");
$db->runQuery(
    "INSERT INTO `food_donation`.`raw_materials_donation` (itemName, itemWeight, expiryDate, itemImage, materialType, quantity, supplier) VALUES
        ('Rice', 100.50, '2025-12-01', NULL, 'Grain', 20, 'Global Rice Co.'),
        ('Flour', 50.00, '2024-10-20', NULL, 'Grain', 40, 'Flour Mills Ltd.'),
        ('Canned Beans', 10.00, '2026-06-15', NULL, 'Canned Goods', 100, 'BeanSuppliers Inc.'),
        ('Olive Oil', 30.75, '2025-02-10', NULL, 'Oil', 15, 'Olive Harvesters LLC'),
        ('Pasta', 25.00, '2024-08-18', NULL, 'Grain', 50, 'PastaWorks Ltd.');"
);
$db->runQuery(
    "INSERT INTO `food_donation`.`ready_meals_donation` (itemName, itemWeight, expiryDate, itemImage, mealType, packagingType) VALUES
        ('Chicken Rice Bowl', 1.50, '2025-12-01', NULL, 'Chicken Meal', 'Plastic Bowl'),
        ('Vegetable Wrap', 0.75, '2024-10-20', NULL, 'Vegetarian', 'Foil Wrap'),
        ('Pasta Bolognese', 0.85, '2026-06-15', NULL, 'Beef Meal', 'Plastic Container'),
        ('Tuna Sandwich', 0.60, '2025-02-10', NULL, 'Seafood', 'Sealed Wrapper'),
        ('Fruit Salad', 0.50, '2024-08-18', NULL, 'Fruit', 'Plastic Bowl');"
);
$db->runQuery(
    "INSERT INTO `food_donation`.`meals_donation` (itemName, itemWeight, expiryDate, itemImage, mealType, servings, ingredients, packagingType) VALUES
        ('Chicken Alfredo Pasta', 1.2, '2025-12-01', NULL, 'Chicken Meal', 2, '[\"Chicken\", \"Pasta\", \"Cream\", \"Parmesan Cheese\"]', 'Plastic Bowl'),
        ('Vegetable Stir Fry', 1.0, '2024-10-20', NULL, 'Vegetarian', 3, '[\"Broccoli\", \"Bell Peppers\", \"Carrots\", \"Soy Sauce\"]', 'Sealed Tray'),
        ('Beef Stew', 1.5, '2026-06-15', NULL, 'Beef Meal', 4, '[\"Beef\", \"Potatoes\", \"Carrots\", \"Celery\", \"Onions\"]', 'Plastic Container'),
        ('Tuna Salad Wrap', 0.6, '2025-02-10', NULL, 'Seafood', 1, '[\"Tuna\", \"Lettuce\", \"Tomato\", \"Whole Wheat Wrap\"]', 'Foil Wrap'),
        ('Fruit Parfait', 0.5, '2024-08-18', NULL, 'Dessert', 1, '[\"Yogurt\", \"Granola\", \"Berries\", \"Honey\"]', 'Plastic Cup');"
);

$db->runQuery(
    "INSERT INTO `food_donation`.`users` (`id`, `name`, `email`, `phone`, `password`) VALUES
    (1, 'Bertha', 'bertha.wilkinson@example.com', '1234567890', 'b95925ed0aa3897a613c7534ae7abeef'),
    (2, 'Demarcus', 'joy.moen@example.net', '0987654321', '5dbd4fbe4edca5a5dd465968232fbf9e'),
    (3, 'Kyla', 'moore.osbaldo@example.com', '1112233445', 'b98dba1167a8c475d603a51ab9935315'),
    (4, 'Lacey', 'zdaniel@example.org', '9988776655', 'e5214e7ac6fce4abf561f03c8f30587a'),
    (5, '7amada', '7amada@belganzabeel.com', '1231231234', '25d55ad283aa400af464c76d713c07ad'),
    (6, 'Pearl', 'rjacobi@example.org', '5556667777', 'c1191c08cb4ae00632c4cf3444979bbd');"
);
?>