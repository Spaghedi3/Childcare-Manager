<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function updateRelationship()
{
    if (!isset($_COOKIE['childId'])) {
        sendResponse(['error' => 'Child ID is required'], 400);
        return;
    }

    $childId = $_COOKIE['childId'];
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
        return;
    }

    // Parse incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        sendResponse(['error' => 'Invalid input'], 400);
        return;
    }

    // Validate and sanitize input data
    $relationshipTypes = ['parent', 'grandparent', 'sibling', 'friend']; // List of valid relationship types

    if (!isset($data['relationship_type']) || !in_array($data['relationship_type'], $relationshipTypes)) {
        sendResponse(['error' => 'Invalid or missing relationship type'], 400);
        return;
    }

    $relationshipType = $data['relationship_type'];
    $name = isset($data['name']) ? trim($data['name']) : '';
    $contactInfo = isset($data['contact']) ? trim($data['contact']) : '';

    // Prepare SQL query
    $sql = "UPDATE relationships SET name = ?, contact_info = ? WHERE child_id = ? AND relationship_type = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        sendResponse(['error' => 'Failed to prepare statement: ' . $conn->error], 500);
        $conn->close();
        return;
    }

    // Bind parameters and execute query
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
