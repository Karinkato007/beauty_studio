<!-- reset_password.php -->
<?php
session_start();
include('includes/db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $query = "SELECT * FROM password_resets WHERE token='$token' AND expires >= " . date("U");
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $email = mysqli_fetch_assoc($result)['email'];

            // Update the user's password
            $query = "UPDATE users SET password='$new_password' WHERE email='$email'";
            mysqli_query($conn, $query);

            // Delete the token
            mysqli_query($conn, "DELETE FROM password_resets WHERE token='$token'");

            $_SESSION['success'] = "Password has been reset successfully! You can log in now.";
            header('Location: login.php');
            exit;
        }
    } else {
        $error = "This token is invalid or has expired.";
    }
} else {
    header('Location: forgot_password.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Reset Password</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-sm mx-auto mt-10">
        <h2 class="text-2xl mb-6">Reset Password</h2>
        <?php if (isset($error)) echo "<p class='text-red-500'>$error</p>"; ?>
        <?php if (isset($_SESSION['success'])) echo "<p class='text-green-500'>{$_SESSION['success']}</p>"; ?>
        <form method="POST" action="reset_password.php?token=<?php echo $token; ?>" class="space-y-4">
            <div>
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" class="block w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>
