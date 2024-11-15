
<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // Check if user is logged in
$username = $isLoggedIn ? $_SESSION['username'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Check if email exists in session

$hasBooking = false;

if ($isLoggedIn && $email) {
    include('includes/db.php');

    // Use prepared statements for secure database queries
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE email = ? AND status IN ('pending', 'confirmed') LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $hasBooking = ($result->num_rows > 0);
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Beauty Studio</title>
</head>
<body class="bg-gray-100 text-gray-900">
<!-- Navbar -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between">
            <div class="flex space-x-4">
                <div>
                    <a href="index.php" class="flex items-center py-5 px-2 text-gray-700">
                        <span class="font-bold text-lg">Beauty Studio</span>
                    </a>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-1">
                <?php if ($isLoggedIn): ?>
                    <span class="py-5 px-3 text-gray-700">Hello, <?php echo htmlspecialchars($username); ?>!</span>
                    <a href="./booking/my_appointment.php" class="py-2 px-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                        My Appointment
                    </a>
                    <a href="user/logout.php" class="py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</a>
                <?php else: ?>
                    <a href="user/login.php" class="py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Login</a>
                    <a href="user/register.php" class="py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="bg-cover bg-center h-screen" style="background-image: url('images/studio-background.jpg');">
    <div class="flex items-center justify-center h-full bg-gray-900 bg-opacity-50">
        <div class="text-center">
            <h1 class="text-white text-5xl font-bold">Welcome to Our Beauty Studio</h1>
            <p class="text-gray-300 mt-4">Your beauty, our passion. Book an appointment today!</p>

            <?php if ($isLoggedIn && !$hasBooking): ?>
                <a href="./booking/booking.php" 
                   class="mt-6 inline-block bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600">
                   Book Now
                </a>
            <?php elseif ($isLoggedIn && $hasBooking): ?>
                <a href="./booking/my_appointment.php" 
                   class="mt-6 inline-block bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600">
                   View My Appointment
                </a>
            <?php else: ?>
                <a href="./user/login.php" class="mt-6 inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                   Login to Book Now
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Services Section -->
<section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-100 p-6 text-center">
                    <h3 class="text-xl font-semibold">Makeup</h3>
                    <p class="mt-4">Professional makeup services for every occasion.</p>
                </div>
                <div class="bg-gray-100 p-6 text-center">
                    <h3 class="text-xl font-semibold">Nail Art</h3>
                    <p class="mt-4">Get stunning nail designs to complement your style.</p>
                </div>
                <div class="bg-gray-100 p-6 text-center">
                    <h3 class="text-xl font-semibold">Hair Styling</h3>
                    <p class="mt-4">Stylish haircuts and styling for a fresh look.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 py-6 text-white text-center">
        <p>&copy; 2024 Beauty Studio. All Rights Reserved.</p>
    </footer>
</body>
</html>

