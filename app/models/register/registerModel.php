<?php

require_once '../app/models/db.php';
require_once '../app/models/auth.php';

$username = $_POST['username'];
if (!isValidUsername($username)) {
    header('Location: /register?message=Invalid username, Please try again!', TRUE, 303);
    exit();
}
if (userExists($username)) {
    header('Location: /register?message=User already exists, Please try again!', TRUE, 303);
    exit();
}

$email = $_POST['email'];
if (!isValidEmail($email)) {
    header('Location: /register?message=Invalid email, Please try again!', TRUE, 303);
    exit();
}

$password = $_POST['password'];
if (!isValidPassword($password)) {
    header('Location: /register?message=Invalid password, Please try again!', TRUE, 303);
    exit();
}
$confirmPassword = $_POST['confirm_password'];
if ($password !== $confirmPassword) {
    header('Location: /register?message=Passwords do not match, Please try again!', TRUE, 303);
    exit();
}

// TODO Hash the password for security

$connection = Database::getConnection();
$query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

if (mysqli_query($connection, $query)) {
    header('Location: /login?message=Registered successfully, Please login!', TRUE, 303);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($connection);
}