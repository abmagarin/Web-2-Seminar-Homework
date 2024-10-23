<?php
// Database connection
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
function buildMenu($parent_id = NULL, $menu_items = [], $conn) {
    $menu = "";
    $sql = "SELECT * FROM menu_items WHERE parent_id " . ($parent_id === NULL ? "IS NULL" : "= $parent_id");
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $menu .= "<ul>";
        while ($row = $result->fetch_assoc()) {
            $menu .= "<li>" . $row['name'];
            $menu .= buildMenu($row['id'], $menu_items, $conn);
            $menu .= "</li>";
        }
        $menu .= "</ul>";
    }
    return $menu;
}

// Display the menu
echo buildMenu(NULL, [], $conn);

// Close the connection
$conn->close();
?>
