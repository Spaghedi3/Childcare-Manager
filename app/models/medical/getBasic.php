<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';


function getChildBasic()
{

    if (!isset($_SESSION['childId'])) {
        sendResponse(['error' => 'Child ID is required'], 400);
    }
    $childId = $_SESSION['childId'];
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
    }

    $sql = "SELECT c.id, c.name, c.birth_date
            FROM children c
            WHERE c.id = $childId";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        sendResponse(['error' => 'Failed to get child profiles: ' . $conn->error], 500);
    }

    $info = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $birthDate = new DateTime($row['birth_date']);
            $currentDate = new DateTime();
            $age = $currentDate->diff($birthDate)->y;
            $row['age'] = $age;
            $info[] = $row;
        }
    }

    sendResponse($info);
}

getChildBasic();
