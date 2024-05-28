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

    $sql = "INSERT INTO children (name, profile_picture_path) VALUES ('$name', '$profilePicturePath')";
    
    if ($conn->query($sql) === TRUE) {
        $childId = $conn->insert_id;

        $sql = "INSERT INTO Users_Children (user_id, child_id) VALUES ('$userId', '$childId')";
        
        if ($conn->query($sql) === TRUE) {
            $response = [
                'id' => $childId,
                'name' => $name,
                'profile_picture_path' => $profilePicturePath
            ];
            sendResponse($response);
        } else {
            $conn->query("DELETE FROM children WHERE id = '$childId'");
            sendResponse(['error' => 'Failed to link child profile to user: ' . $conn->error], 500);
        }
    } else {
        sendResponse(['error' => 'Failed to add child profile: ' . $conn->error], 500);
    }
}

addChildProfile($userId);
?>
