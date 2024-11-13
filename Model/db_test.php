<?php
require_once 'DatabaseManager.php';
$dbManager = DatabaseManager::getInstance();
$dbManager->runQuery("INSERT INTO test (name) VALUES
('Alice'),
('Bob'),
('Charlie'),
('David'),
('Eva'),
('Frank'),
('Grace'),
('Hannah'),
('Ivy'),
('Jack'),
('Kara'),
('Leo'),
('Mona'),
('Nate'),
('Olivia'),
('Paul'),
('Quincy'),
('Rachel'),
('Sam'),
('Tina');");
