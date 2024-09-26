<!-- forgot_password.php -->
<?php
session_start();
include('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a reset token and store it (you should ideally store it in a database)
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 3600; // 1 hour expiry time

        // Store the token in the database (you need to create a tokens table for this)
        $query = "INSERT INTO password_resets (email, token, expires) VALUES ('$email', '$token', '$expires')";
        mysqli_query($conn, $query);

        // Send email with the reset link
        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "To reset your password, click the link: $reset_link";
        mail($email, $subject, $message); // Use a mailing function or library

        $_SESSION['success'] = "Password reset link has been sent to your email.";
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Forgot Password</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-sm mx-auto mt-10">
        <h2 class="text-2xl mb-6">Forgot Password</h2>
        <?php if (isset($error)) echo "<p class='text-red-500'>$error</p>"; ?>
        <?php if (isset($_SESSION['success'])) echo "<p class='text-green-500'>{$_SESSION['success']}</p>"; ?>
        <form method="POST" action="forgot_password.php" class="space-y-4">
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" class="block w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Send Reset Link</button>
            </div>
        </form>
        <p class="mt-4">Remembered your password? <a href="login.php" class="text-blue-500">Login now</a></p>
    </div>
</body>
</html>
