<?php
// Display errors in case something goes wrong
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laravel";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Verify the connection
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Create the SQL query to delete the data
$sql = "DELETE FROM notebook";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "All the content from notebook has been deleted<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$sql = "DELETE FROM opsystem";
// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "All the content from opsystem has been deleted<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$sql = "DELETE FROM processor";
// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "All the content from processor has been deleted<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
