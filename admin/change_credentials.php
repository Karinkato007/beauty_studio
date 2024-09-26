<!-- admin/change_credentials.php -->
<?php
session_start();
include('../includes/db.php');

// Ensure only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../user/login.php');
    exit;
}

// Handle form submission for updating credentials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // If password is provided, hash it
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id={$_SESSION['user_id']}";
    } else {
        $query = "UPDATE users SET username='$username', email='$email' WHERE id={$_SESSION['user_id']}";
    }

    if (mysqli_query($conn, $query)) {
        echo "Admin credentials updated successfully!";
        $_SESSION['username'] = $username; // Update session username
    } else {
        echo "Error updating credentials: " . mysqli_error($conn);
    }
}

// Fetch current admin details
$query = "SELECT * FROM users WHERE id={$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Change Admin Credentials</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-2xl mb-6">Change Admin Credentials</h2>
        <form method="POST" action="change_credentials.php" class="space-y-4">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" class="block w-full border px-3 py-2 rounded-lg" value="<?php echo $admin['username']; ?>">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" class="block w-full border px-3 py-2 rounded-lg" value="<?php echo $admin['email']; ?>">
            </div>
            <div>
                <label for="password">New Password (leave blank if not changing):</label>
                <input type="password" name="password" class="block w-full border px-3 py-2 rounded-lg">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Credentials</button>
            </div>
        </form>
    </div>
</body>
</html>
