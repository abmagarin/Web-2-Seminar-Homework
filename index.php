<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laptop Menu</title>
    <nav class="navbar">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

</head>
<body>

<?php

include 'includes/db_connection.php'; // Include the connection file

// Insert data if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manufacturer = $_POST['manufacturer'];
    $type = $_POST['type'];
    $price = $_POST['price'];

    // Prepare and execute the query
    $sql = "INSERT INTO notebook (manufacturer, type, price) VALUES ('$manufacturer', '$type', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "New notebook added";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<form method="POST" action="">
    Manufacturer: <input type="text" name="manufacturer">
    Type: <input type="text" name="type">
    Price: <input type="number" name="price">
    <input type="submit" value="Add">
</form>


<h2>Notebook Items</h2>
<?php
// Retrieve and display notebook items
$sql = "SELECT id, manufacturer, type, price FROM notebook"; // Selecting relevant columns
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Manufacturer: " . $row["manufacturer"] . " - Type: " . $row["type"] . " - Price: " . $row["price"] . "<br>";
    }
} else {
    echo "No items in the notebook table.";
}
?>

</body>
</html>
