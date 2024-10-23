<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Laptop";

// Create the connection
$conn = new mysqli($servername, $username, $password);

// Check the connection
if ($conn->connect_error) {
    die("Failed connection: " . $conn->connect_error);
}

// Create the database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// SQL to create the tables
$sql = "
CREATE TABLE IF NOT EXISTS notebook (
    id INT(6) UNSIGNED PRIMARY KEY,
    manufacturer VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    display FLOAT NOT NULL,
    memory INT NOT NULL,
    harddisk INT NOT NULL,
    videocontroller VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    processorid INT NOT NULL,
    opsystemid INT NOT NULL,
    pieces INT NOT NULL
);

CREATE TABLE IF NOT EXISTS opsystem (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS processor (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manufacturer VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL
);

//JS added for table
//demo
//Creating the Database Table for Menu Items 

CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
// Registration, Login, and Role Separation
//Step: Create the users table in your database.

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('visitor', 'registered_visitor', 'admin') NOT NULL DEFAULT 'visitor'
);


//

";

// Execute the query and check for errors
if ($conn->multi_query($sql) === TRUE) {
    echo "Tables created successfully.";
} else {
    echo "Error creating tables: " . $conn->error;
}

// Close the connection
$conn->close();
?>
