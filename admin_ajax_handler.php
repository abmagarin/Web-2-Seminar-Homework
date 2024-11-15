<?php
session_start();
require_once('data/db_connection.php');
require_once('admin_soap_service.php');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
$adminService = new AdminSoapService($conn);

try {
    switch ($data['action']) {
        case 'add_reference_code':
            $result = $adminService->addReferenceCode($data['code']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Reference code added successfully' : 'Failed to add reference code']);
            break;

        case 'edit_reference_code':
            $result = $adminService->editReferenceCode($data['id'], $data['code']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Reference code updated successfully' : 'Failed to update reference code']);
            break;

        case 'delete_reference_code':
            $result = $adminService->deleteReferenceCode($data['id']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Reference code deleted successfully' : 'Failed to delete reference code']);
            break;

        // Add Notebook
        case 'add_notebook':
            $result = $adminService->addNotebook($data['data']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Notebook added successfully' : 'Failed to add notebook']);
            break;

        // Load Notebooks with pagination
        case 'load_notebooks':
            $page = $data['page'];
            $limit = 10; // Notebooks per page
            $notebooksData = $adminService->getNotebooksWithPagination($page, $limit);
            echo json_encode([
                'html' => $notebooksData['html'],
                'pagination' => $notebooksData['pagination']
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
