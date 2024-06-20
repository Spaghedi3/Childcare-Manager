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

    public function sessionAPI()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                require_once '../app/models/login/loginModel.php';
                break;
            case 'DELETE':
                require_once '../app/models/login/logoutModel.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }
}
