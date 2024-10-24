<?php
$servername = "localhost";
$username = "root";
$password = ""; // Cambia si tienes una contraseña

//$dbname = "Laptop";
$dbname = "laravel"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Revisar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
