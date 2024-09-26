<!-- booking.php -->
<?php
// Display success or error messages
if (isset($_GET['success'])) {
    echo '<div class="bg-green-500 text-white p-4 rounded-lg text-center">Appointment booked successfully!</div>';
} elseif (isset($_GET['error'])) {
    echo '<div class="bg-red-500 text-white p-4 rounded-lg text-center">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Book Appointment</title>
</head>
<body class="bg-gray-100">

    <header class="bg-pink-600 text-white p-5 shadow-md">
        <h1 class="text-center text-3xl font-bold">Book Your Appointment</h1>
    </header>

    <main class="py-10">
        <div class="max-w-xl mx-auto bg-white shadow-lg p-8 rounded-lg">
            <form action="submit_booking.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <input type="tel" name="mobile" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" required placeholder="e.g. +1234567890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Service</label>
                    <select name="service" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" required>
                        <option value="">Select a service</option>
                        <option value="Makeup">Makeup</option>
                        <option value="Nail Art">Nail Art</option>
                        <option value="Lashes">Lashes</option>
                        <option value="Hairstyle">Hairstyle</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="appointment_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-pink-500 focus:border-pink-500" 
                           min="<?php echo date('Y-m-d\TH:i'); ?>" 
                           id="appointment_time" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition duration-200">Book Appointment</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
            <h2 class="text-lg font-bold">Appointment Booked!</h2>
            <p>Your appointment has been successfully booked.</p>
            <div class="mt-4">
                <button id="closeModal" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Disable past date & time
        const now = new Date().toISOString().slice(0, 16);
        document.getElementById("appointment_time").setAttribute("min", now);

        // Check if the URL contains a success parameter and show the modal
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        // Close the modal
        document.getElementById('closeModal').onclick = function() {
            document.getElementById('confirmationModal').classList.add('hidden');
        };
    </script>
</body>
</html>
