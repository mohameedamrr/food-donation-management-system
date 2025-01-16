<?php
session_start();

if (isset($_SESSION['cart'])) {
    $count = count($_SESSION['cart']);
} else {
    $count = 0;
}

echo json_encode(['count' => $count]);
?>