<?php
include('../includes/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the booking ID is set in the URL
if (isset($_GET['id'])) {
    $delete_id = intval($_GET['id']); // Get and sanitize the ID

    // Delete query
    $delete_query = "DELETE FROM bookings WHERE id = $delete_id";

    if (mysqli_query($conn, $delete_query)) {
        // Redirect to view bookings page with a success message
        header('Location: view_booking.php?message=Booking deleted successfully');
        exit(); // Stop the script
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "No booking ID specified!";
}

// Prevent the browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
