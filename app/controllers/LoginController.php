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
        require_once '../app/models/auth.php';
        require_once '../app/models/apiUtils.php';

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->validateLogin();
                require_once '../app/models/login/loginModel.php';
                break;
            case 'DELETE':
                $this->validateLogout();
                require_once '../app/models/login/logoutModel.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }

    private function validateLogin()
    {
        if (isset($_SESSION['userId'])) {
            sendResponse(['status' => 'error', 'message' => 'Already logged in'], 400);
        }
        
        if (!isset($_REQUEST['username']))
            sendResponse(['status' => 'error', 'message' => 'Username is required'], 400);
        
        if (!isset($_REQUEST['password'])) 
            sendResponse(['status' => 'error', 'message' => 'Password is required'], 400);

        if (!isset($_REQUEST['username']) && !is_string($_REQUEST['username']))
            sendResponse(['status' => 'error', 'message' => 'Username must be a string'], 400);

        if (!isset($_REQUEST['password']) && !is_string($_REQUEST['password']))
            sendResponse(['status' => 'error', 'message' => 'Password must be a string'], 400);
    }

    private function validateLogout()
    {
        if(!isset($_SESSION['userId'])) {
            sendResponse(['status' => 'error', 'message' => 'You are not logged in!'], 400);
        }
    }
}
