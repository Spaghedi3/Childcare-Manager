<?php

$sql = "SELECT id, description, type FROM Media WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        $fileName = $result['id'] . '.' . $result['description'];
        $filePath = '../media/' . $result['type'] . 's/' . $fileName;

        // Delete the record from the database
        $sql = "DELETE FROM Media WHERE id = ? AND user_id = ? AND child_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iii", $id, $userId, $childId);

        if ($stmt->execute()) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            sendResponse(['status' => 'success', 'message' => 'File deleted successfully']);
        } else {
            sendResponse(['status' => 'error', 'message' => 'Failed to delete record from database'], 500);
        }
    } else {
        sendResponse(['status' => 'error', 'message' => 'Record not found'], 404);
    }
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to fetch record from database'], 500);
}
