<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('data/db_connection.php');

// Check admin authentication
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// SOAP Client Configuration
try {
    $client = new SoapClient('http://localhost/Web-2-Seminar-Homework/admin_service.wsdl', [
        'trace' => 1,
        'exceptions' => true
    ]);
} catch (SoapFault $e) {
    echo json_encode(['success' => false, 'message' => 'SOAP Client Error: ' . $e->getMessage()]);
    exit();
}

header('Content-Type: application/json');

$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
}

try {
    switch ($data['action']) {
        // Reference Code Actions
        case 'add_reference_code':
            if (empty($data['code'])) {
                throw new Exception("Reference code cannot be empty");
            }
            $response = $client->__soapCall('addReferenceCode', [$data['code']]);
            echo json_encode([
                'success' => $response, 
                'message' => $response ? 'Reference code added successfully' : 'Failed to add reference code'
            ]);
            break;

            case 'edit_reference_code':
                if (empty($data['oldCode']) || empty($data['newCode'])) {
                    throw new Exception("Old and new codes are required");
                }
                // Convert to string to ensure consistent handling
                $oldCode = (string)$data['oldCode'];
                $newCode = (string)$data['newCode'];
                $response = $client->__soapCall('editReferenceCode', [$oldCode, $newCode]);
                echo json_encode([
                    'success' => $response, 
                    'message' => $response ? 'Reference code updated successfully' : 'Failed to update reference code'
                ]);
                break;
            
            case 'delete_reference_code':
                if (empty($data['code'])) {
                    throw new Exception("Code is required");
                }
                // Convert to string to ensure consistent handling
                $code = (string)$data['code'];
                $response = $client->__soapCall('deleteReferenceCode', [$code]);
                echo json_encode([
                    'success' => $response, 
                    'message' => $response ? 'Reference code deleted successfully' : 'Failed to delete reference code'
                ]);
                break;
                
        // Notebook Actions
        case 'add_notebook':
            $requiredFields = [
                'manufacturer', 'type', 'display', 'memory', 
                'harddisk', 'videocontroller', 'price', 
                'processorid', 'opsystemid', 'pieces'
            ];

            // Validate all required fields
            foreach ($requiredFields as $field) {
                if (!isset($data['data'][$field]) || $data['data'][$field] === '') {
                    throw new Exception("Missing required field: $field");
                }
            }

            $response = $client->__soapCall('addNotebook', [$data['data']]);
            echo json_encode([
                'success' => $response, 
                'message' => $response ? 'Notebook added successfully' : 'Failed to add notebook'
            ]);
            break;

        case 'edit_notebook':
            if (empty($data['id']) || empty($data['data'])) {
                throw new Exception("Notebook ID and data are required");
            }
            $response = $client->__soapCall('editNotebook', [$data['id'], $data['data']]);
            echo json_encode([
                'success' => $response, 
                'message' => $response ? 'Notebook updated successfully' : 'Failed to update notebook'
            ]);
            break;

        case 'delete_notebook':
            if (empty($data['id'])) {
                throw new Exception("Notebook ID is required");
            }
            $response = $client->__soapCall('deleteNotebook', [$data['id']]);
            echo json_encode([
                'success' => $response, 
                'message' => $response ? 'Notebook deleted successfully' : 'Failed to delete notebook'
            ]);
            break;

        default:
            throw new Exception("Invalid action");
    }
} catch (SoapFault $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'SOAP Error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}