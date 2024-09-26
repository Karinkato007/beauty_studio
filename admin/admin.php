<!-- admin/index.php -->
<?php
session_start();
include('../includes/db.php'); // Make sure this file establishes a connection and defines $conn

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../user/login.php'); // Redirect to login if not an admin
    exit;
}

// Fetch stock data
$query = "SELECT * FROM stock";
$result = mysqli_query($conn, $query);

// Check for query success
if (!$result) {
    die("Database query failed: " . mysqli_error($conn)); // Error handling if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white p-5">
        <h1 class="text-center text-3xl">Admin Dashboard</h1>
        <div class="text-center mt-2">
            <a href="../user/logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Logout
            </a>
        </div>
    </header>

    <main class="py-10">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl mb-6">Manage Your Studio</h2>
            <div class="space-y-4">
                <a href="view_booking.php" class="block bg-blue-500 text-white px-6 py-3 rounded-lg text-center">View & Manage Bookings</a>
                <a href="manage_stock.php" class="block bg-green-500 text-white px-6 py-3 rounded-lg text-center">View & Manage Stock</a>
            </div>
            
            <!-- Display Stock Data -->
            <h3 class="text-xl mt-8">Current Stock</h3>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="min-w-full bg-white border border-gray-200 mt-4">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Product Name</th>
                            <th class="border px-4 py-2">Quantity</th>
                            <th class="border px-4 py-2">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['last_updated']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="mt-4">No stock available.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
