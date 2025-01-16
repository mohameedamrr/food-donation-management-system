<?php
// require "DatabaseManager.php";
// $db = DatabaseManager::getInstance();
// $db->runQuery("DROP DATABASE IF EXISTS `food_donation`");
// $db->runQuery("CREATE DATABASE `food_donation`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`users`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`employees`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`appointments`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`donations`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`donation_history`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`donation_items`");
// $db->runQuery("DROP TABLE IF EXISTS `food_donation`.`extra_box_items`");


// $db->runQuery(
//     "CREATE TABLE `food_donation`.`users` (
//     `id` int NOT NULL AUTO_INCREMENT,
//     `name` VARCHAR(50) NULL DEFAULT NULL,
//     `email` VARCHAR(50) NULL DEFAULT NULL,
//     `phone` VARCHAR(50) NULL,
//     `password` VARCHAR(32) NOT NULL,
//     PRIMARY KEY (`id`),
//     UNIQUE INDEX `uq_email` (`email` ASC)
// );"
// );

// $db->runQuery(
//     "CREATE TABLE food_donation.employees (
//     id int PRIMARY KEY,
//     `role` VARCHAR(50) NOT NULL,
//     department VARCHAR(50) NOT NULL,
//     salary FLOAT NOT NULL,
//     FOREIGN KEY employees(id) REFERENCES users (id) ON DELETE CASCADE
// );"
// );

// $db->runQuery(
//     "CREATE TABLE `food_donation`.`appointments` (
//     `appointmentID` int NOT NULL AUTO_INCREMENT,
//     `userID` int NOT NULL,
//     `status` VARCHAR(50) NOT NULL,
//     `date` DATETIME NOT NULL,
//     `employeeAssignedID` int,
//     `location` VARCHAR(150) NOT NULL,
//     `note` TEXT ,
//     PRIMARY KEY (`appointmentID`),
//     FOREIGN KEY appointments(userID) REFERENCES users (id)
// );"
// );

// $db->runQuery(
//     "INSERT INTO `food_donation`.`users` (`id`, `name`, `email`, `phone`, `password`) VALUES
//     (1, 'Bertha', 'bertha.wilkinson@example.com', '1234567890', 'b95925ed0aa3897a613c7534ae7abeef'),
//     (2, 'Demarcus', 'joy.moen@example.net', '0987654321', '5dbd4fbe4edca5a5dd465968232fbf9e'),
//     (3, 'Kyla', 'moore.osbaldo@example.com', '1112233445', 'b98dba1167a8c475d603a51ab9935315'),
//     (4, 'Lacey', 'zdaniel@example.org', '9988776655', 'e5214e7ac6fce4abf561f03c8f30587a'),
//     (5, '7amada', '7amada@belganzabeel.com', '1231231234', 'fcea920f7412b5da7be0cf42b8c93759'),
//     (6, 'Pearl', 'rjacobi@example.org', '5556667777', 'c1191c08cb4ae00632c4cf3444979bbd');"
// );

// $db->runQuery(
//     "INSERT INTO `food_donation`.`employees` (`id`, `role`, `department`, salary) VALUES
//     (1, 'Manager', 'Administration', 20000.0),
//     (2, 'Volunteer', 'Community Outreach', 0),
//     (3, 'Coordinator', 'Events', 17000),
//     (4, 'Driver', 'Logistics', 5000),
//     (5, 'Technician', 'Maintenance', 15000),
//     (6, 'Assistant', 'Operations', 10000);"
// );


// //$db->runQuery(
// //    "INSERT INTO `food_donation`.`appointments` (`status`, `date`, `employeeAssignedID`, `location`) VALUES
// //    ('Scheduled', '2024-11-14 10:30:00', 1, 'asdasdasdadasd'),
// //    ('Completed', '2024-11-13 15:00:00', 2, 'asdasdasdadasd'),
// //    ('Cancelled', '2024-11-12 09:00:00', 3, 'asdasdasdadasd'),
// //    ('Scheduled', '2024-11-15 11:00:00', 1, 'asdasdasdadasd'),
// //    ('In Progress', '2024-11-15 14:00:00', 2, 'asdasdasdadasd');"
// //);

// $db->runQuery(
//     "INSERT INTO `food_donation`.`appointments` (`userID`, `status`, `date`, `employeeAssignedID`, `location`, `note`) VALUES
//     (1, 'Scheduled', '2024-11-14 10:30:00', 1, '123 Main St, City A', 'Please ring the doorbell before pickup.'),
//     (2, 'Completed', '2024-11-13 15:00:00', 2, '456 Elm St, City B', 'Left the donation package by the front door.'),
//     (3, 'Cancelled', '2024-11-12 09:00:00', 3, '789 Oak St, City C', 'Cancelled due to unexpected travel.'),
//     (4, 'Scheduled', '2024-11-15 11:00:00', 1, '101 Pine St, City D', 'Please call before arriving.'),
//     (5, 'In Progress', '2024-11-15 14:00:00', 2, '202 Maple St, City E', 'Donation is ready in the garage.'),
//     (6, 'Scheduled', '2024-11-16 10:00:00', 4, '303 Birch St, City F', 'Please use the side entrance for pickup.'),
//     (1, 'Completed', '2024-11-17 12:00:00', 5, '404 Cedar St, City G', 'Donation delivered to the community center.'),
//     (2, 'Scheduled', '2024-11-18 13:00:00', 6, '505 Walnut St, City H', 'Please confirm the pickup time.'),
//     (3, 'In Progress', '2024-11-19 14:00:00', 1, '606 Spruce St, City I', 'Donation is in the backyard.'),
//     (4, 'Cancelled', '2024-11-20 15:00:00', 2, '707 Fir St, City J', 'Cancelled due to bad weather.');"
// );





// $db->runQuery("
//     CREATE TABLE `food_donation`.`donation_items` (
//         item_id INT AUTO_INCREMENT PRIMARY KEY,
//         item_name VARCHAR(255) NOT NULL,
//         currency VARCHAR(255) NOT NULL,
//         cost FLOAT NOT NULL,
//         animal_type VARCHAR(255),
//         weight FLOAT,
//         mealType VARCHAR(255),
//         expiration DATE,
//         ingredients TEXT,
//         initial_box_size FLOAT,
//         initial_item_list TEXT
//     );
// ");

// $db->runQuery("
//     CREATE TABLE `food_donation`.`donations` (
//         donation_id INT AUTO_INCREMENT PRIMARY KEY,
//         donation_date DATETIME NOT NULL,
//         user_id INT NOT NULL,
//         is_successful BOOLEAN NOT NULL,
//         FOREIGN KEY donations(user_id) REFERENCES users(id) ON DELETE CASCADE
//     );
// ");

// $db->runQuery("
//     CREATE TABLE `food_donation`.`donation_history` (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         total_cost FLOAT NOT NULL,
//         description TEXT NOT NULL,
//         donation_id INT NOT NULL,
//         meal_id INT,
//         meal_cost FLOAT,
//         meal_quantity INT,
//         raw_materials_id INT,
//         raw_materials_cost FLOAT,
//         material_type VARCHAR(255),
//         material_quantity INT,
//         material_weight FLOAT,
//         material_supplier VARCHAR(255),
//         client_ready_meal_id INT,
//         client_ready_meal_cost FLOAT,
//         ready_meal_type VARCHAR(255),
//         ready_meal_expiration DATE,
//         ready_meal_quantity INT,
//         ready_meal_packaging_type VARCHAR(255),
//         money_id INT,
//         money_amount FLOAT,
//         money_donation_purpose VARCHAR(255),
//         sacrifice_id INT,
//         sacrifice_cost FLOAT,
//         box_id INT,
//         box_cost FLOAT,
//         final_box_size FLOAT,
//         final_item_list TEXT,
//         FOREIGN KEY donation_history(donation_id) REFERENCES donations(donation_id) ON DELETE CASCADE
//     );
// ");

// $db->runQuery("
//     CREATE TABLE `food_donation`.`extra_box_items` (
//         extra_item_id INT AUTO_INCREMENT PRIMARY KEY,
//         extra_item_name VARCHAR(255) NOT NULL,
//         price_per_unit FLOAT NOT NULL
//     );
// ");

// $db->runQuery(
//     "INSERT INTO `food_donation`.`donation_items` (
//         `item_name`, `currency`, `cost`, `animal_type`, `weight`, `mealType`, `expiration`, `ingredients`, `initial_box_size`, `initial_item_list`
//     ) VALUES
//     ('Vegetarian Meal', 'EGP', 150, NULL, NULL, 'Vegetarian', '2025-12-16', 'Beans, Water, Salt', NULL, NULL),
//     ('Chicken Meal', 'EGP', 250, NULL, NULL, 'Chicken', '2024-12-31', 'Chicken, Rice, Fries', NULL, NULL),
//     ('Raw Materials', 'EGP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
//     ('Client Ready Meal', 'EGP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
//     ('Money', 'EGP', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
//     ('Sheep Sacrifice', 'EGP', 150, 'Sheep', 90, NULL, NULL, NULL, NULL, NULL),
//     ('Cow Sacrifice', 'EGP', 350, 'Cow', 290, NULL, NULL, NULL, NULL, NULL),
//     ('Goat Sacrifice', 'EGP', 250, 'Goat', 290, NULL, NULL, NULL, NULL, NULL),
//     ('Initial Box', 'EGP', 100, NULL, NULL, NULL, NULL, NULL, 10, 'Oil, Pasta, Rice, Sugar');"
// );

// $db->runQuery(
//     "INSERT INTO `food_donation`.`donations` (
//         `donation_date`, `user_id`, `is_successful`
//     ) VALUES
//     ('2023-10-01 15:00:00', 1, TRUE),
//     ('2023-10-02 15:00:00', 2, FALSE),
//     ('2023-10-03 15:00:00', 3, FALSE),
//     ('2023-10-04 15:00:00', 4, TRUE),
//     ('2023-10-05 15:00:00', 5, FALSE);"
// );

// $db->runQuery(
//     "INSERT INTO `food_donation`.`donation_history` (
//         `total_cost`, `description`, `donation_id`, `meal_id`, `meal_cost`, `meal_quantity`, `raw_materials_id`, `raw_materials_cost`, `material_type`, `material_quantity`, `material_weight`, `material_supplier`, `client_ready_meal_id`, `client_ready_meal_cost`, `ready_meal_type`, `ready_meal_expiration`, `ready_meal_quantity`, `ready_meal_packaging_type`, `money_id`, `money_amount`, `money_donation_purpose`, `sacrifice_id`, `sacrifice_cost`, `box_id`, `box_cost`, `final_box_size`, `final_item_list`
//     ) VALUES
//     (50.00, 'Donation for meals and raw materials', 1, 1, 10.00, 5, 2, 20.00, 'Grains', 10, 5.0, 'Supplier A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
//     (100.00, 'Donation for ready meals', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 50.00, 'Frozen', '2024-12-31', 10, 'Plastic Wrap', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
//     (75.00, 'Monetary donation for a cause', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 75.00, 'Education', NULL, NULL, NULL, NULL, NULL, NULL),
//     (30.00, 'Donation for animal sacrifice', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 30.00, NULL, NULL, NULL, NULL),
//     (60.00, 'Donation for a custom box', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 60.00, 15.0, 'Oil, Pasta, Rice, Sugar');"
// );

// $db->runQuery(
//     "INSERT INTO `food_donation`.`extra_box_items` (
//         `extra_item_name`, `price_per_unit`
//     ) VALUES
//     ('Rice', 50),
//     ('Oil', 10),
//     ('Pasta', 20);"
// );
?>
