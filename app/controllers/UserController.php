<?php

class UserController
{
    public function userAPI()
    {
        require_once '../app/models/db.php';
        require_once '../app/models/auth.php';
        require_once '../app/models/apiUtils.php';

        $connection = Database::getConnection();

        $input = json_decode(file_get_contents('php://input'), true);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->validateSession();
                require_once '../app/models/profile/exportData.php';
                break;
            case 'POST':
                $this->validatePost($input);
                require_once '../app/models/register/addUser.php';
                break;
            case 'DELETE':
                $this->validateSession();
                $this->validateDelete($input);
                require_once '../app/models/profile/deleteAccount.php';
                break;
            case 'PATCH':
                $this->validateSession();
                $this->validatePatch($input);
                require_once '../app/models/profile/changeUserData.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }

    private function validateSession()
    {
        if (!isset($_SESSION['userId']))
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
    }

    private function validatePost($input)
    {
        if (!isset($input['username']))
            sendResponse(['status' => 'error', 'message' => 'Username is required'], 400);

        if (!is_string($input['username']))
            sendResponse(['status' => 'error', 'message' => 'Username must be a string'], 400);

        if (!isValidUsername($input['username']))
            sendResponse(['status' => 'error', 'message' => 'Invalid username'], 400);

        if (userExists($input['username']))
            sendResponse(['status' => 'error', 'message' => 'Username is already taken'], 400);

        if (!isset($input['email']))
            sendResponse(['status' => 'error', 'message' => 'Email is required'], 400);

        if (!is_string($input['email']))
            sendResponse(['status' => 'error', 'message' => 'Email must be a string'], 400);

        if (!isValidEmail($input['email']))
            sendResponse(['status' => 'error', 'message' => 'Invalid email'], 400);

        if (!isset($input['password'], $input['confirmPassword']))
            sendResponse(['status' => 'error', 'message' => 'Password and confirm password are required'], 400);

        if (!is_string($input['password']))
            sendResponse(['status' => 'error', 'message' => 'Password must be a string'], 400);

        if (!is_string($input['confirmPassword']))
            sendResponse(['status' => 'error', 'message' => 'Confirm password must be a string'], 400);

        if (!isValidPassword($input['password']))
            sendResponse(['status' => 'error', 'message' => 'Invalid password'], 400);

        if ($input['password'] !== $input['confirmPassword'])
            sendResponse(['status' => 'error', 'message' => 'Passwords do not match'], 400);
    }

    private function validateDelete($input)
    {
        if (!isset($input['password']))
            sendResponse(['status' => 'error', 'message' => 'Password is required'], 400);

        if (!is_string($input['password']))
            sendResponse(['status' => 'error', 'message' => 'Password must be a string'], 400);
    }

    private function validatePatch($input)
    {
        if (isset($input['username']) && !is_string($input['username']))
            sendResponse(['status' => 'error', 'message' => 'Username must be a string'], 400);

        if (isset($input['email']) && !is_string($input['email']))
            sendResponse(['status' => 'error', 'message' => 'Email must be a string'], 400);

        if (isset($input['oldPassword']) && !is_string($input['oldPassword']))
            sendResponse(['status' => 'error', 'message' => 'Old password must be a string'], 400);

        if (isset($input['newPassword']) && !is_string($input['newPassword']))
            sendResponse(['status' => 'error', 'message' => 'New password must be a string'], 400);

        if (isset($input['confirmPassword']) && !is_string($input['confirmPassword']))
            sendResponse(['status' => 'error', 'message' => 'Confirm password must be a string'], 400);
    }
}
