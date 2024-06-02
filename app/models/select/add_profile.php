<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];
} else if (isset($input['userId'])) {
    $userId = $input['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'User Id is required'], 400);
}

function addChildProfile($userId)
{
    $conn = Database::getConnection();
    $name = $conn->real_escape_string('New Child');
    $profilePicturePath = $conn->real_escape_string('app/views/images/logo.ico');

    $sql = "INSERT INTO children (user_id, name, profile_picture_path) VALUES ('$userId', '$name', '$profilePicturePath')";

    if ($conn->query($sql) === TRUE) {
        $childId = $conn->insert_id;

        setcookie('childId', $childId, time() + (86400 * 30), "/"); 

        $response = [
            'id' => $childId,
            'user_id' => $userId,
            'name' => $name,
            'profile_picture_path' => $profilePicturePath
        ];
        sendResponse($response);
    } else {
        sendResponse(['error' => 'Failed to add child profile: ' . $conn->error], 500);
    }
}

addChildProfile($userId);
?>
