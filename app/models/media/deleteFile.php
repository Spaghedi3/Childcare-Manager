<?php

$sql = "SELECT media_link FROM Media WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $media_link = $row['media_link'];

        // Check how many times the file is referenced in the database
        $sql = "SELECT COUNT(*) as count FROM Media WHERE media_link = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $media_link);
        $stmt->execute();
        $countResult = $stmt->get_result();
        $countRow = $countResult->fetch_assoc();

        // Delete the record from the database
        $sql = "DELETE FROM Media WHERE id = ? AND user_id = ? AND child_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iii", $id, $userId, $childId);

        if ($stmt->execute()) {
            // If the file is referenced only once, delete the file from the filesystem
            if ($countRow['count'] == 1) {
                if (file_exists($media_link)) {
                    unlink($media_link);
                }
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
