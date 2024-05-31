<?php
require_once '../app/models/db.php';

function deleteChildProfile() {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameter: id']);
        return;
    }

    $conn = Database::getConnection();
    $id = $conn->real_escape_string($_GET['id']);

    $conn->begin_transaction();

    try {
        $sqlChildren = "DELETE FROM children WHERE id='$id'";
        if (!$conn->query($sqlChildren)) {
            throw new Exception('Failed to delete child profile');
        }

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

deleteChildProfile();
?>
