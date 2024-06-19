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
}
