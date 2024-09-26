<!-- register.php -->
<?php
session_start();
include('../includes/db.php');  // Ensure the path is correct
// if (isset($conn)) {
//     echo "Database connection successful!";
// } else {
//     echo "Database connection failed!";
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'costumer';  // Default role

    // Check if username or email is already taken
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already taken!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            // Redirect to login after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Register</title>
    <style>
        body {
            background-image: url('../images/login-background.jpg'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            height: 100vh; /* Ensure the body takes full height */
        }
    </style>
</head>
<body>
    <div class="flex items-center justify-center bg-gray-900 bg-opacity-50 h-screen">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-10 lg:p-12 max-w-sm w-full">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Register</h2>
            <?php if (isset($error)) echo "<p class='text-red-500 text-center'>$error</p>"; ?>
            <form method="POST" action="register.php" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" name="username" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" name="email" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" name="password" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <!-- <div>
                    <label for="role" class="block text-gray-700">Role:</label>
                    <select name="role" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                        <option value="employee">Employee</option>
                    </select>

                </div> -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Register</button>
                </div>
            </form>
            <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-blue-500">Login now</a></p>
        </div>
    </div>
</body>
</html>
