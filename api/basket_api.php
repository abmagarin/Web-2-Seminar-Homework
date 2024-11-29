<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../data/db_connection.php';
include '../data/db_connection.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $userId = $_SESSION['user_id'];

    switch($method) {
        case 'GET':
            // Get user's basket items with notebook details
            $stmt = $conn->prepare("
                SELECT 
                    b.basket_id,
                    b.user_id,
                    b.product_id as notebook_id,
                    b.quantity,
                    n.manufacturer,
                    n.type,
                    n.price,
                    n.pieces as stock_available
                FROM baskets b 
                JOIN notebook n ON b.product_id = n.id 
                WHERE b.user_id = ?
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            break;

        case 'DELETE':
            // Get the quantity being removed
            $notebookId = $_GET['notebook_id'];
            
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Get current quantity in basket
                $stmt = $conn->prepare("SELECT quantity FROM baskets WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $userId, $notebookId);
                $stmt->execute();
                $basketResult = $stmt->get_result();
                $basketItem = $basketResult->fetch_assoc();
                $quantityToReturn = $basketItem['quantity'];

                // Update notebook stock
                $stmt = $conn->prepare("UPDATE notebook SET pieces = pieces + ? WHERE id = ?");
                $stmt->bind_param("ii", $quantityToReturn, $notebookId);
                $stmt->execute();

                // Remove from basket
                $stmt = $conn->prepare("DELETE FROM baskets WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $userId, $notebookId);
                $stmt->execute();

                $conn->commit();
                echo json_encode(['success' => 'Item removed and stock updated']);
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $notebookId = $data['notebook_id'];
            $newQuantity = $data['quantity'];
            
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Get current quantity
                $stmt = $conn->prepare("SELECT quantity FROM baskets WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $userId, $notebookId);
                $stmt->execute();
                $result = $stmt->get_result();
                $currentItem = $result->fetch_assoc();
                $quantityDiff = $currentItem['quantity'] - $newQuantity;

                // Check if new quantity is valid
                $stmt = $conn->prepare("SELECT pieces FROM notebook WHERE id = ?");
                $stmt->bind_param("i", $notebookId);
                $stmt->execute();
                $stockResult = $stmt->get_result();
                $stockItem = $stockResult->fetch_assoc();
                
                if ($stockItem['pieces'] + $quantityDiff < 0) {
                    throw new Exception('Not enough stock available');
                }

                // Update notebook stock
                $stmt = $conn->prepare("UPDATE notebook SET pieces = pieces + ? WHERE id = ?");
                $stmt->bind_param("ii", $quantityDiff, $notebookId);
                $stmt->execute();

                // Update basket quantity
                $stmt = $conn->prepare("UPDATE baskets SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("iii", $newQuantity, $userId, $notebookId);
                $stmt->execute();

                $conn->commit();
                echo json_encode(['success' => 'Quantity updated']);
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>