<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Laptop";

//sudo /opt/lampp/lampp start

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

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reference_code VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS baskets (
    basket_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT(6) UNSIGNED NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES notebook(id)
);

CREATE TABLE reference_codes (
    code VARCHAR(10) NOT NULL PRIMARY KEY,
    used TINYINT(1) NOT NULL DEFAULT 0
);


INSERT INTO reference_codes (code, used) VALUES
('code1', 0),
('code2', 0),
('code3', 0),
('code4', 0),
('code5', 0),
('code6', 0),
('code7', 0),
('code8', 0),
('code9', 0),
('code10', 0);

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
