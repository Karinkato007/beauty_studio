<?php
// Start the session to check for logged-in users
session_start();

// Include the database connection
include('../includes/db.php');

// Check if the employee is logged in
if (!isset($_SESSION['user_id'])) { // Updated session variable
    header("Location: login.php");
    exit();
}

// Debugging line to see if session ID is set
// echo "Session ID: " . $_SESSION['user_id']; 

// Initialize variables
$messages = [];
$stocks = [];
$bookings = [];

// Fetch contact messages
$queryMessages = "SELECT * FROM contact_messages";
$resultMessages = $conn->query($queryMessages);

if (!$resultMessages) {
    die("Query failed: " . $conn->error); // Debugging line for errors
}

if ($resultMessages->num_rows > 0) {
    while ($row = $resultMessages->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Fetch stocks
$queryStocks = "SELECT * FROM stock";
$resultStocks = $conn->query($queryStocks);

if (!$resultStocks) {
    die("Query failed: " . $conn->error); // Debugging line for errors
}

if ($resultStocks->num_rows > 0) {
    while ($row = $resultStocks->fetch_assoc()) {
        $stocks[] = $row;
    }
}

// Fetch bookings
$queryBookings = "SELECT * FROM bookings";
$resultBookings = $conn->query($queryBookings);

if (!$resultBookings) {
    die("Query failed: " . $conn->error); // Debugging line for errors
}

if ($resultBookings->num_rows > 0) {
    while ($row = $resultBookings->fetch_assoc()) {
        $bookings[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../css/tailwind.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Employee Dashboard Header -->
    <div class="bg-gray-800 text-white p-4">
        <h1 class="text-3xl">Employee Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="logout.php" class="text-red-500">Logout</a>
    </div>

    <!-- Stocks Section -->
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold">Current Stock</h2>

        <?php if (!empty($stocks)): ?>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Product Name</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($stock['product_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($stock['quantity']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($stock['last_updated']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stock information available.</p>
        <?php endif; ?>
    </div>

    <!-- Inside Bookings Section in employee_dashboard.php -->
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold">Bookings</h2>

        <?php if (!empty($bookings)): ?>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Customer Name</th>
                        <th class="border px-4 py-2">Date and Time</th>
                        <th class="border px-4 py-2">Service</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['username']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['appointment_time']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($booking['services']); ?></td>
                            <td class="border px-4 py-2"><?php echo ucfirst($booking['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings available.</p>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>
