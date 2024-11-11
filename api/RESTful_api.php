// This is Task 6.Restful server menu: Create a Restful web service for your database to one of its boards. Implement GET, POST, PUT, DELETE functions.Test your web service with cURL and Postman. Describe the testing steps in the documentation.
//Testing with Postman with all methods went successfully.
<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=laptop', 'root', '',
                   array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $dbh->exec('SET NAMES utf8 COLLATE utf8_general_ci');
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Database connection failed: ' . $e->getMessage()));
}
// Define the possible actions for the RESTful service
$action = isset($_GET['action']) ? $_GET['action'] : '';
// Handle actions based on the endpoint
switch ($action) {
    case 'getNotebooks':
        getNotebooks();
        break;
    case 'getOpsystems':
        getOpsystems();
        break;
    case 'getProcessors':
        getProcessors();
        break;
    case 'getUsers':
        getUsers();
        break;
    case 'getAdmins':
        getAdmins();
        break;
    case 'getReferenceCodes':
        getReferenceCodes();
        break;
    case 'createNotebook':
        createNotebook();
        break;
    case 'createOpsystem':
        createOpsystem();
        break;
    case 'createProcessor':
        createProcessor();
        break;
    case 'createUser':
        createUser();
        break;
    case 'createAdmin':
        createAdmin();
        break;
    case 'createReferenceCode':
        createReferenceCode();
        break;
    case 'updateNotebook':
        updateNotebook();
        break;
    case 'updateOpsystem':
        updateOpsystem();
        break;
    case 'updateProcessor':
        updateProcessor();
        break;
    case 'updateUser':
        updateUser();
        break;
    case 'updateAdmin':
        updateAdmin();
        break;
    case 'updateReferenceCode':
        updateReferenceCode();
        break;
    case 'deleteNotebook':
        deleteNotebook();
        break;
    case 'deleteOpsystem':
        deleteOpsystem();
        break;
    case 'deleteProcessor':
        deleteProcessor();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'deleteAdmin':
        deleteAdmin();
        break;
    case 'deleteReferenceCode':
        deleteReferenceCode();
        break;
    default:
        echo json_encode(array('message' => 'Invalid action.'));
}

function getNotebooks() {
    global $dbh;
    try {
        $sql = "SELECT * FROM notebook";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $notebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($notebooks);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define the other GET methods here (getOpsystems, getProcessors, etc.)

function createNotebook() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO notebook (notebook_name, brand, processor_id, opsystem_id) VALUES (:notebook_name, :brand, :processor_id, :opsystem_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':notebook_name' => $data['notebook_name'],
            ':brand' => $data['brand'],
            ':processor_id' => $data['processor_id'],
            ':opsystem_id' => $data['opsystem_id']
        ));
        echo json_encode(array('message' => 'Notebook created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define the other CREATE methods here (createOpsystem, createProcessor, etc.)

function updateNotebook() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE notebook SET notebook_name = :notebook_name, brand = :brand, processor_id = :processor_id, opsystem_id = :opsystem_id WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':notebook_name' => $data['notebook_name'],
            ':brand' => $data['brand'],
            ':processor_id' => $data['processor_id'],
            ':opsystem_id' => $data['opsystem_id']
        ));
        echo json_encode(array('message' => 'Notebook updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define the other UPDATE methods here (updateOpsystem, updateProcessor, etc.)

function deleteNotebook() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM notebook WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'Notebook deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// GET functions for other tables
function getOpsystems() {
    global $dbh;
    try {
        $sql = "SELECT * FROM opsystem";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $opsystems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($opsystems);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function getProcessors() {
    global $dbh;
    try {
        $sql = "SELECT * FROM processor";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $processors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($processors);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function getUsers() {
    global $dbh;
    try {
        $sql = "SELECT * FROM users";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function getAdmins() {
    global $dbh;
    try {
        $sql = "SELECT * FROM admins";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($admins);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function getReferenceCodes() {
    global $dbh;
    try {
        $sql = "SELECT * FROM reference_codes";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($codes);
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// CREATE functions
function createOpsystem() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO opsystem (name, version) VALUES (:name, :version)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':name' => $data['name'],
            ':version' => $data['version']
        ));
        echo json_encode(array('message' => 'Operating System created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function createProcessor() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO processor (name, speed) VALUES (:name, :speed)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':name' => $data['name'],
            ':speed' => $data['speed']
        ));
        echo json_encode(array('message' => 'Processor created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function createUser() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':username' => $data['username'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':email' => $data['email']
        ));
        echo json_encode(array('message' => 'User created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function createAdmin() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO admins (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':username' => $data['username'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':email' => $data['email']
        ));
        echo json_encode(array('message' => 'Admin created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function createReferenceCode() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "INSERT INTO reference_codes (code, discount) VALUES (:code, :discount)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':code' => $data['code'],
            ':discount' => $data['discount']
        ));
        echo json_encode(array('message' => 'Reference code created successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// UPDATE functions
function updateOpsystem() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE opsystem SET name = :name, version = :version WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':version' => $data['version']
        ));
        echo json_encode(array('message' => 'Operating System updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function updateProcessor() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE processor SET name = :name, speed = :speed WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':speed' => $data['speed']
        ));
        echo json_encode(array('message' => 'Processor updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function updateUser() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':username' => $data['username'],
            ':email' => $data['email']
        ));
        echo json_encode(array('message' => 'User updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function updateAdmin() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE admins SET username = :username, email = :email WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':username' => $data['username'],
            ':email' => $data['email']
        ));
        echo json_encode(array('message' => 'Admin updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function updateReferenceCode() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "UPDATE reference_codes SET code = :code, discount = :discount WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':id' => $data['id'],
            ':code' => $data['code'],
            ':discount' => $data['discount']
        ));
        echo json_encode(array('message' => 'Reference code updated successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// DELETE functions
function deleteOpsystem() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM opsystem WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'Operating System deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function deleteProcessor() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM processor WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'Processor deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function deleteUser() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'User deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function deleteAdmin() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'Admin deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

function deleteReferenceCode() {
    global $dbh;
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $sql = "DELETE FROM reference_codes WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $data['id']));
        echo json_encode(array('message' => 'Reference code deleted successfully.'));
    } catch (PDOException $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}



// Define the other DELETE methods here (deleteOpsystem, deleteProcessor, etc.)
?>
