<?php

class TimelineController
{
    public function index()
    {
        require_once '../app/views/header.php';
        require_once '../app/views/Timeline/header.php';
        require_once '../app/views/navbar.php';
        require_once '../app/views/Timeline/timeline.php';
        require_once '../app/views/footer.php';
    }

    public function postsAPI()
    {
        require_once '../app/models/db.php';
        require_once '../app/models/apiUtils.php';

        $connection = Database::getConnection();
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
        } else {
            sendResponse(['status' => 'error', 'message' => 'Log in at /api/session'], 400);
        }

        if (isset($_SESSION['childId'])) {
            $childId = $_SESSION['childId'];
        } else {
            sendResponse(['status' => 'error', 'message' => 'Child ID is required'], 400);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require_once '../app/models/timeline/getPosts.php';
                break;
            case 'POST':
                require_once '../app/models/timeline/addPost.php';
                break;
            case 'DELETE':
                require_once '../app/models/timeline/deletePost.php';
                break;
            case 'PATCH':
                require_once '../app/models/timeline/editPost.php';
                break;
            default:
                sendResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
                break;
        }
    }
}
