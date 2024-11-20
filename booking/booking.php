<?php
// Start session to access logged-in user data
session_start();

// Database connection (adjust path as needed)
include('../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

// Get user details from the session
$username = $_SESSION['username'];

// Fetch user email
$email_query = "SELECT email FROM users WHERE username = '$username' LIMIT 1";
$email_result = mysqli_query($conn, $email_query);

if ($email_result && mysqli_num_rows($email_result) > 0) {
    $email_row = mysqli_fetch_assoc($email_result);
    $email = $email_row['email'];
} else {
    error_log("Error: Unable to retrieve email for user $username");
    echo "Error: Unable to retrieve email.";
    exit();
}

// Check if the user already has a booking
$existingBookingQuery = "SELECT * FROM bookings WHERE email = '$email' AND status IN ('pending', 'confirmed') LIMIT 1";
$existingBookingResult = mysqli_query($conn, $existingBookingQuery);

if (!$existingBookingResult) {
    error_log("Query Error: " . mysqli_error($conn));
    $hasBooking = false;
} else {
    $hasBooking = (mysqli_num_rows($existingBookingResult) > 0);
    if ($hasBooking) {
        $existingBooking = mysqli_fetch_assoc($existingBookingResult);
        error_log("Existing booking found for $email: " . json_encode($existingBooking));
    }
}

// Check for success message
$successMessage = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Book Appointment</title>
</head>
<body class="bg-gray-100">

    <header class="bg-pink-600 text-white p-5 shadow-md flex items-center justify-between">
        <a href="../index.php" ><span class="font-bold text-3xl">Beauty Studio</span></a>
        <h1 class="text-center text-3xl font-bold flex-1">Book Your Appointment</h1>
    </header>
    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <?php if ($hasBooking && !$successMessage): ?>
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Appointment Already Booked',
                        text: 'You already have an appointment booked. Please view or manage your appointment.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'my_appointment.php';
                    });
                </script>
            <?php elseif ($successMessage): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your appointment has been booked successfully.',
                        confirmButtonText: 'OK'
                    });
                </script>
            <?php endif; ?>

            <form action="submit_booking.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                        readonly required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                        readonly required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <input type="tel" name="mobile" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                        required placeholder="e.g. +1234567890">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Select Services</label>
                    <div class="flex flex-wrap gap-4 mt-1">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="services[]" value="Makeup" class="form-checkbox h-5 w-5 text-pink-600">
                            <span class="text-gray-700">Makeup (2 hours)</span>
                        </label>
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="services[]" value="Nail Art" class="form-checkbox h-5 w-5 text-pink-600">
                            <span class="text-gray-700">Nail Art (1 hour)</span>
                        </label>
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="services[]" value="Lashes" class="form-checkbox h-5 w-5 text-pink-600">
                            <span class="text-gray-700">Lashes (2 hours)</span>
                        </label>
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="services[]" value="Hairstyle" class="form-checkbox h-5 w-5 text-pink-600">
                            <span class="text-gray-700">Hairstyle (1 hour)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="appointment_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                        min="<?php echo date('Y-m-d\TH:i'); ?>" 
                        id="appointment_time" required>
                </div>

                <div>
                    <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition duration-200">
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
