<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
}

if (isset($_SESSION['childId'])) {
    $childId = $_SESSION['childId'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Select child at /api/select'], 400);
}

function getMedicalInfo($userId, $childId) {
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $sql = "SELECT * FROM Medical_Info WHERE user_id = ? AND child_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        sendResponse(['error' => 'Failed to prepare statement: ' . $conn->error], 500);
    }
    
    $stmt->bind_param("ii", $userId, $childId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === FALSE) {
        sendResponse(['error' => 'Failed to get medical information: ' . $conn->error], 500);
    }

    $medicalInfo = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $medicalInfo[] = $row;
        }
    } else {
        sendResponse(['status' => 'error', 'message' => 'No medical information found'], 404);
    }
    
    sendResponse($medicalInfo);
}

getMedicalInfo($userId, $childId);
?>
