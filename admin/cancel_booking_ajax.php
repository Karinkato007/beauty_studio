<?php
include('../includes/db.php'); // Connect to the database

// Ensure ID is passed
if (isset($_POST['id'])) {
    $bookingId = mysqli_real_escape_string($conn, $_POST['id']);

    // Update the status to 'canceled'
    $query = "UPDATE bookings SET status = 'canceled' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return failure response
        echo json_encode(['success' => false, 'message' => 'Failed to cancel booking.']);
    }
} else {
    // Return failure response if no ID is passed
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided.']);
}
?>
