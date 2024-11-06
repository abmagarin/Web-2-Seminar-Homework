<?php
require_once 'db_connection.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$response = [];

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Registration successful.";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
