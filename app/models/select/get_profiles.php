<?php
require_once '../app/models/db.php'; 
require_once '../app/models/apiUtils.php';

function getChildProfiles() {
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $sql = "SELECT id, name, profile_picture_path FROM children";
    $result = $conn->query($sql);

    if ($result === FALSE) {
       sendResponse(['error' => 'Failed to get child profiles: ' . $conn->error], 500);
    }

    $profiles = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $profiles[] = $row;
        }
    }

    sendResponse($profiles);
}

getChildProfiles();
?>
