<?php
include('../includes/db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get customer data from session
    session_start();
    $email = $_SESSION['email']; // Assuming email is saved in session
    $username = $_SESSION['username']; // Assuming username is saved in session
    $mobile = $_POST['mobile']; // Mobile number from form input
    $services = $_POST['services']; // Array of selected services
    $appointment_time = $_POST['appointment_time'];

    // Service duration map
    $service_durations = [
        'Makeup' => 2,    // 2 hours
        'Nail Art' => 1,  // 1 hour
        'Lashes' => 2,    // 2 hours
        'Hairstyle' => 1, // 1 hour
    ];

    // Validate the appointment time
    if (strtotime($appointment_time) === false) {
        header("Location: booking.php?error=" . urlencode("Invalid appointment time!"));
        exit();
    }

    // Calculate total duration based on selected services
    $total_duration = 0;
    foreach ($services as $service) {
        if (isset($service_durations[$service])) {
            $total_duration += $service_durations[$service];
        }
    }

    if ($total_duration <= 0) {
        header("Location: booking.php?error=" . urlencode("Invalid service duration!"));
        exit();
    }

    // Calculate end time based on total duration
    $end_time = date('Y-m-d H:i:s', strtotime($appointment_time . " +$total_duration hours"));

    // Check if the customer already has an active booking
    $check_query = "SELECT * FROM bookings WHERE email = '$email' AND (status = 'pending' OR status = 'confirmed')";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        header("Location: booking.php?error=" . urlencode("You already have an active booking! Please cancel your existing booking to make a new one."));
        exit();
    }

    // Check if the selected time is in the past
    $current_time = date('Y-m-d H:i:s');
    if ($appointment_time < $current_time) {
        header("Location: booking.php?error=" . urlencode("You cannot book an appointment in the past!"));
        exit();
    }

    // Check for overlapping appointments
    $check_slot_query = "SELECT * FROM bookings WHERE 
                        (appointment_time < '$end_time' AND DATE_ADD(appointment_time, INTERVAL $total_duration HOUR) > '$appointment_time')";
    $result_slot = mysqli_query($conn, $check_slot_query);

    if (mysqli_num_rows($result_slot) > 0) {
        header("Location: booking.php?error=" . urlencode("The selected time slot is already booked!"));
        exit();
    }

    // Prepare services as a string
    $services_str = implode(",", $services);

    // Insert the new booking with name, email, and mobile
    $insert_query = "INSERT INTO bookings (username, email, mobile, services, appointment_time, status) 
                     VALUES ('$username', '$email', '$mobile', '$services_str', '$appointment_time', 'pending')";

    if (mysqli_query($conn, $insert_query)) {
        // Redirect to 'my_appointment.php' after booking with success message
        header("Location: booking.php?success=1");
    } else {
        header("Location: booking.php?error=" . urlencode("Error: " . mysqli_error($conn)));
    }
}
?>
