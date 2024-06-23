<?php

// File details
$fileTmpPath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$fileSize = $_FILES['file']['size'];
$fileType = $_FILES['file']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

// Directory to save file
$uploadFileDir = '../media/' . $type . 's/';

// Create directory if not exists
if (!is_dir($uploadFileDir)) {
    mkdir($uploadFileDir, 0777, true);
}

// Move the file to the directory
$title = basename($fileName, '.' . $fileExtension); // File name without extension
date_default_timezone_set('Europe/Bucharest');
$datetime = date('Y-m-d H:i:s');

// We use transaction since we are inserting into the database and moving the file
$connection->begin_transaction();

try {
    // Insert into database
    $sql = "INSERT INTO Media (user_id, child_id, title, description, datetime, type) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iissss", $userId, $childId, $title, $fileExtension, $datetime, $type);

    if ($stmt->execute()) {
        $dest_path = $uploadFileDir . $stmt->insert_id . '.' . $fileExtension;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $connection->commit();
            sendResponse(['status' => 'success', 'message' => 'File uploaded successfully', 'id' => $stmt->insert_id, 'type' => $type]);
        } else {
            $connection->rollback();
            sendResponse(['status' => 'error', 'message' => 'Failed to move file'], 500);
        }
    } else {
        $connection->rollback();
        sendResponse(['status' => 'error', 'message' => 'Failed to upload file'], 500);
    }
    $stmt->close();
} catch (Exception $e) {
    $connection->rollback();
    sendResponse(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
}
?>
