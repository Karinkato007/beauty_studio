<?php
header('Content-Type: application/json');
include('../includes/db.php');

// Ensure database connection
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if (isset($_POST['id'])) {
    $bookingId = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    if (!$bookingId) {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
        exit;
    }

    // Prepare query to update status to 'confirmed'
    $query = "UPDATE bookings SET status = 'confirmed' WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $bookingId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking confirmed successfully.']);
        } else {
            error_log("Error confirming booking ID $bookingId: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Failed to confirm booking.']);
        }
        $stmt->close();
    } else {
        error_log("Error preparing statement: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the query.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided.']);
}

$conn->close();
?>
