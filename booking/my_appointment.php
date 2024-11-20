<?php
// Start session to access user data
session_start();

// Database connection
include('../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

// Retrieve user details from session
$username = $_SESSION['username'];

// Fetch user email from the database
$email_query = "SELECT email FROM users WHERE username = '$username' LIMIT 1";
$email_result = mysqli_query($conn, $email_query);

if ($email_result && mysqli_num_rows($email_result) > 0) {
    $email_row = mysqli_fetch_assoc($email_result);
    $email = $email_row['email'];
} else {
    echo "Error: Email not found for the logged-in user.";
    exit();
}

// Fetch booking details
$booking_query = "SELECT * FROM bookings WHERE email = '$email' AND status IN ('pending', 'confirmed') LIMIT 1";
$booking_result = mysqli_query($conn, $booking_query);

// Handle no booking scenario
if (!$booking_result || mysqli_num_rows($booking_result) == 0) {
    echo "<script>
            alert('No active bookings found.');
            window.location.href = '../index.php';
          </script>";
    exit();
}

// Fetch the booking details
$booking = mysqli_fetch_assoc($booking_result);

// Handle cancelation
if (isset($_POST['cancel_booking'])) {
    $cancel_query = "UPDATE bookings SET status = 'canceled' WHERE id = {$booking['id']}";
    if (mysqli_query($conn, $cancel_query)) {
        echo "<script>
                alert('Your booking has been canceled.');
                window.location.href = '../index.php';
              </script>";
    } else {
        echo "<script>alert('Error canceling your booking.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>My Appointment</title>
</head>
<body class="bg-gray-100">
    <header class="bg-pink-600 text-white p-5 shadow-md flex items-center justify-between">
    <a href="../index.php" >
                    <span class="font-bold text-3xl">Beauty Studio</span>    
                </a>
        <h1 class="text-center text-3xl font-bold flex-1">My Appointment</h1>
    </header>


    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <h2 class="text-2xl font-semibold mb-4">Your Appointment Details</h2>
            <ul class="space-y-3">
                <li><strong>Services:</strong> <?php echo htmlspecialchars($booking['services']); ?></li>
                <li><strong>Appointment Time:</strong> <?php echo htmlspecialchars($booking['appointment_time']); ?></li>
                <li><strong>Total Duration:</strong> <?php echo htmlspecialchars($booking['total_duration']); ?> minutes</li>
                <li><strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?></li>
            </ul>

            <div class="mt-6">
                <form method="POST">
                    <button type="submit" name="cancel_booking" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                        Cancel Appointment
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
