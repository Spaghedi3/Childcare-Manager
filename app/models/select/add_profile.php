<?php
require_once '../models/db.php';

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
        echo json_encode($response);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add child profile: ' . $conn->error]);
    }
}

addChildProfile();

?>
