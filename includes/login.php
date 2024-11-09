<?php
// login.php
include '../data/db_connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = htmlspecialchars($username);
            echo 'success';
        } else {
            echo 'Invalid credentials.';
        }
    } else {
        echo 'Invalid credentials.';
    }
}

?>
