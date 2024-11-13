<?php
session_start();
require_once '../data/db_connection.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $notebookId = $_POST['notebook_id'];
    $quantity = $_POST['quantity'] ?? 1;

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=laptop', 'root', '',
                       array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        // Check if the notebook exists and is in stock
        $stmt = $dbh->prepare("SELECT pieces FROM notebook WHERE id = ?");
        $stmt->execute([$notebookId]);
        $notebook = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$notebook || $notebook['pieces'] < $quantity) {
            echo "Not enough stock or invalid product.";
            exit();
        }

        // Check if the item is already in the basket
        $stmt = $dbh->prepare("SELECT * FROM baskets WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $notebookId]);
        $existingBasketItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingBasketItem) {
            // Update quantity if item exists
            $stmt = $dbh->prepare("UPDATE baskets SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $userId, $notebookId]);
        } else {
            // Insert new item to basket
            $stmt = $dbh->prepare("INSERT INTO baskets (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $notebookId, $quantity]);
        }

        // Reduce stock
        $stmt = $dbh->prepare("UPDATE notebook SET pieces = pieces - ? WHERE id = ?");
        $stmt->execute([$quantity, $notebookId]);

        echo "Added to basket successfully!";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>