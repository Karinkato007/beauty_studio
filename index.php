<!-- index.php (Homepage) -->
<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // Check if user is logged in
$username = $isLoggedIn ? $_SESSION['username'] : '';
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
                    <!-- Logo -->
                    <div>
                        <a href="index.php" class="flex items-center py-5 px-2 text-gray-700">
                            <span class="font-bold text-lg">Beauty Studio</span>
                        </a>
                    </div>
                </div>

                <!-- Secondary Nav (Conditional) -->
                <div class="hidden md:flex items-center space-x-1">
                    <?php if ($isLoggedIn): ?>
                        <span class="py-5 px-3 text-gray-700">Hello, <?php echo htmlspecialchars($username); ?>!</span>
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
                <a href="booking/booking.php" class="mt-6 inline-block bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600">Book Now</a>
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

    <!-- Contact Section -->
    <section class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Contact Us</h2>
            <div class="flex justify-center">
                <form class="w-full max-w-lg">
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Your Name">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Your Email">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                            Message
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="message" rows="5" placeholder="Your Message"></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600" type="submit">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 py-6 text-white text-center">
        <p>&copy; 2024 Beauty Studio. All Rights Reserved.</p>
    </footer>
</body>
</html>
