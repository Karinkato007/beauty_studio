<!-- admin/manage_stock.php -->
<?php
include('../includes/db.php');

// Fetch stock data
$query = "SELECT * FROM stock ORDER BY product_name ASC";
$result = mysqli_query($conn, $query);

// Prevent the browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/tailwind.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Manage Stock</title>
</head>
<body class="bg-gray-100">
<header class="bg-gray-800 text-white p-5 flex items-center justify-between">
        <a href="./admin.php" ><span class="font-bold text-2xl">BACK</span></a>
        <h1 class="text-center text-3xl flex-1">Manage Stocks</h1>
    </header>

    <main class="py-10">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl mb-6">Stock List</h2>
            <table class="table-auto w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Product Name</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Last Updated</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $row['id']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['product_name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['quantity']; ?></td>
                        <td class="border px-4 py-2"><?php echo $row['last_updated']; ?></td>
                        <td class="border px-4 py-2">
                            <a href="edit_stock.php?id=<?php echo $row['id']; ?>" class="text-blue-500">Edit</a> | 
                            <a href="delete_stock.php?id=<?php echo $row['id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this stock?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
