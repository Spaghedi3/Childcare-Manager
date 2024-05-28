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

    $sql = "DELETE FROM children WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete child profile']);
    }
}

deleteChildProfile();
?>
