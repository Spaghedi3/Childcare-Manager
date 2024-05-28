<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function addChildProfile() {
    $conn = Database::getConnection();
    $name = $conn->real_escape_string('New Child'); 
    $profilePicturePath = $conn->real_escape_string('app/views/images/logo.ico'); 

    $sql = "INSERT INTO children (name, profile_picture_path) VALUES ('$name', '$profilePicturePath')";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'id' => $conn->insert_id,
            'name' => $name,
            'profile_picture_path' => $profilePicturePath
        ];
        sendResponse($response);
    } else {
       sendResponse(['error' => 'Failed to add child profile: ' . $conn->error], 500);
    }
}

addChildProfile();
?>
