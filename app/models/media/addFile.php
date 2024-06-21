<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

$conn = Database::getConnection();

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (isset($_SESSION['childId'])) {
    $childId = $_SESSION['childId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
}

$mimeToType = [
    'image/jpeg' => 'image',
    'image/png' => 'image',
    'image/gif' => 'image',
    'audio/mpeg' => 'audio',
    'audio/wav' => 'audio',
    'audio/mp4' => 'audio',
    'video/mp4' => 'video',
    'application/pdf' => 'document',
    'application/msword' => 'document',
];

// Check if file is uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    // File details
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Determine the media type from the file's MIME type
    if (array_key_exists($fileType, $mimeToType)) {
        $type = $mimeToType[$fileType];
    } else {
        sendResponse(['status' => 'error', 'message' => 'Unsupported media type'], 400);
    }

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
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $userId, $childId, $title, $datetime, $type, $media_link);

        if ($stmt->execute()) {
            sendResponse(['status' => 'success', 'message' => 'File uploaded successfully', 'id' => $stmt->insert_id]);
        } else {
            sendResponse(['status' => 'error', 'message' => 'Failed to upload file'], 500);
        }

        $stmt->close();
    } else {
        sendResponse(['status' => 'error', 'message' => 'Failed to move file'], 500);
    }
} else {
    sendResponse(['status' => 'error', 'message' => 'File upload error'], 400);
}
