<?php

require_once '../app/models/auth.php';

$username = $_POST['username'];
$password = $_POST['password'];

if ($userId = userExists($username)) {
    if (verifyPassword($userId, $password)) {
        $cookie_name = 'userId';
        $cookie_value = $userId;

        if (isset($_REQUEST["remember"]) && $_REQUEST["remember"] == "on") {
            setcookie($cookie_name, $cookie_value, time() + (3600 * 24 * 7 * 12), "/"); // 12 weeks
        } else {
            setcookie($cookie_name, $cookie_value, 0, "/");
        }

        header('Location: /select', TRUE, 303);
    } else {
        header('Location: /login?message=Invalid password, Please try again!', TRUE, 303);
    }
} else {
    header('Location: /login?message=User does not exist!', TRUE, 303);
}
