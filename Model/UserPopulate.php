<?php
require "DatabaseManager.php";
$db = DatabaseManager::getInstance();
$db->runQuery("DROP DATABASE IF EXISTS `food_donation`");
$db->runQuery("CREATE DATABASE `food_donation`");
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`users`");
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`employees`");
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`appointments`");
$db->runQuery(
    "CREATE TABLE food_donation.billable_donations (
        id BIGINT NOT NULL AUTO_INCREMENT,
        user_id BIGINT NOT NULL,
        donation_type VARCHAR(50) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        animal_type ENUM('Lamb', 'Cow', 'Camel', 'Chicken') NULL DEFAULT NULL,
        description TEXT NULL DEFAULT NULL,
        PRIMARY KEY (id)
    );"
);

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
    "CREATE TABLE food_donation.employees (
    id BIGINT PRIMARY KEY,
    role VARCHAR(50) NOT NULL,
    department VARCHAR(50) NOT NULL,
    FOREIGN KEY employees(id) REFERENCES users (id)
);"
);

$db->runQuery(
    "CREATE TABLE `food_donation`.`appointments` (
    `appointmentID` BIGINT NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(50) NOT NULL,
    `date` DATETIME NOT NULL,
    `employeeAssignedID` BIGINT,
    PRIMARY KEY (`appointmentID`)
);"
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

$db->runQuery(
    "INSERT INTO `food_donation`.`employees` (`id`, `role`, `department`) VALUES
    (1, 'Manager', 'Administration'),
    (2, 'Volunteer', 'Community Outreach'),
    (3, 'Coordinator', 'Events'),
    (4, 'Driver', 'Logistics'),
    (5, 'Technician', 'Maintenance'),
    (6, 'Assistant', 'Operations');"
);

$db->runQuery(
    "INSERT INTO `food_donation`.`appointments` (`status`, `date`, `employeeAssignedID`) VALUES
    ('Scheduled', '2024-11-14 10:30:00', 1),
    ('Completed', '2024-11-13 15:00:00', 2),
    ('Cancelled', '2024-11-12 09:00:00', 3),
    ('Scheduled', '2024-11-15 11:00:00', 1),
    ('In Progress', '2024-11-15 14:00:00', 2);"
);

/*
$db->runQuery(
    "CREATE TABLE `food_donation`.`donation_items` (
        itemID INT AUTO_INCREMENT PRIMARY KEY,
        itemName VARCHAR(255) NOT NULL,
        itemWeight DECIMAL(10, 2),
        israwmaterial TINYINT(1) DEFAULT 0,    -- Flag for raw materials (0 or 1)
        isreadymeal TINYINT(1) DEFAULT 0,      -- Flag for ready meals (0 or 1)
        ismeal TINYINT(1) DEFAULT 0,           -- Flag for meals (0 or 1)
        ismoney TINYINT(1) DEFAULT 0,           -- Flag for money (0 or 1)
        issacrifice TINYINT(1) DEFAULT 0,       -- Flag for sacrifice (0 or 1)
        isbox TINYINT(1) DEFAULT 0              -- Flag for box (0 or 1)
);");

$db->runQuery(
    "CREATE TABLE `food_donation`.`raw_materials_donation` (
    itemID INT  PRIMARY KEY,
    expiryDate DATE,
    itemImage BLOB,
    materialType VARCHAR(100),
    quantity INT,
    supplier VARCHAR(255)
);");
$db->runQuery(
    "CREATE TABLE `food_donation`.`ready_meals_donation` (
    itemID INT PRIMARY KEY,
    expiryDate DATE,
    itemImage BLOB,
    mealType VARCHAR(100),
    packagingType VARCHAR(255)
);");
$db->runQuery(
    "CREATE TABLE `food_donation`.`meals_donation` (
    itemID INT PRIMARY KEY,
    expiryDate DATE,
    itemImage BLOB,
    mealType VARCHAR(100),
    servings INT,
    ingredients TEXT,       -- Array of ingredient names stored as serialized text (e.g., JSON)
);");



$db->runQuery(
    "INSERT INTO `food_donation`.`donation_items` (itemName, itemWeight, israwmaterial, isreadymeal, ismeal, ismoney, issacrifice, isbox) VALUES
        ('Rice', 100.50, 1, 0, 0, 0, 0, 0),
        ('Chicken Rice Bowl', 1.50, 0, 1, 0, 0, 0, 0),
        ('Beef Stew', 1.80, 0, 0, 1, 0, 0, 0),
        ('Cash Donation', 0, 0, 0, 0, 1, 0, 0),
        ('Animal Sacrifice', 0, 0, 0, 0, 0, 1, 0),
        ('Food Box', 10.00, 0, 0, 0, 0, 0, 1);"
);
$db->runQuery(
    "INSERT INTO `food_donation`.`raw_materials_donation` (itemID, expiryDate, itemImage, materialType, quantity, supplier) VALUES
        (1, '2025-12-01', NULL, 'Grain', 20, 'Global Rice Co.'),
        (1, '2024-10-20', NULL, 'Grain', 40, 'Flour Mills Ltd.'),
        (1, '2026-06-15', NULL, 'Canned Goods', 100, 'BeanSuppliers Inc.'),
        (1, '2025-02-10', NULL, 'Oil', 15, 'Olive Harvesters LLC'),
        (1, '2024-08-18', NULL, 'Grain', 50, 'PastaWorks Ltd.');"
);
$db->runQuery(
    "INSERT INTO `food_donation`.`ready_meals_donation` (itemID, expiryDate, itemImage, mealType, packagingType) VALUES
        (2, '2025-06-20', NULL, 'Chicken Meal', 'Plastic Bowl'),
        (2, '2024-10-15', NULL, 'Vegetarian', 'Foil Wrap'),
        (2, '2026-06-30', NULL, 'Beef Meal', 'Plastic Container'),
        (2, '2025-02-01', NULL, 'Seafood', 'Sealed Wrapper'),
        (2, '2024-12-12', NULL, 'Fruit', 'Plastic Bowl');"
);
$db->runQuery(
    "INSERT INTO `food_donation`.`meals_donation` (itemID, expiryDate, itemImage, mealType, servings, ingredients) VALUES
        (3, '2025-04-15', NULL, 'Beef Meal', 4, '[\"Beef\", \"Potatoes\", \"Carrots\"]'),
        (3, '2024-11-01', NULL, 'Vegetarian', 3, '[\"Broccoli\", \"Bell Peppers\", \"Carrots\"]'),
        (3, '2025-08-10', NULL, 'Chicken Meal', 2, '[\"Chicken\", \"Rice\", \"Sauce\"]'),
        (3, '2024-07-20', NULL, 'Seafood', 1, '[\"Fish\", \"Lettuce\", \"Tomato\"]'),
        (3, '2025-01-05', NULL, 'Fruit', 1, '[\"Apple\", \"Banana\", \"Grapes\"]');"
);

$db->runQuery(
    "INSERT INTO `food_donation`.`users` (`id`, `name`, `email`, `phone`, `password`) VALUES
    (1, 'Bertha', 'bertha.wilkinson@example.com', '1234567890', 'b95925ed0aa3897a613c7534ae7abeef'),
    (2, 'Demarcus', 'joy.moen@example.net', '0987654321', '5dbd4fbe4edca5a5dd465968232fbf9e'),
    (3, 'Kyla', 'moore.osbaldo@example.com', '1112233445', 'b98dba1167a8c475d603a51ab9935315'),
    (4, 'Lacey', 'zdaniel@example.org', '9988776655', 'e5214e7ac6fce4abf561f03c8f30587a'),
    (5, '7amada', '7amada@belganzabeel.com', '1231231234', '25d55ad283aa400af464c76d713c07ad'),
    (6, 'Pearl', 'rjacobi@example.org', '5556667777', 'c1191c08cb4ae00632c4cf3444979bbd');"
);*/
?>
