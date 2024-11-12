<!-- admin/view_bookings.php -->
<?php
include('../includes/db.php');

// Fetch all bookings from the database
$query = "SELECT * FROM bookings ORDER BY appointment_time DESC";
$result = mysqli_query($conn, $query);

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
    <title>Manage Bookings</title>
</head>
<body class="bg-gray-100">
    <div class="bg-gray-100">
    <header class="bg-gray-800 text-white p-5">
        <h1 class="text-center text-3xl">Manage Bookings</h1>
    </header>

    <main class="py-10">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl mb-6">Booking List</h2>
            <table class="table-auto w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Service</th>
                        <th class="px-4 py-2">Appointment Time</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['username']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['email']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['services']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['appointment_time']; ?></td>
                        <td class="border px-4 py-2">
                            <a href="edit_booking.php?id=<?php echo $row['id']; ?>" class="text-blue-500">Edit</a> | 
                            <a href="delete_booking.php?id=<?php echo $row['id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    </div>
</body>
</html>
