<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

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

$stmt = $connection->prepare("SELECT day, hour, type, items FROM Schedule WHERE user_id = ? AND child_id = ?");
$stmt->bind_param("ii", $userId, $childId);

if (!$stmt->execute()) {
    sendResponse(['status' => 'error', 'message' => 'Failed to get schedule'], 500);
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    sendResponse((object) []); // Send empty object {} if schedule is empty
}

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $day = $row['day'];
    $hour = $row['hour'];
    $type = $row['type'];
    $items = $row['items'] ? explode(',', $row['items']) : [];

    if (!isset($schedule[$day])) {
        $schedule[$day] = [];
    }

    $schedule[$day][$hour] = [
        'type' => $type,
        'items' => $items
    ];
}

sendResponse($schedule);
