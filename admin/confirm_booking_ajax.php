<?php
include('../includes/db.php');

// Check if the booking ID is provided
if (isset($_POST['id'])) {
    $bookingId = $_POST['id'];

    // Fetch the current booking details to calculate end_time
    $stmt = $conn->prepare("SELECT appointment_time, total_duration FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        // Calculate end_time by adding total_duration to appointment_time
        $appointmentTime = new DateTime($booking['appointment_time']);
        $endTime = $appointmentTime->add(new DateInterval('PT' . $booking['total_duration'] . 'M'));

        // Update the booking status to 'confirmed' and set the end_time
        $updateStmt = $conn->prepare("UPDATE bookings SET status = 'confirmed', end_time = ? WHERE id = ?");
        $updateStmt->bind_param("si", $endTime->format('Y-m-d H:i:s'), $bookingId);

        if ($updateStmt->execute()) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return failure response
            echo json_encode(['success' => false, 'message' => 'Failed to update booking status.']);
        }

        $updateStmt->close();
    } else {
        // Return failure if booking does not exist
        echo json_encode(['success' => false, 'message' => 'Booking not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
}

$conn->close();
?>
