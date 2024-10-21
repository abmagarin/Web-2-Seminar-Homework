<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aplicación de Alquiler de Bicicletas</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Si decides agregar estilos -->
</head>
<body>

<?php
include 'includes/db_connection.php'; // Incluir el archivo de conexión

// Insertar datos si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manufacturer = $_POST['manufacturer'];
    $type = $_POST['type'];
    $price = $_POST['price'];

    // Preparar y ejecutar la consulta
    $sql = "INSERT INTO notebook (manufacturer, type, price) VALUES ('$manufacturer', '$type', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo notebook añadido";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<form method="POST" action="">
    Fabricante: <input type="text" name="manufacturer">
    Tipo: <input type="text" name="type">
    Precio: <input type="number" name="price">
    <input type="submit" value="Agregar">
</form>


<h2>Elementos de Notebook</h2>
<?php
// Recuperar y mostrar los elementos del notebook
$sql = "SELECT id, manufacturer, type, price FROM notebook"; // Seleccionando columnas relevantes
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Fabricante: " . $row["manufacturer"] . " - Tipo: " . $row["type"] . " - Precio: " . $row["price"] . "<br>";
    }
} else {
    echo "No hay elementos en la tabla de notebooks.";
}
?>

</body>
</html>
