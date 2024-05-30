<?php

class LoginController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Login/header.php';
        require_once '../app/views/Login/login.php';
        require_once '../app/views/footer.php';
    }

    public function login()
    {
        require_once '../app/models/login/loginModel.php';
    }

    public function logout()
    {
        setcookie('userId', '', time() - 3600, '/');
        setcookie('childId', '', time() - 3600, '/');
        header('Location: /home');
    }
}
