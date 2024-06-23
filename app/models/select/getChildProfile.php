<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function getChildProfile($id = null)
{
    $childId = $id;

    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $stmt = $conn->prepare("SELECT id, name, profile_picture_path, birth_date FROM children WHERE id = ?");
    $stmt->bind_param("i", $childId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();

        // Calculate age from birth date
        $birthDate = new DateTime($profile['birth_date']);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;

        // Add age to the profile data
        $profile['age'] = $age;

        // Store childId in session
        $_SESSION['childId'] = $childId;

        sendResponse($profile);
    } else {
        sendResponse(['error' => 'Child profile not found'], 404);
    }

    $stmt->close();
    $conn->close();
}

getChildProfile($id);

?>
