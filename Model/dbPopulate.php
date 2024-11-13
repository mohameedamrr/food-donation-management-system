<?php
require "DatabaseManager.php";
$db = DatabaseManager::getInstance();
$db->runQuery("DROP TABLE IF EXISTS `food_donation`.`users`");
$db->runQuery("CREATE DATABASE `food_donation`");
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
    "INSERT INTO `food_donation`.`users` (`id`, `name`, `email`, `phone`, `password`) VALUES
    (1, 'Bertha', 'bertha.wilkinson@example.com', '1234567890', 'b95925ed0aa3897a613c7534ae7abeef'),
    (2, 'Demarcus', 'joy.moen@example.net', '0987654321', '5dbd4fbe4edca5a5dd465968232fbf9e'),
    (3, 'Kyla', 'moore.osbaldo@example.com', '1112233445', 'b98dba1167a8c475d603a51ab9935315'),
    (4, 'Lacey', 'zdaniel@example.org', '9988776655', 'e5214e7ac6fce4abf561f03c8f30587a'),
    (5, '7amada', '7amada@belganzabeel.com', '1231231234', '25d55ad283aa400af464c76d713c07ad'),
    (6, 'Pearl', 'rjacobi@example.org', '5556667777', 'c1191c08cb4ae00632c4cf3444979bbd');"
);
?>