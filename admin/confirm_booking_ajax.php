<?php
include('../includes/db.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the booking ID from the POST request
    $bookingId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Log the received booking ID (for debugging)
    error_log("Received Booking ID: " . $bookingId);

    if ($bookingId > 0) {
        // Prepare the SQL query to update booking status to 'confirmed'
        $query = "UPDATE bookings SET status = 'confirmed' WHERE id = ?";
        $stmt = $conn->prepare($query);

        // Check for query preparation errors
        if (!$stmt) {
            error_log("SQL Preparation Error: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL query.']);
            exit;
        }

        // Bind the parameter (booking ID) and execute the query
        $stmt->bind_param("i", $bookingId);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true]);
        } else {
            // Return error message with error details
            error_log("SQL Execution Error: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Invalid booking ID, return an error message
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
    }
} else {
    // Invalid request method (not POST)
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
