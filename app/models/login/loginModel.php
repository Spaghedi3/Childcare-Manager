<?php

require_once '../app/models/auth.php';
require_once '../app/models/apiUtils.php';

if (isset($_SESSION['userId'])) {
    sendResponse(['status' => 'error', 'message' => 'Already logged in'], 400);
}

if (isset($_REQUEST['username'])) {
    $username = $_REQUEST['username'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Username is required'], 400);
}

if (isset($_REQUEST['password'])) {
    $password = $_REQUEST['password'];
} else {
    sendResponse(['status' => 'error', 'message' => 'Password is required'], 400);
}

if ($userId = userExists($username)) {
    if (verifyPassword($userId, $password)) {

        // TODO - Implement remember me functionality
        // if (isset($_REQUEST["remember"]) && $_REQUEST["remember"] == "on") {
        //     ini_set('session.cookie_lifetime', 3600 * 24 * 7 * 12); // 12 weeks
        //     session_regenerate_id(true); // Regenerate session ID to apply the new settings
        // } else {
        //     ini_set('session.cookie_lifetime', 0);
        //     session_regenerate_id(true);
        // }
        $_SESSION['userId'] = $userId;

        sendResponse(['status' => 'success', 'message' => 'Login successful']);
    } else {
        sendResponse(['status' => 'error', 'message' => 'Incorrect password!'], 400);
    }
} else {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}
