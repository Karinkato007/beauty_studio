<?php
include('../includes/db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $service = trim($_POST['service']);
    $appointment_time = $_POST['appointment_time'];

    // Check if the selected time is in the past
    $current_time = date('Y-m-d H:i:s');
    if ($appointment_time < $current_time) {
        header("Location: booking.php?error=" . urlencode("You cannot book an appointment in the past!"));
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: booking.php?error=" . urlencode("Invalid email format!"));
        exit();
    }

    // Validate mobile number format (adjust regex as needed)
    if (!preg_match("/^\+?[0-9]{10,15}$/", $mobile)) {
        header("Location: booking.php?error=" . urlencode("Invalid mobile number format! Use +1234567890."));
        exit();
    }

    // Check if the appointment slot is already booked
    $check_query = "SELECT * FROM bookings WHERE appointment_time = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $appointment_time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: booking.php?error=" . urlencode("The selected time slot is already booked!"));
        exit();
    }

    // If not booked, proceed with booking
    $insert_query = "INSERT INTO bookings (name, email, mobile, service, appointment_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $name, $email, $mobile, $service, $appointment_time);
    
    if ($stmt->execute()) {
        header("Location: booking.php?success=true");
    } else {
        header("Location: booking.php?error=" . urlencode("Error: " . $stmt->error));
    }

    $stmt->close();
}
?>
