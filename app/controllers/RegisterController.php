<?php

class RegisterController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Register/header.php';
        require_once '../app/views/Register/register.php';
        require_once '../app/views/footer.php';
    }
    public function register()
    {
        require_once '../app/models/register.php';
    }
}