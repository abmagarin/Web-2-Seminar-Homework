<?php
include '../data/db_connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted admin credentials
    $adminUsername = $_POST['admin_username'];
    $adminPassword = $_POST['admin_password'];

    // Check the database for the admin's credentials
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $adminUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify the password
        if (password_verify($adminPassword, $admin['password'])) {
            // Set session variables for the logged-in admin
            $_SESSION['username'] = $adminUsername;
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['is_admin'] = true;  // Mark this user as admin
            
            // Respond with success
            echo 'success';
        } else {
            // Invalid password
            echo 'Invalid password.';
        }
    } else {
        // Admin not found
        echo 'Admin not found.';
    }
}
?>
