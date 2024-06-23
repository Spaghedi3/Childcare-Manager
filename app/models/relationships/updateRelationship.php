<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function updateRelationship()
{
    if(isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
    } else {
        sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
    }
    
    if (isset($_SESSION['childId'])) {
        $childId = $_SESSION['childId'];
    } else {
        sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);
    }
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        sendResponse(['error' => 'Invalid input'], 400);
        return;
    }

    $relationshipTypes = ['parent', 'grandparent', 'sibling', 'friend']; 

    if (!isset($data['relationship_type']) || !in_array($data['relationship_type'], $relationshipTypes)) {
        sendResponse(['error' => 'Invalid or missing relationship type'], 400);
        return;
    }

    $relationshipType = $data['relationship_type'];
    $name = isset($data['name']) ? trim($data['name']) : '';
    $contactInfo = isset($data['contact']) ? trim($data['contact']) : '';

    $sql = "UPDATE relationships SET name = ?, contact_info = ? WHERE child_id = ? AND relationship_type = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        sendResponse(['error' => 'Failed to prepare statement: ' . $conn->error], 500);
        $conn->close();
        return;
    }

    $stmt->bind_param('ssis', $name, $contactInfo, $childId, $relationshipType);
    if (!$stmt->execute()) {
        sendResponse(['error' => 'Error updating relationships information: ' . $stmt->error], 500);
        $stmt->close();
        $conn->close();
        return;
    }

    $stmt->close();
    $conn->close();

    sendResponse(['success' => true]);
}

updateRelationship();
?>
