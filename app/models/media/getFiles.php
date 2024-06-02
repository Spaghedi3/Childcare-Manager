<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

if (isset($_GET['type'])) {
	$type = $_GET['type'];
	if ($type != 'audio' && $type != 'video' && $type != 'document') {
		$type = 'image';
	}
} else {
	$type = 'image';
}

// TODO check type?

$input = json_decode(file_get_contents('php://input'), true);

if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
} else if (isset($input['userId'])) {
    $userId = $input['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'User Id is required'], 400);
}

if (isset($_COOKIE['childId'])) {
    $childId = $_COOKIE['childId'];
} else if (isset($input['childId'])) {
    $childId = $input['childId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Child Id is required'], 400);
}

$connection = Database::getConnection();

$stmt = $connection->prepare("SELECT * FROM media WHERE user_id = ? AND child_id = ? AND type = ?");
$stmt->bind_param("iis", $userId, $childId, $type);
$stmt->execute();
$result = $stmt->get_result();

$result = $result->fetch_all(MYSQLI_ASSOC);
sendResponse($result);

echo "</div>";