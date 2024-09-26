<!-- stock_list.php -->
<?php
session_start();
include('../includes/db.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch all stock items from the database
$query = "SELECT * FROM stock";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Stock List</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-2xl mb-6">Stock List</h2>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200">Product Name</th>
                    <th class="py-2 px-4 bg-gray-200">Quantity</th>
                    <th class="py-2 px-4 bg-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $row['product_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $row['quantity']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="edit_stock.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded-lg">Edit</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
