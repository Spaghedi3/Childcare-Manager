<?php

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
