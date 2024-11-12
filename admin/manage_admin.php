<?php
include('../includes/db.php');
session_start();

// Ensure the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all admin users from the database
$query = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
    <link href="../css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<header class="bg-gray-800 text-white p-5">
    <h1 class="text-center text-3xl">Manage Admins</h1>
</header>

<main class="container mx-auto py-10">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl mb-6">Admins</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Username</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="border px-4 py-2">
                                <a href="edit_admin.php?id=<?php echo $row['id']; ?>" class="text-blue-500">Edit</a>
                                <a href="delete_admin.php?id=<?php echo $row['id']; ?>" class="text-red-500 ml-2">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No admin users found.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
