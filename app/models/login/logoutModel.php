<?php

require_once '../app/models/apiUtils.php';

if(!isset($_SESSION['userId'])) {
    sendResponse(['status' => 'error', 'message' => 'You are not logged in!'], 400);
}

session_unset();

session_destroy();

// Delete the session cookie, if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

setcookie('childId', '', time() - 3600, '/');

sendResponse(['status' => 'success', 'message' => 'Logout successful']);
