<?php
require_once('data/db_connection.php');

class AdminSoapService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Core admin operations
    public function authenticateAdmin($username, $password) {
        $query = "SELECT * FROM admins WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Reference Code Methods
    public function getAllReferenceCodes() {
        $query = "SELECT * FROM reference_codes";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addReferenceCode($code) {
        if (empty($code)) {
            throw new Exception("Reference code cannot be empty");
        }

        $checkQuery = "SELECT * FROM reference_codes WHERE code = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $code);
        $checkStmt->execute();
        $existingCode = $checkStmt->get_result()->fetch_assoc();

        if ($existingCode) {
            throw new Exception("Reference code already exists");
        }

        $query = "INSERT INTO reference_codes (code, used) VALUES (?, 0)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $code);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Failed to add reference code: " . $stmt->error);
        }

        return true;
    }

    public function editReferenceCode($id, $code) {
        $query = "UPDATE reference_codes SET code = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $code, $id);
        return $stmt->execute();
    }

    public function deleteReferenceCode($id) {
        $query = "DELETE FROM reference_codes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Notebook Methods
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

    public function getNotebooksWithPagination($page, $limit) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM notebook LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $totalQuery = "SELECT COUNT(*) as total FROM notebook";
        $totalResult = $this->conn->query($totalQuery);
        $total = $totalResult->fetch_assoc()['total'];

        $html = '';
        foreach ($result as $notebook) {
            $html .= "<tr>
                        <td>{$notebook['id']}</td>
                        <td>{$notebook['manufacturer']}</td>
                        <td>{$notebook['type']}</td>
                        <td>{$notebook['display']}</td>
                        <td>{$notebook['memory']}</td>
                        <td>{$notebook['harddisk']}</td>
                        <td>{$notebook['videocontroller']}</td>
                        <td>{$notebook['price']}</td>
                        <td>{$notebook['processorid']}</td>
                        <td>{$notebook['opsystemid']}</td>
                        <td>{$notebook['pieces']}</td>
                        <td>
                            <button class='btn btn-sm btn-primary edit-notebook' data-id='{$notebook['id']}'>Edit</button>
                            <button class='btn btn-sm btn-danger delete-notebook' data-id='{$notebook['id']}'>Delete</button>
                        </td>
                      </tr>";
        }

        $pagination = '';
        for ($i = 1; $i <= ceil($total / $limit); $i++) {
            $activeClass = $i == $page ? 'active' : '';
            $pagination .= "<button class='btn $activeClass' onclick='loadNotebooks($i)'>$i</button>";
        }

        return ['html' => $html, 'pagination' => $pagination];
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

        $data['stats'] = array(
            'total_notebooks' => $this->getCount('notebook'),
            'total_processors' => $this->getCount('processor'),
            'total_os' => $this->getCount('opsystem'),
            'total_admins' => $this->getCount('admins'),
            'total_users' => $this->getCount('users'),
            'total_reference_codes' => $this->getCount('reference_codes')
        );

        $data['notebooks'] = $this->getAllNotebooks();
        $data['processors'] = $this->getAllProcessors();
        $data['operatingSystems'] = $this->getAllOperatingSystems();
        $data['admins'] = $this->getAllAdmins();
        $data['users'] = $this->getAllUsers();
        $data['referenceCodes'] = $this->getAllReferenceCodes();

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

    // Processor Methods
    public function updateProcessor($id, $data) {
        $query = "UPDATE processor SET manufacturer = ?, type = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $data['manufacturer'], $data['type'], $id);
        return $stmt->execute();
    }

    public function deleteProcessor($id) {
        $query = "DELETE FROM processor WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Operating System Methods
    public function updateOperatingSystem($id, $data) {
        $query = "UPDATE opsystem SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $data['name'], $id);
        return $stmt->execute();
    }

    public function deleteOperatingSystem($id) {
        $query = "DELETE FROM opsystem WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // User Methods
    public function updateUser($id, $data) {
        $query = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $data['username'], $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
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
