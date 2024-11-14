<?php
// admin_soap_service.php
require_once('data/db_connection.php');

class AdminSoapService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Core admin operations
    public function authenticateAdmin($username, $password) {
        // Add authentication logic
        $query = "SELECT * FROM admins WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Product Management
    public function addNotebook($data) {
        $query = "INSERT INTO notebook (manufacturer, type, display, memory, harddisk, videocontroller, price, processorid, opsystemid, pieces) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssdiis", 
            $data['manufacturer'], 
            $data['type'], 
            $data['display'],
            $data['memory'],
            $data['harddisk'],
            $data['videocontroller'],
            $data['price'],
            $data['processorid'],
            $data['opsystemid'],
            $data['pieces']
        );
        return $stmt->execute();
    }

    public function updateNotebook($id, $data) {
        $query = "UPDATE notebook SET 
                 manufacturer = ?, 
                 type = ?, 
                 display = ?,
                 memory = ?,
                 harddisk = ?,
                 videocontroller = ?,
                 price = ?,
                 processorid = ?,
                 opsystemid = ?,
                 pieces = ?
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssdiisi", 
            $data['manufacturer'], 
            $data['type'], 
            $data['display'],
            $data['memory'],
            $data['harddisk'],
            $data['videocontroller'],
            $data['price'],
            $data['processorid'],
            $data['opsystemid'],
            $data['pieces'],
            $id
        );
        return $stmt->execute();
    }

    public function deleteNotebook($id) {
        $query = "DELETE FROM notebook WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Data Retrieval Methods
    public function getAllDashboardData() {
        $data = array();
        
        // Get all counts
        $data['stats'] = array(
            'total_notebooks' => $this->getCount('notebook'),
            'total_processors' => $this->getCount('processor'),
            'total_os' => $this->getCount('opsystem'),
            'total_admins' => $this->getCount('admins'),
            'total_users' => $this->getCount('users')
        );

        // Get all table data
        $data['notebooks'] = $this->getAllNotebooks();
        $data['processors'] = $this->getAllProcessors();
        $data['operatingSystems'] = $this->getAllOperatingSystems();
        $data['admins'] = $this->getAllAdmins();
        $data['users'] = $this->getAllUsers();

        return $data;
    }

    private function getAllNotebooks() {
        $query = "SELECT * FROM notebook";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getAllProcessors() {
        $query = "SELECT * FROM processor";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getAllOperatingSystems() {
        $query = "SELECT * FROM opsystem";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getAllAdmins() {
        $query = "SELECT * FROM admins";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getAllUsers() {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getCount($table) {
        $query = "SELECT COUNT(*) as count FROM " . $table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Order Management
    public function processOrder($userId, $notebookId, $quantity) {
        // Add order processing logic
        // Start transaction
        $this->conn->begin_transaction();
        try {
            // Check stock
            $stock = $this->checkStock($notebookId);
            if ($stock < $quantity) {
                throw new Exception("Insufficient stock");
            }

            // Create order
            $orderQuery = "INSERT INTO orders (user_id, notebook_id, quantity, order_date) VALUES (?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($orderQuery);
            $stmt->bind_param("iii", $userId, $notebookId, $quantity);
            $stmt->execute();

            // Update stock
            $updateQuery = "UPDATE notebook SET pieces = pieces - ? WHERE id = ?";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->bind_param("ii", $quantity, $notebookId);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    private function checkStock($notebookId) {
        $query = "SELECT pieces FROM notebook WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $notebookId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['pieces'];
    }
}

// Initialize SOAP Server
if (isset($_GET['wsdl'])) {
    // Serve WSDL
    header('Content-Type: application/xml');
    readfile('admin_service.wsdl');
} else {
    try {
        ini_set("soap.wsdl_cache_enabled", "0");
        $server = new SoapServer('admin_service.wsdl');
        $server->setClass('AdminSoapService', $conn);
        $server->handle();
    } catch (SoapFault $e) {
        error_log("SOAP Error: " . $e->getMessage());
        die("SOAP Error: " . $e->getMessage());
    }
}
?>