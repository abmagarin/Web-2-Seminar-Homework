<?php
// This lines print the fails on screen when it fails
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection maybe we dont need it as includes has it
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Laptop";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verify the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to build the menu
function buildMenu($conn, $parent_id = NULL, $menu_items = []) {
    $menu = "";
    $sql = "SELECT * FROM menu_items WHERE parent_id " . ($parent_id === NULL ? "IS NULL" : "= $parent_id");
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $menu .= "<ul>";
        while ($row = $result->fetch_assoc()) {
            $menu .= "<li>" . $row['name'];
            $menu .= buildMenu($conn, $row['id'], $menu_items);
            $menu .= "</li>";
        }
        $menu .= "</ul>";
    }
    return $menu;
}


// Display the menu
echo buildMenu($conn, NULL, []);

// Close the connection
$conn->close();
?>
