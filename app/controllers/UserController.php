<?php

class UserController
{
    public function userAPI()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require_once '../app/models/profile/exportData.php';
                break;
            case 'POST':
                require_once '../app/models/register/addUser.php';
                break;
            case 'DELETE':
                require_once '../app/models/profile/deleteAccount.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }
}
