<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Level Menu</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Barra de navegación al principio de la página -->
    <nav class="navbar">
        <ul>
            <!-- Nivel 1: Menú Principal -->
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>

            <!-- Nivel 1: Menú Principal con Submenú -->
            <li>
                <a href="products.php">Products</a>
                <!-- Nivel 2: Submenú de Productos -->
                <ul class="submenu">
                    <li><a href="laptops.php">Laptops</a></li>
                    <li><a href="tablets.php">Tablets</a></li>
                    <li><a href="accessories.php">Accessories</a></li>
                </ul>
            </li>

            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- Resto del contenido de la página -->
    <div class="content">
        <h1>Welcome to Our Website</h1>
        <p>Here you can introduce the main topic of your website.</p>
    </div>
</body>
</html>
