<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
} else if (isset($input['userId'])) {
    $userId = $input['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'User ID is required'], 400);
}

function getChildProfiles($userId)
{
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $sql = "SELECT c.id, c.name, c.profile_picture_path 
            FROM children c
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        sendResponse(['error' => 'Failed to prepare statement: ' . $conn->error], 500);
    }
    
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === FALSE) {
        sendResponse(['error' => 'Failed to get child profiles: ' . $conn->error], 500);
    }

    $profiles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $profiles[] = $row;
        }
    }
    
    sendResponse($profiles);
}

getChildProfiles($userId);
?>
