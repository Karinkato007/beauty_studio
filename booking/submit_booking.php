<?php
// Start the session to access user data
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../includes/db.php'); // Connect to the database

// Service durations (in minutes)
$service_durations = [
    'Makeup' => 120,    // 2 hours
    'Nail Art' => 60,   // 1 hour
    'Lashes' => 120,    // 2 hours
    'Hairstyle' => 60   // 1 hour
];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure required fields are set
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['mobile']) || !isset($_POST['services']) || !isset($_POST['appointment_time'])) {
        header("Location: booking.php?error=" . urlencode("All fields are required."));
        exit();
    }

    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $services = $_POST['services'];
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    // Calculate total duration based on selected services
    $total_duration = 0;
    foreach ($services as $service) {
        if (isset($service_durations[$service])) {
            $total_duration += $service_durations[$service];
        }
    }
    if ($total_duration === 0) {
        header("Location: booking.php?error=" . urlencode("Invalid service selection."));
        exit();
    }

    // Calculate end time
    $appointment_datetime = new DateTime($appointment_time);
    $appointment_end_time = clone $appointment_datetime;
    $appointment_end_time->modify("+$total_duration minutes");
    $end_time = $appointment_end_time->format('Y-m-d H:i:s');

    // Check if the user already has an active booking
    $check_query = $conn->prepare(
        "SELECT * FROM bookings 
         WHERE email = ? 
           AND status IN ('pending', 'confirmed') 
           AND appointment_time > NOW()"
    );
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        $existingBooking = $check_result->fetch_assoc(); // Debugging
        error_log("Existing booking found: " . json_encode($existingBooking)); // Debug log
        header("Location: booking.php?error=" . urlencode("You already have an active booking."));
        exit();
    }
    $check_query->close();

    // Combine selected services into a single string for storage
    $services_string = implode(', ', $services);

    // Prepare the SQL query to insert the booking into the database
    $query = $conn->prepare(
        "INSERT INTO bookings (username, email, mobile, services, appointment_time, total_duration, end_time, status, created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())"
    );
    $query->bind_param("sssssis", $username, $email, $mobile, $services_string, $appointment_time, $total_duration, $end_time);

    // Execute the query and check if it was successful
    if ($query->execute()) {
        // Redirect to booking page with success message
        header("Location: booking.php?success=1");
        exit();
    } else {
        // Redirect with an error message if there was a problem
        header("Location: booking.php?error=" . urlencode("Database Error: " . $query->error));
        exit();
    }
} else {
    // Redirect back if accessed without POST method
    header("Location: booking.php");
    exit();
}
?>
