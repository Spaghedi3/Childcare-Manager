<?php

require_once '../app/models/db.php';
$connection = Database::getConnection();

$cookie_name = 'userId';

// Select the user from the database
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $userID = $user['id'];

    // Verify the password
    if ($password === $user['password']) {
        // $childQuery = "SELECT * FROM children WHERE user = '$userID'";

        // $childResult = mysqli_query($connection, $childQuery);

        // if (mysqli_num_rows($childResult) > 0) {
        //     $child = mysqli_fetch_assoc($childResult);
        //     $cookie_value = $child['id'];
        //     setcookie($cookie_name, $cookie_value, time() + (33600 * 24), "/");
        //     header('Location: /select', TRUE, 303);
        // }
        
        $cookie_value = $user['id'];
        if(isset($_REQUEST["remember"]) && $_REQUEST["remember"] == "on") {
            setcookie($cookie_name, $cookie_value, time() + (3600 * 24 * 7 * 12), "/"); // 12 weeks
        }
        else {
            setcookie($cookie_name, $cookie_value, 0, "/");
        }
       // setcookie('childId  ', 1);   DE PUS IN select   

        header('Location: /select', TRUE, 303);
    } else {
        header('Location: /login?message=Invalid password, Please try again!', TRUE, 303);
    }
} else {
    header('Location: /login?message=User does not exist!', TRUE, 303);
}

mysqli_close($connection);
