<?php
// Include database connection
include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Take the variables from the HTTP request and store them in PHP variables
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the username exists, return an error message
        echo "Username already taken.";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            echo "success";  // Return success if the user is successfully registered
        } else {
            echo "Error occurred. Please try again.";
        }
    }
}
?>
