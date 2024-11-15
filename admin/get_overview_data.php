<?php
include('../includes/db.php');

// Initialize response array
$response = [
    'total_bookings' => 0,
    'pending_bookings' => 0,
    'confirmed_bookings' => 0,
    'total_stock' => 0
];

// Fetch total bookings
$totalQuery = "SELECT COUNT(*) AS total FROM bookings";
$result = mysqli_query($conn, $totalQuery);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response['total_bookings'] = $row['total'];
}

// Fetch pending bookings
$pendingQuery = "SELECT COUNT(*) AS pending FROM bookings WHERE status = 'pending'";
$result = mysqli_query($conn, $pendingQuery);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response['pending_bookings'] = $row['pending'];
}

// Fetch confirmed bookings
$confirmedQuery = "SELECT COUNT(*) AS confirmed FROM bookings WHERE status = 'confirmed'";
$result = mysqli_query($conn, $confirmedQuery);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response['confirmed_bookings'] = $row['confirmed'];
}

// Fetch total stock
$stockQuery = "SELECT SUM(quantity) AS total_stock FROM stock";
$result = mysqli_query($conn, $stockQuery);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $response['total_stock'] = $row['total_stock'];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
