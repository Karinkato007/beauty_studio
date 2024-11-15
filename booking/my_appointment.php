<?php
session_start();
include('../includes/db.php');

// Ensure database connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Redirect if user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../user/login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch the user's booking details securely
$stmt = $conn->prepare("SELECT * FROM bookings WHERE email = ? AND status IN ('pending', 'confirmed') LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query returned a result
if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
    $noAppointmentMessage = "You haven't booked an appointment.";
}

$stmt->close();

// Handle form submission for updating the appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($booking)) {
    // Validate and sanitize form data
    $newService = htmlspecialchars(strip_tags($_POST['services']));
    $newTime = htmlspecialchars(strip_tags($_POST['appointment_time']));

    // Update the appointment in the database
    $updateStmt = $conn->prepare("UPDATE bookings SET services = ?, appointment_time = ? WHERE email = ? AND status IN ('pending', 'confirmed')");
    $updateStmt->bind_param("sss", $newService, $newTime, $email);

    if ($updateStmt->execute()) {
        $booking['services'] = $newService;
        $booking['appointment_time'] = $newTime;
        $updateMessage = "<p class='text-green-500'>Appointment updated successfully!</p>";
    } else {
        $updateMessage = "<p class='text-red-500'>Failed to update appointment. Please try again.</p>";
    }

    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <title>My Appointment</title>
</head>
<body class="bg-gray-100">
    <header class="bg-pink-600 text-white p-5 shadow-md">
        <h1 class="text-center text-3xl font-bold">My Appointment</h1>
    </header>

    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <?php if (isset($noAppointmentMessage)): ?>
                <p class="text-center text-red-500 text-xl font-bold"><?php echo $noAppointmentMessage; ?></p>
            <?php else: ?>
                <h2 class="text-xl font-bold text-gray-800">Booking Details</h2>
                <p><strong>Services:</strong> <?php echo htmlspecialchars($booking['services']); ?></p>
                <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($booking['appointment_time']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?></p>

                <?php if (isset($updateMessage)) echo $updateMessage; ?>

                <form method="POST" action="" class="mt-6">
                    <label for="services" class="block text-gray-700">Change Service:</label>
                    <input type="text" name="services" id="services" value="<?php echo htmlspecialchars($booking['services']); ?>" 
                           class="shadow border rounded w-full py-2 px-3 mb-4" required>

                    <label for="appointment_time" class="block text-gray-700">Change Appointment Time:</label>
                    <input type="datetime-local" name="appointment_time" id="appointment_time" 
                           value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($booking['appointment_time']))); ?>" 
                           class="shadow border rounded w-full py-2 px-3 mb-4" required>

                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                        Update Appointment
                    </button>
                </form>

                <a href="delete_appointment.php" class="mt-4 inline-block bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">
                    Cancel Appointment
                </a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
