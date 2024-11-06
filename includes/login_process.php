<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db_connection.php';

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedPassword = sha1($password);

        $sql = "SELECT id, username FROM users WHERE email = ? AND password = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $email, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $errormessage = "Invalid email or password.";
            }
            $stmt->close();
        } else {
            $errormessage = "Error preparing statement: " . $conn->error;
        }
    } else {
        $errormessage = "Please provide both email and password.";
    }
}
?>
