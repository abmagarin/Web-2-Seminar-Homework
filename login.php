<?php
// login.php
include 'includes/db_connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar la consulta
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Si el login es exitoso
            echo 'success';  // Esto será interpretado por AJAX
        } else {
            // Si la contraseña es incorrecta
            echo 'Incorrect password.';
        }
    } else {
        // Si no se encuentra el usuario
        echo 'No user found.';
    }
}
?>
