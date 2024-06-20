<?php

require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

$input = json_decode(file_get_contents('php://input'), true);

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

$schedule = $input['schedule'];

foreach ($schedule as $day => $hours) {
    foreach ($hours as $hour => $details) {
        if ($details === null) {
            // Delete the entry if details for this hour are null
            $stmt = $connection->prepare("DELETE FROM Schedule WHERE user_id = ? AND child_id = ? AND day = ? AND hour = ?");
            $stmt->bind_param("iisi", $userId, $childId, $day, $hour);
        } else {
            $type = $details['type'];
            $items = isset($details['items']) ? implode(',', $details['items']) : null;

            $stmt = $connection->prepare("INSERT INTO Schedule (user_id, child_id, day, hour, type, items) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE type = VALUES(type), items = VALUES(items)");
            $stmt->bind_param("iisiss", $userId, $childId, $day, $hour, $type, $items);
        }

        if (!$stmt->execute()) {
            sendResponse(['status' => 'error', 'message' => 'Failed to update schedule'], 500);
        }
    }
}

sendResponse(['status' => 'success', 'message' => 'Schedule updated successfully']);