<?php

//modified this so you have to specify the file type you need in the url
function getResponseType() {
    if (isset($_GET['response_type'])) {
        $responseType = $_GET['response_type'];
        if ($responseType === 'xml') {
            return 'xml';
        }
    }
    return 'json';
}

function sendResponse($data, $statusCode = 200) {
    $responseType = getResponseType();
    http_response_code($statusCode);
    
    if ($responseType == 'xml') {
        header('Content-Type: application/xml');
        echo arrayToXml($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    exit();
}

function arrayToXml($data, $rootElement = 'response', $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement("<{$rootElement}/>");
    }
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            arrayToXml($value, $key, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
    return $xml->asXML();
}

// Validate user existence
function userExistsById($connection, $userId) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}