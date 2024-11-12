<!-- edit_stock.php -->
<?php
session_start();
include('../includes/db.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch the stock item to be edited
if (isset($_GET['id'])) {
    $stock_id = $_GET['id'];
    $query = "SELECT * FROM stock WHERE id = $stock_id";
    $result = mysqli_query($conn, $query);
    $stock_item = mysqli_fetch_assoc($result);
}

// Handle form submission to update stock
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // Update the stock item in the database
    $update_query = "UPDATE stock SET product_name='$product_name', quantity='$quantity', last_updated=NOW() WHERE id=$stock_id";
    
    if (mysqli_query($conn, $update_query)) {
        header('Location: stock_list.php'); // Redirect back to stock list after update
        exit;
    } else {
        echo "Error updating stock: " . mysqli_error($conn);
    }
}

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
    <title>Edit Stock</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-2xl mb-6">Edit Stock Item</h2>
        <form method="POST" action="edit_stock.php?id=<?php echo $stock_id; ?>" class="space-y-4">
            <div>
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" class="block w-full border px-3 py-2 rounded-lg" value="<?php echo $stock_item['product_name']; ?>" required>
            </div>
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" class="block w-full border px-3 py-2 rounded-lg" value="<?php echo $stock_item['quantity']; ?>" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Stock</button>
            </div>
        </form>
    </div>
</body>
</html>

