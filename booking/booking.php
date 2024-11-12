<?php
// Start session to access logged-in user data
session_start();

// Database connection (adjust path as needed)
include('../includes/db.php');

// Check if user is logged in (make sure the session has the username)
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: ../user/login.php");
    exit();
}

// Get user details from the session
$username = $_SESSION['username'];

// Get email from the users table
$email_query = "SELECT email FROM users WHERE username = '$username' LIMIT 1";
$email_result = mysqli_query($conn, $email_query);
if ($email_result && mysqli_num_rows($email_result) > 0) {
    $email_row = mysqli_fetch_assoc($email_result);
    $email = $email_row['email'];
} else {
    // Handle error if email is not found (this should not happen if user is logged in)
    echo "Error: Unable to retrieve email.";
    exit();
}

// Get already booked times for the services (to block those times)
$bookedTimes = [];
$query = "SELECT appointment_time FROM bookings WHERE status IN ('pending', 'confirmed')";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = $row['appointment_time'];
    }
}

// Check for success message
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<div class='bg-green-500 text-white p-4 rounded-lg mb-4'>
            <strong>Success!</strong> Your appointment has been booked successfully.
          </div>";
}

// Display success or error messages
if (isset($_GET['success'])) {
    echo '<div class="bg-green-500 text-white p-4 rounded-lg text-center">Appointment booked successfully!</div>';
} elseif (isset($_GET['error'])) {
    echo '<div class="bg-red-500 text-white p-4 rounded-lg text-center">' . htmlspecialchars($_GET['error']) . '</div>';
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
    <title>Book Appointment</title>
</head>
<body class="bg-gray-100">

    <header class="bg-pink-600 text-white p-5 shadow-md">
        <h1 class="text-center text-3xl font-bold">Book Your Appointment</h1>
    </header>

    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <form action="submit_booking.php" method="POST" class="space-y-6">
                <!-- Remove Full Name field, use session's username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                           readonly required>
                </div>

                <!-- Automatically populate email field from the database -->
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

                <!-- Services Section -->
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
                    <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition duration-200">Book Appointment</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
