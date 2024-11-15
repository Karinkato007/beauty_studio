<!-- admin/edit_booking.php -->
<?php
include('../includes/db.php');
$id = $_GET['id'];

// Fetch the booking by ID
$query = "SELECT * FROM bookings WHERE id = $id";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $service = $_POST['service'];
    $appointment_time = $_POST['appointment_time'];

    // Update the booking
    $update_query = "UPDATE bookings SET name = '$name', email = '$email', service = '$service', appointment_time = '$appointment_time' WHERE id = $id";
    if (mysqli_query($conn, $update_query)) {
        header('Location: view_booking.php');
    } else {
        echo "Error updating booking: " . mysqli_error($conn);
    }
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
    <title>Edit Booking</title>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white p-5">
        <h1 class="text-center text-3xl">Edit Booking</h1>
    </header>

    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow p-8 rounded-lg">
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="<?php echo $booking['username']; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo $booking['email']; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Service</label>
                    <select name="service" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Makeup" <?php echo $booking['services'] == 'Makeup' ? 'selected' : ''; ?>>Makeup</option>
                        <option value="Nail Art" <?php echo $booking['services'] == 'Nail Art' ? 'selected' : ''; ?>>Nail Art</option>
                        <option value="Lashes" <?php echo $booking['services'] == 'Lashes' ? 'selected' : ''; ?>>Lashes</option>
                        <option value="Hairstyle" <?php echo $booking['services'] == 'Hairstyle' ? 'selected' : ''; ?>>Hairstyle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="appointment_time" value="<?php echo date('Y-m-d\TH:i', strtotime($booking['appointment_time'])); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Update Booking</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
