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
$dest_path = $uploadFileDir . $fileName;

// Create directory if not exists
if (!is_dir($uploadFileDir)) {
    mkdir($uploadFileDir, 0777, true);
}

// Move the file to the directory
if (move_uploaded_file($fileTmpPath, $dest_path)) {

    $title = basename($fileName, '.' . $fileExtension); // File name without extension
    date_default_timezone_set('Europe/Bucharest');
    $datetime = date('Y-m-d H:i:s');
    $media_link = $dest_path;

    // Insert into database
    $sql = "INSERT INTO Media (user_id, child_id, title, datetime, type, media_link) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iissss", $userId, $childId, $title, $datetime, $type, $media_link);

    if ($stmt->execute()) {
        sendResponse(['status' => 'success', 'message' => 'File uploaded successfully', 'id' => $stmt->insert_id, 'type' => $type]);
    } else {
        sendResponse(['status' => 'error', 'message' => 'Failed to upload file'], 500);
    }

    $stmt->close();
} else {
    sendResponse(['status' => 'error', 'message' => 'Failed to move file'], 500);
}
