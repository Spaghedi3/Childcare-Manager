<?php
require_once '../app/models/db.php';
require_once '../app/models/apiUtils.php';

function updateMedicalInfo()
{
    if(isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
    } else {
        sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
    }
    
    if (isset($_SESSION['childId'])) {
        $childId = $_SESSION['childId'];
    } else {
        sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
    }

    $childId = $_SESSION['childId'];
    $conn = Database::getConnection();
    if ($conn->connect_error) {
        sendResponse(['error' => 'Database connection failed: ' . $conn->connect_error], 500);
        return;
    }

    // Get the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        sendResponse(['error' => 'Invalid input'], 400);
        return;
    }

    // Fields mapping for medical_info table
    $medicalInfoFields = [
        'emergency' => 'emergency_contact_info',
        'med_conditions' => 'medical_conditions',
        'medication' => 'medication',
        'allergies' => 'allergies',
        'immunization' => 'immunization_record',
        'insurance' => 'insurance_info',
        'history' => 'medical_history'
    ];

    $key = key($data);
    $value = $data[$key];

    if (array_key_exists($key, $medicalInfoFields)) {
        $updateMedicalInfoFields = $medicalInfoFields[$key] . " = ?";
        $updateMedicalInfoValues[] = $value;
    }

    if (!empty($updateMedicalInfoFields)) {
        $sqlMedicalInfo = "UPDATE medical_info SET " .  $updateMedicalInfoFields . " WHERE child_id = ?";
        $stmtMedicalInfo = $conn->prepare($sqlMedicalInfo);
        $updateMedicalInfoValues[] = $childId;

        if ($stmtMedicalInfo) {
            $stmtMedicalInfo->bind_param(str_repeat('s', count($updateMedicalInfoValues)), ...$updateMedicalInfoValues);

            if (!$stmtMedicalInfo->execute()) {
                sendResponse(['error' => 'Error updating medical information: ' . $stmtMedicalInfo->error], 500);
                $stmtMedicalInfo->close();
                $conn->close();
                return;
            }
            $stmtMedicalInfo->close();
        } else {
            sendResponse(['error' => 'Failed to prepare statement: ' . $conn->error], 500);
            $conn->close();
            return;
        }
    }

    if (isset($data['date_of_birth'])) {
        $sqlChildren = "UPDATE children SET birth_date = ? WHERE id = ?";
        $stmtChildren = $conn->prepare($sqlChildren);
        $stmtChildren->bind_param('si', $data['date_of_birth'], $childId);

        if (!$stmtChildren->execute()) {
            sendResponse(['error' => 'Error updating birth date: ' . $stmtChildren->error], 500);
            $stmtChildren->close();
            $conn->close();
            return;
        }
        $stmtChildren->close();
    }

    $conn->close();
    sendResponse(['success' => true]);
}

updateMedicalInfo();
