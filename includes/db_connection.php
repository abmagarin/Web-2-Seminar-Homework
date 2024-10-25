<?php
$servername = "localhost";
$username = "root";
$password = ""; //for now we dont have password

//$dbname = "Laptop";
$dbname = "Laptop "; // Name of your database is laptop so we need to be sure it not mix it with other dbs

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
