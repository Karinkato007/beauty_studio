<?php
include('../includes/db.php');

// Fetch total bookings
$bookings_query = "SELECT COUNT(*) AS total_bookings FROM bookings";
$bookings_result = mysqli_query($conn, $bookings_query);
$bookings_data = mysqli_fetch_assoc($bookings_result);
$total_bookings = $bookings_data['total_bookings'];

// Fetch total stock
$stock_query = "SELECT SUM(quantity) AS total_stock FROM stock";
$stock_result = mysqli_query($conn, $stock_query);
$stock_data = mysqli_fetch_assoc($stock_result);
$total_stock = $stock_data['total_stock'];

// Fetch total messages
$messages_query = "SELECT COUNT(*) AS total_messages FROM contact_messages";
$messages_result = mysqli_query($conn, $messages_query);
$messages_data = mysqli_fetch_assoc($messages_result);
$total_messages = $messages_data['total_messages'];

// Fetch total admin users (optional)
$admin_query = "SELECT COUNT(*) AS total_admins FROM users WHERE role = 'admin'";
$admin_result = mysqli_query($conn, $admin_query);
$admin_data = mysqli_fetch_assoc($admin_result);
$total_admins = $admin_data['total_admins'];

// Return data as JSON
$response = [
    'total_bookings' => $total_bookings,
    'total_stock' => $total_stock,
    'total_messages' => $total_messages,
    'total_admins' => $total_admins
];

header('Content-Type: application/json');
echo json_encode($response);
?>
