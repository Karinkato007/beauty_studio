<!-- login.php -->
<?php
session_start();

// Include the database connection
include('../includes/db.php');  // Adjust this path based on your file structure

// Redirect to the appropriate dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/admin.php");
        exit();
    } elseif ($_SESSION['role'] === 'employee') {
        header("Location: employee_dashboard.php");
        exit();
    } else {
        header("Location: ../index.php");
        exit();
    }
}

// Initialize error message variable
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if fields are filled
    if (empty($username) || empty($password)) {
        $error_message = "Please fill in both username and password!";
    } else {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password (use password_hash() when creating users)
            if (password_verify($password, $user['password'])) { // Assuming 'password' is the hashed password field
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/admin.php");
                } elseif ($user['role'] === 'employee') {
                    header("Location: employee_dashboard.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                $error_message = "Invalid username or password!";
            }
        } else {
            $error_message = "Invalid username or password!";
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
    <title>Login</title>
    <style>
        body {
            background-image: url('../images/login-background.jpg'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="">
    <?php include('formnav.php') ?>
    <div class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-10 lg:p-12 max-w-sm w-full">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome</h2>
            <?php if (isset($error)) echo "<p class='text-red-500 text-center'>$error</p>"; ?>
            <form method="POST" action="login.php" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" name="username" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" name="password" class="block w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Login</button>
                </div>
            </form>
            <p class="mt-4 text-center">Don't have an account? <a href="register.php" class="text-blue-500">Register now</a></p>
            <p class="mt-2 text-center"><a href="forgot_password.php" class="text-blue-500">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>
