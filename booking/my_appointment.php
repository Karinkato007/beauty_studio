<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../user/login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch the user's booking details
$query = "SELECT * FROM bookings WHERE email = '$email' AND status IN ('pending', 'confirmed') LIMIT 1";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "No active appointments found.";
    exit();
}

// Prevent the browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
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

    <header class="bg-pink-600 text-white p-5 shadow-md">
        <h1 class="text-center text-3xl font-bold">My Appointment</h1>
    </header>

    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800">Booking Details</h2>
            <p><strong>Services:</strong> <?php echo htmlspecialchars($booking['services']); ?></p>
            <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($booking['appointment_time']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?></p>

            <a href="delete_appointment.php" class="mt-4 inline-block bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">Cancel Appointment</a>
        </div>
    </main>

</body>
</html>
