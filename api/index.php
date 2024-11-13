<?php
// api/index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$id = basename($path);

// Database connection
$pdo = new PDO("mysql:host=localhost;dbname=laptop", "root", "");

switch($method) {
    case 'GET':
        if($id) {
            // Get specific laptop
            $stmt = $pdo->prepare("SELECT * FROM notebooks WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            // Get all laptops
            $stmt = $pdo->query("SELECT * FROM notebooks");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO notebooks (manufacturer, type, price, display, memory, harddisk, videocontroller, pieces) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['manufacturer'],
            $data['type'],
            $data['price'],
            $data['display'],
            $data['memory'],
            $data['harddisk'],
            $data['videocontroller'],
            $data['pieces']
        ]);
        echo json_encode(['message' => 'Created successfully', 'id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE notebooks SET 
                manufacturer = ?, 
                type = ?,
                price = ?,
                display = ?,
                memory = ?,
                harddisk = ?,
                videocontroller = ?,
                pieces = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['manufacturer'],
            $data['type'],
            $data['price'],
            $data['display'],
            $data['memory'],
            $data['harddisk'],
            $data['videocontroller'],
            $data['pieces'],
            $id
        ]);
        echo json_encode(['message' => 'Updated successfully']);
        break;

    case 'DELETE':
        $stmt = $pdo->prepare("DELETE FROM notebooks WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Deleted successfully']);
        break;
}
?>