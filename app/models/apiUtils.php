<?php

//modified this so you have to specify the file type you need in the url
function getResponseType()
{
    if (isset($_GET['format'])) {
        $responseType = $_GET['format'];
        if ($responseType === 'xml') {
            return 'xml';
        }
    }
    return 'json';
}

function sendResponse($data, $statusCode = 200)
{
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

function arrayToXml($data, $rootElement = 'response', $xml = null)
{
    if ($xml === null) {
        $xml = new SimpleXMLElement("<{$rootElement}/>");
    }
    foreach ($data as $key => $value) {
        $key = preg_replace('/[^a-z0-9_]/i', '_', $key);
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            arrayToXml($value, $key, $xml->addChild($key));
        } else {
            if (is_numeric($key)) {
                $key = 'item';
            }
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
    return $xml->asXML();
}

// Validate user existence
function userExistsById($connection, $userId)
{
    $stmt = $connection->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function mediaExistsById($connection, $userId, $childId, $mediaId)
{
    $mediaStmt = $connection->prepare("SELECT COUNT(*) FROM media WHERE id = ? AND user_id = ? AND child_id = ?");
    $mediaCount = 0;
    $mediaStmt->bind_param('iii', $mediaId, $userId, $childId);
    $mediaStmt->execute();
    $mediaStmt->bind_result($mediaCount);
    $mediaStmt->fetch();
    $mediaStmt->close();

    if ($mediaCount === 0) 
        return false;
    return true;
}

function postExistsById($connection, $userId, $childId, $postId)
{
    $postStmt = $connection->prepare("SELECT COUNT(*) FROM posts WHERE id = ? AND user_id = ? AND child_id = ?");
    $postCount = 0;
    $postStmt->bind_param('iii', $postId, $userId, $childId);
    $postStmt->execute();
    $postStmt->bind_result($postCount);
    $postStmt->fetch();
    $postStmt->close();

    if ($postCount === 0) 
        return false;
    return true;
}
