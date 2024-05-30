<?php

require_once '../app/models/db.php';

// Returns user id if user exists, otherwise false
function userExists($username)
{
    $connection = Database::getConnection();

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        return $user['id'];
    } else
        return false;
}

function verifyPassword($userId, $password)
{
    $connection = Database::getConnection();

    $query = "SELECT * FROM users WHERE id = '$userId'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        return password_verify($password, $user['password']);
    }
}

function isValidUsername($username)
{
    $pattern = '/^[a-z0-9_]+$/';
    return preg_match($pattern, $username);
}

function isValidEmail($email)
{
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email);
}

function isValidPassword($password)
{
    $pattern = '/^[a-zA-Z0-9!@#$%^&*()_+=-]{8,}$/';
    return preg_match($pattern, $password);
}
