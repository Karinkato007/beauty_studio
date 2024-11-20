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
    <header class="bg-gray-800 text-white p-5 flex items-center justify-between">
        <a href="./admin.php" ><span class="font-bold text-2xl">BACK</span></a>
        <h1 class="text-center text-3xl flex-1">Manage Bookings</h1>
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
                                <!-- Confirm Button -->
                                <?php if ($row['status'] != 'confirmed' && $row['status'] != 'canceled'): ?>
                                    <button onclick="confirmBooking(<?php echo $row['id']; ?>)" 
                                        id="confirm-<?php echo $row['id']; ?>"
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Confirm
                                    </button>
                                <?php endif; ?>

                                <!-- Pending Button -->
                                <?php if ($row['status'] != 'canceled' && $row['status'] != 'confirmed'): ?>
                                    <button onclick="setPending(<?php echo $row['id']; ?>)" 
                                        id="pending-<?php echo $row['id']; ?>"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                        Pending
                                    </button>
                                <?php endif; ?>

                                <!-- Cancel Button -->
                                <?php if ($row['status'] != 'canceled'): ?>
                                    <button onclick="cancelBooking(<?php echo $row['id']; ?>)" 
                                        id="cancel-<?php echo $row['id']; ?>"
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmBooking(bookingId) {
            // Show a loading status while processing
            $('#status-' + bookingId).text('Confirming...').removeClass().addClass('text-blue-500');

            $.ajax({
                url: 'confirm_booking_ajax.php',
                type: 'POST',
                data: { id: bookingId },
                success: function(response) {
                    if (response.success) {
                        // Update the status only if the server confirms success
                        $('#status-' + bookingId).text('Confirmed').removeClass().addClass('text-green-500');
                        showNotification('Booking confirmed successfully!', 'success');
                    } else {
                        // Revert to the previous status and show error message
                        $('#status-' + bookingId).text('Pending').removeClass().addClass('text-yellow-500');
                        showNotification(response.message || 'Failed to confirm booking.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    // Revert to the previous status and show error notification
                    $('#status-' + bookingId).text('Pending').removeClass().addClass('text-red-500');
                    showNotification('An error occurred: ' + error, 'error');
                }
            });
        }

        function setPending(bookingId) {
            $('#status-' + bookingId).text('Setting Pending...').removeClass().addClass('text-yellow-500');

            $.ajax({
                url: 'set_pending_ajax.php',
                type: 'POST',
                data: { id: bookingId },
                success: function(response) {
                    if (response.success) {
                        $('#status-' + bookingId).text('Pending').removeClass().addClass('text-yellow-500');
                        showNotification('Booking set to pending!', 'success');
                    } else {
                        $('#status-' + bookingId).text('Failed').removeClass().addClass('text-red-500');
                        showNotification(response.message || 'Failed to set pending.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    $('#status-' + bookingId).text('Failed').removeClass().addClass('text-red-500');
                    showNotification('An error occurred: ' + error, 'error');
                }
            });
        }

        function cancelBooking(bookingId) {
            // Show a loading status while processing
            $('#status-' + bookingId).text('Canceling...').removeClass().addClass('text-red-500');

            $.ajax({
                url: 'cancel_booking_ajax.php',
                type: 'POST',
                data: { id: bookingId },
                success: function(response) {
                    if (response.success) {
                        // Update the status and disable buttons
                        $('#status-' + bookingId).text('Canceled').removeClass().addClass('text-gray-500');
                        showNotification('Booking canceled successfully!', 'success');
                        // Disable all action buttons after cancellation
                        disableActionButtons(bookingId);
                    } else {
                        $('#status-' + bookingId).text('Pending').removeClass().addClass('text-yellow-500');
                        showNotification(response.message || 'Failed to cancel booking.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    $('#status-' + bookingId).text('Pending').removeClass().addClass('text-yellow-500');
                    showNotification('An error occurred: ' + error, 'error');
                }
            });
        }

        // Disable Confirm and Pending buttons after cancellation
        function disableActionButtons(bookingId) {
            $('#confirm-' + bookingId).prop('disabled', true).addClass('bg-gray-500 cursor-not-allowed');
            $('#pending-' + bookingId).prop('disabled', true).addClass('bg-gray-500 cursor-not-allowed');
            $('#cancel-' + bookingId).prop('disabled', true).addClass('bg-gray-500 cursor-not-allowed');
        }

        // Notification function to provide feedback to users
        function showNotification(message, type) {
            const notificationDiv = $('<div>')
                .addClass('notification p-2 rounded-md mb-2 text-white ' + (type === 'success' ? 'bg-green-500' : 'bg-red-500'))
                .text(message);
            $('body').append(notificationDiv);

            // Automatically remove the notification after 3 seconds
            setTimeout(function() {
                notificationDiv.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>
</body>
</html>
