<?php

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

    public function changeUsername()
    {
        require_once '../app/models/profile/changeUsername.php';
    }

    public function changeEmail()
    {
        require_once '../app/models/profile/changeEmail.php';
    }

    public function changePassword()
    {
        require_once '../app/models/profile/changePassword.php';
    }
}
