<?php
require_once '../models/db.php';

function getChildProfiles() {
    $conn = Database::getConnection();
    $sql = "SELECT name, profile_picture_path FROM children"; 
    $result = $conn->query($sql);

    if ($result === FALSE) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get child profiles: ' . $conn->error]);
        exit();
    }

    $profiles = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $profiles[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($profiles);
}

getChildProfiles();

?>
