<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

$connection = Database::getConnection();

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (isset($_SESSION['childId'])) {
    $childId = $_SESSION['childId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
}

if(!mediaExistsById($connection, $userId, $childId, $id)) {
    sendResponse(['status' => 'error', 'message' => 'Media not found'], 404);
}

$stmt = $connection->prepare("SELECT title, datetime, media_link, type FROM media WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$result = $result->fetch_assoc();
$filePath = $result['media_link'];
$fileType = mime_content_type($filePath);
$fileData = base64_encode(file_get_contents($filePath));
$result['media_data'] = "data:$fileType;base64,$fileData";
unset($result['media_link']);


sendResponse($result);
