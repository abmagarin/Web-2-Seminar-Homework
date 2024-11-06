<?php
require_once 'db_connection.php';
session_start();

$adminUsername = $_POST['admin_username'];
$adminPassword = $_POST['admin_password'];
$adminCode = $_POST['admin_code'];

$response = [];

$sql = "SELECT * FROM admins WHERE username = ? AND access_code = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $adminUsername, $adminCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($adminPassword, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $response['success'] = true;
            $response['message'] = "Admin login successful.";
        } else {
            $response['success'] = false;
            $response['message'] = "Invalid password.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "No admin found with this username and access code.";
    }
    $stmt->close();
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
