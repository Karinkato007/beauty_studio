<?php
include('../includes/db.php');
// Assuming you have already established a connection to the database

$query = "SELECT * FROM bookings"; // Replace 'bookings' with your actual table name
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Handle query errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Manage Bookings</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
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
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr id="booking-<?php echo $row['id']; ?>">
                            <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['username']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['email']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['services']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['appointment_time']; ?></td>
                            <td class="border px-4 py-2" id="status-<?php echo $row['id']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </td>
                            <td class="border px-4 py-2">
                                <?php if ($row['status'] != 'confirmed'): ?>
                                    <button onclick="confirmBooking(<?php echo $row['id']; ?>)" 
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Confirm
                                    </button>
                                <?php endif; ?>
                                <?php if ($row['status'] != 'canceled'): ?>
                                    <button onclick="cancelBooking(<?php echo $row['id']; ?>)" 
                                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        Cancel
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="notification" class="fixed bottom-5 right-5 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg"></div>

    <script>
        function confirmBooking(bookingId) {
            // Update the status immediately in the table
                $('#status-' + bookingId).text('Confirming...');

                $.ajax({
                    url: 'confirm_booking_ajax.php',
                    type: 'POST',
                    data: { id: bookingId },
                    success: function(response) {
                        if (response.success) {
                            // Update the status cell in the table
                            $('#status-' + bookingId).text('Confirmed');
                            // Show success notification
                            showNotification('Booking confirmed successfully!', 'success');
                        } else {
                            // In case of error, restore the old status
                            $('#status-' + bookingId).text('Failed');
                            // Show error notification
                            showNotification(response.message || 'Failed to confirm booking.', 'error');
                        }
                    },
                    error: function() {
                        // In case of error, restore the old status
                        $('#status-' + bookingId).text('Failed');
                        // Show error notification
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }

            function cancelBooking(bookingId) {
                // Update the status immediately in the table
                $('#status-' + bookingId).text('Canceling...');

                $.ajax({
                    url: 'cancel_booking_ajax.php',
                    type: 'POST',
                    data: { id: bookingId },
                    success: function(response) {
                        if (response.success) {
                            // Update the status cell in the table
                            $('#status-' + bookingId).text('Canceled');
                            // Show success notification
                            showNotification('Booking canceled successfully!', 'success');
                        } else {
                            // In case of error, restore the old status
                            $('#status-' + bookingId).text('Failed');
                            // Show error notification
                            showNotification(response.message || 'Failed to cancel booking.', 'error');
                        }
                    },
                    error: function() {
                        // In case of error, restore the old status
                        $('#status-' + bookingId).text('Failed');
                        // Show error notification
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }

            function showNotification(message, type) {
                const notification = $('#notification');
                notification.removeClass('hidden');
                notification.text(message);
                notification.removeClass('bg-green-500 bg-red-500');
                notification.addClass(type === 'success' ? 'bg-green-500' : 'bg-red-500');

                setTimeout(() => notification.addClass('hidden'), 3000);
            }

    </script>
</body>
</html>
