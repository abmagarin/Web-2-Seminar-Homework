<?php
include 'includes/db_connection.php'; 
// Ensure this file works

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "New user registered";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    Role: 
    <select name="role">
        <option value="visitor">Visitor</option>
        <option value="registered_visitor">Registered Visitor</option>
        <option value="admin">Admin</option>
    </select>
    <input type="submit" value="Register">
</form>
