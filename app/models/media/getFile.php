<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

// if (isset($_GET['type'])) {
// 	$type = $_GET['type'];
// 	if ($type != 'audio' && $type != 'video' && $type != 'document') {
// 		$type = 'image';
// 	}
// } else {
// 	$type = 'image';
// }

// TODO check type?

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

$connection = Database::getConnection();

// TODO check if id exists

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
