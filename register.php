
<?php
session_start();
require 'includes/db_connection.php'; // Assuming you have a database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        echo "Username already exists. Please choose a different username.";
    } else {
        // Username does not exist, proceed with registration
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
