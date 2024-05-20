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
}
