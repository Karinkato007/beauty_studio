<?php
session_start();

// Include the database connection
include('../includes/db.php');

// Check if the employee is logged in
if (!isset($_SESSION['user_id'])) { // Updated session variable
    header("Location: login.php");
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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body class="bg-gray-100">

    <!-- Navigation -->
    <header class="bg-blue-700 shadow-lg p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-3xl font-bold">Admin Dashboard</h1>
            <a href="../user/logout.php" class="text-white bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 border border-red-700">Logout</a>
        </div>
    </header>

    <main class="container mx-auto py-10">
        <!-- Dashboard Overview -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-8">Dashboard Overview</h2>

        <!-- Real-time Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Bookings -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center transition hover:scale-105 border-t-4 border-blue-500">
                <h3 id="total_bookings" class="text-5xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600 mt-3">Total Bookings</p>
            </div>

            <!-- Total Stock -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center transition hover:scale-105 border-t-4 border-green-500">
                <h3 id="total_stock" class="text-5xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600 mt-3">Products in Stock</p>
            </div>

            <!-- Messages -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center transition hover:scale-105 border-t-4 border-yellow-500">
                <h3 id="total_messages" class="text-5xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600 mt-3">New Messages</p>
            </div>

            <!-- Admin Users (Optional) -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center transition hover:scale-105 border-t-4 border-red-500">
                <h3 id="total_admins" class="text-5xl font-bold text-gray-800">0</h3>
                <p class="text-gray-600 mt-3">Admin Users</p>
            </div>
        </div>

        <!-- Manage Sections -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Manage Your Studio</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="view_booking.php" class="bg-blue-600 text-white p-6 rounded-lg shadow-md text-center font-semibold text-lg transition hover:bg-blue-700 hover:shadow-lg">
                    View & Manage Bookings
                </a>
                <a href="manage_stock.php" class="bg-green-600 text-white p-6 rounded-lg shadow-md text-center font-semibold text-lg transition hover:bg-green-700 hover:shadow-lg">
                    View & Manage Stock
                </a>
            </div>
        </div>
    </main>

    <script>
        // Function to fetch the latest overview data
        function fetchOverviewData() {
            $.ajax({
                url: 'get_overview_data.php',
                type: 'GET',
                success: function(response) {
                    // Update the overview section with new data
                    $('#total_bookings').text(response.total_bookings);
                    $('#total_stock').text(response.total_stock);
                    $('#total_messages').text(response.total_messages);
                    $('#total_admins').text(response.total_admins);
                },
                error: function(error) {
                    console.log("Error fetching overview data:", error);
                }
            });
        }

        // Fetch the data every 10 seconds (or any interval you prefer)
        setInterval(fetchOverviewData, 10000); // 10 seconds

        // Initial fetch when page loads
        fetchOverviewData();
    </script>
</body>
</html>
