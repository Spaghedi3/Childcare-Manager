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
    public function userAPI()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once '../app/models/register/addUser.php';
        } else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }
}
