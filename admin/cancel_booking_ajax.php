<?php
header('Content-Type: application/json');
include('../includes/db.php');

// Ensure database connection
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if (isset($_POST['id'])) {
    $bookingId = mysqli_real_escape_string($conn, $_POST['id']);

    // Prepare query to update status to 'canceled'
    $query = "UPDATE bookings SET status = 'canceled' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking canceled successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel booking.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided.']);
}

$conn->close();
?>
