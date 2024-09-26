<!-- admin/delete_booking.php -->
<?php
include('../includes/db.php');
$id = $_GET['id'];

// Delete booking
$query = "DELETE FROM bookings WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header('Location: view_bookings.php');
} else {
    echo "Error deleting booking: " . mysqli_error($conn);
}
?>
