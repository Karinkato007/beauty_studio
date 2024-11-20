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

    // Prepare query to update status to 'pending'
    $query = "UPDATE bookings SET status = 'pending' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking set to pending.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to set booking to pending.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided.']);
}

$conn->close();
?>
