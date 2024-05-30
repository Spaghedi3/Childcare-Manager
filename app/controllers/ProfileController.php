<?php

require_once '../app/models/apiUtils.php';

class ProfileController
{
    public function index()
    {
        require_once '../app/models/profile/profileModel.php';

        require_once '../app/views/header.php';
        require_once '../app/views/Profile/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Profile/profile.php';
        if (isset($_GET['form'])) {
            $form = $_GET['form'];
            switch ($form) {
                case 'changeUsername':
                    require_once '../app/views/Profile/changeUsername.php';
                    break;
                case 'changeEmail':
                    require_once '../app/views/Profile/changeEmail.php';
                    break;
                case 'changePassword':
                    require_once '../app/views/Profile/changePassword.php';
                    break;
                case 'deleteAccount':
                    require_once '../app/views/Profile/deleteAccount.php';
                    break;
            }
        }
        require_once '../app/views/Profile/footer.php';
    }

    public function usernameAPI()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            require_once '../app/models/profile/changeUsername.php';
        }
        else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }

    public function emailAPI()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            require_once '../app/models/profile/changeEmail.php';
        }
        else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }

    public function passwordAPI()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            require_once '../app/models/profile/changePassword.php';
        }
        else {
            sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }
    }
}
