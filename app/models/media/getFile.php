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

// if (isset($_COOKIE['userId'])) {
//     $userId = $_COOKIE['userId'];
// } else if (isset($input['userId'])) {
//     $userId = $input['userId'];
// } else {
//     sendResponse(['status' => 'error', 'message' => 'User Id is required'], 400);
// }

// if (isset($_COOKIE['childId'])) {
//     $childId = $_COOKIE['childId'];
// } else if (isset($input['childId'])) {
//     $childId = $input['childId'];
// } else {
//     sendResponse(['status' => 'error', 'message' => 'Child Id is required'], 400);
// }

$connection = Database::getConnection();

// TODO check if id exists

$stmt = $connection->prepare("SELECT title, datetime, media_link, type FROM media WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$result = $result->fetch_assoc();
sendResponse($result);