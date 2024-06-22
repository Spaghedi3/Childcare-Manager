<?php

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

if ($userId = userExists($username)) {
    if (verifyPassword($userId, $password)) {

        session_destroy();
        if (isset($_REQUEST["remember"]) && $_REQUEST["remember"] == "on") {
            ini_set('session.cookie_lifetime', 3600 * 24 * 7 * 12); // 12 weeks
        } else {
            ini_set('session.cookie_lifetime', 0);
        }
        session_start();
        session_regenerate_id(true); // Regenerate session ID to apply the new settings

        $_SESSION['userId'] = $userId;

        sendResponse(['status' => 'success', 'message' => 'Login successful']);
    } else {
        sendResponse(['status' => 'error', 'message' => 'Incorrect password!'], 400);
    }
} else {
    sendResponse(['status' => 'error', 'message' => 'User does not exist'], 400);
}
